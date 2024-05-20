<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\Master\Item;
use App\Models\Master\Parameter;
use App\Models\Transaction\Transaction as TransactionTransaction;
use App\Models\Transaction\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use PDF;

use function PHPUnit\Framework\isNull;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $card = $this->cardItem(null);
        return view('pos.order', ['cards' => $card]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $model = new Transaction();
            $model->status_id = 1;
            $model->invoice_no = getNumber();
            $model->date = date('y-m-d');
            $model->total = str_replace('.', '', $request->total);
            $model->grand_total = str_replace('.', '', $request->total);
            $model->cost = str_replace('.', '', $request->total_cost);

            // $sub = round(reverse_tax($model->total));

            // $model->pajak = $model->total - $sub;
            $model->sub_total = $model->total;
            $model->created_by = Auth::user()->id;
            $model->save();

            $index = $request->index;
            foreach ($index as $key => $value) {
                $dtl = new TransactionDetail();
                $dtl->transaction_id = $model->id;
                $dtl->item_id = json_encode($request->item_id[$value]);
                $dtl->qty_item = json_encode($request->item_qty[$value]);
                // $dtl->item_pajak = json_encode($request->item_pajak[$value]);
                $dtl->item_price = json_encode($request->item_price[$value]);
                $dtl->item_discount = json_encode($request->item_discount[$value]);
                $dtl->qty = $request->qty[$key];
                $dtl->item_name = strtoupper($request->item_name[$key]);
                $dtl->total = $request->price[$key];
                // $dtl->ppn = $request->pajak[$key];
                $dtl->discount = $request->discount[$key];
                $dtl->cost = $request->cost[$key];
                $dtl->price = $request->sub_price[$key];
                $dtl->notes = $request->notes[$key];

                foreach ($request->item_id[$value] as $k => $val) {
                    $item = Item::find($val);

                    if ($item->qty < ($request->item_qty[$value][$k])) {
                        $data = [
                            'message' => 'Sisa stok ' . $item->name . ' adalah ' . $item->qty,
                        ];

                        $content = returnJson(false, $data);
                        $status = 200;

                        return (new Response($content, $status))
                            ->header('Content-Type', 'json');
                    }

                    $item->qty = $item->qty - ($request->item_qty[$value][$k]) ;
                    $item->save();
                }

                $dtl->save();
            }

            DB::commit();
            $data = [
                'url' => route('pos.show', ['id' => base64_encode($model->id)]),
            ];

            $content = returnJson(true, $data);
            $status = 200;

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        } catch (Exception $e) {
            DB::rollBack();

            $data = [
                'error' => json_encode($e),
            ];
            $content = returnJson(false, $data);
            $status = 200;

            Log::error($content);

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = base64_decode($id);

        $model = Transaction::find($id);

        return view('pos.show', ['model' => $model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => 'Anda belum memilih Tipe Pembayaran',
        ];
        $request->validate([
            'tipe_pembayaran' => 'required'
        ], $messages);

        DB::beginTransaction();
        try {
            $model = Transaction::find(base64_decode($id));
            $model->status_id = 2;
            $model->customer_name = strtoupper($request->name);
            $model->customer_phone = $request->no_telp;
            $model->customer_age = $request->usia;
            $model->customer_address = $request->alamat;
            $model->payment_type_id = $request->tipe_pembayaran;
            $model->card_number = $request->card_number;
            $model->discount = str_replace('.', '', $request->discount);
            $model->grand_total = $model->total - $model->discount;
            $model->save();

            DB::commit();

            $content = returnJson(true, '');
            $status = 200;

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        } catch (Exception $e) {
            DB::rollBack();

            $data = [
                'error' => json_encode($e),
            ];
            $content = returnJson(false, $data);
            $status = 400;

            Log::error($content);

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function cardItem($param)
    {
        if (!is_null($param)) {
            $items = Item::where('name', 'like', '%' . $param . '%')->get();
        } else {
            $items = Item::all();
        }

        foreach ($items as $key => $value) {
            $stock = ($value->qty == 0) ? '<small class="text-red">Out of Stock !</small>' : '<small>In Stock</small>';
            $pajak = 0; //pajak($value->sale_price);
            $harga = $value->sale_price + $pajak;

            if (!is_null($value->discount)) {
                $total = '<dd style="color:grey"><strong><s>Rp. ' . number_format($harga, 0, ',', '.') . '</s></strong></dd>';
                $harga = $harga - ($harga * $value->discount / 100);
                $discount = '<span><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></span>';
            } else {
                $total = '<dd style=""><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></dd>';
                $discount = '';
            }

            $html = '<div class="col-4">
            <div class="card">
        <div class="card-body" style="background-color: #F2F2F280">
            <div class="row">
                <div class="col-md-4">
                    <img src="' . asset('img/no-pict.png') . '" alt="" width="100%" height="100px" class="rounded">
                </div>
                <div class="col-md-8">
                    <dl>
                        <dd>' . $value->name . '</dd>
                        <dd><small>' . $value->composition . '</small></dd>

                    </dl>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        ' . $total . '
                        <dd style="margin-bottom: 0px"><span>' . format_rupiah($value->qty) . '</span></dd>
                        <dd style="margin-bottom: 0px">' . $stock . '</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    ' . $discount . '
                    <div class="input-group" style="margin-top: 0.5rem">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-xs btn-danger btn-number-' . $value->id . '" onclick="minusItem(' . $value->id . ')" data-type="minus" data-field="quant[2]">
                                <i class="fas fa-minus"></i>
                            </button>
                        </span>
                        <input type="text" name="quant[2]" class="form-control input-number-' . $value->id . '" value="0"
                            min="0" max="' . $value->qty . '" style="background-color: #F2F2F280;border: 0px;text-align:center">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-xs btn-soraba btn-number-' . $value->id . '" onclick="plusItem(' . $value->id . ')" data-type="plus" data-field="quant[2]">
                                <i class="fas fa-plus"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <button class="btn btn-block btn-inventory" onclick="pesan(' . $value->id . ',1)">Tambahkan ke Pesanan</button>
        </div>
    </div>
    </div>';

            $htmls[$key] = $html;
        }


        return $htmls;
    }

    function cari(Request $request)
    {
        $param = $request->cari;
        $card = $this->cardItem($param);


        $content = returnJson(true, $card);
        $status = 200;

        return (new Response($content, $status))
            ->header('Content-Type', 'json');
    }

    function addToCart(Request $request)
    {
        $time = strtotime("now");

        if ($request->qty == 0) {
            $data = [
                'message' => 'Kuantitas Tidak Boleh 0',
            ];

            $content = returnJson(false, $data);
            $status = 200;

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        }

        if ($request->tipe == 2) {

            $harga = 0;
            $totPajak = 0;
            $string = '';
            $disc = 0;
            $cost = str_replace('.', '', $request->cost);
            foreach ($request->item_id as $key => $value) {

                $item = Item::find($value);
                if ($item->qty < $request->qty_item[$key]) {
                    $data = [
                        'message' => 'Sisa stok ' . $item->name . ' adalah ' . $item->qty,
                    ];

                    $content = returnJson(false, $data);
                    $status = 200;

                    return (new Response($content, $status))
                        ->header('Content-Type', 'json');
                }
                $discount = is_null($item->discount) ? 0 : (($item->sale_price * $request->qty_item[$key])) * $item->discount / 100;
                // $sebelum_pajak = round(reverse_tax($item->sale_price * $request->qty_item[$key]));
                $subprice = $item->sale_price * $request->qty_item[$key];

                $item_price = $item->sale_price;
                // $item_pajak = $item->sale_price - $item_price;

                $string .= '<input type="hidden" name="item_id[' . $time . '][' . $key . ']" value="' . $item->id . '">';
                $string .= '<input type="hidden" name="item_qty[' . $time . '][' . $key . ']" value="' . $request->qty_item[$key] * $request->qty. '">';
                // $string .= '<input type="hidden" name="item_pajak[' . $time . '][' . $key . ']" value="' . $item_pajak . '">';
                $string .= '<input type="hidden" name="item_price[' . $time . '][' . $key . ']" value="' . $item_price . '">';
                $string .= '<input type="hidden" name="item_discount[' . $time . '][' . $key . ']" value="' . $discount . '">';

                $harga += $subprice;
                $disc += $discount;
            }
            // $price = $harga / $request->qty;
            $sub = $harga;
            // $totPajak = round(pajak($sub + $cost));
            $totPajak = 0;

            $harga = $request->qty * $cost;//$sub  - $disc + $request->cost;
            $price = ($sub + $request->cost) / $request->qty;

            $html = '<div class="row item-detail" id="detail-' . $time . '">
                        <div class="col-2">
                            <img src="' . asset('img/no-pict.png') . '" width="100%" height="84px" class="rounded">
                        </div>
                        <div class="col-10">
                            <dl>
                                <dd style="margin-bottom: 0px">' . strtoupper($request->item_name) . '</dd>
                                <input type="hidden" name="index[]" value="' . $time . '">
                                <input type="hidden" name="item_name[]" value="' . $request->item_name . '">
                                ' . $string . '`
                                <input type="hidden" name="type[]" value="' . $request->tipe . '">
                                <dd style="margin-bottom: 0px;color:#626E73"><strong>x' . $request->qty . '</strong></dd>
                                <input type="hidden" name="qty[]" value="' . $request->qty . '">

                                <dd style="margin-bottom: 0px">
                                    <div class="row">
                                        <div class="col-5">
                                            <textarea class="form-control" rows="1" name="notes[]" placeholder="Catatan" style="min-width: 100%"></textarea>

                                        </div>
                                        <div class="col-7">
                                            <strong class="float-right" style="margin-right: 2rem">
                                            Rp. ' . number_format($harga, '0', ',', '.') . '
                                                <input type="hidden" name="price[]" id="price_' . $time . '" value="' . str_replace('.', '', $harga) . '">
                                                    <input type="hidden" name="pajak[]" id="pajak_' . $time . '" value="' . $totPajak . '">
                                                    <input type="hidden" name="discount[]" value="' . $disc . '">
                                                    <input type="hidden" name="cost[]" id="cost_' . $time . '" value="' . str_replace('.', '', $request->cost) . '">
                                                    <input type="hidden" name="sub_price[]" id="sub_price_' . $time . '" value="' . $cost . '">
                                                <button type="button" class="btn btn-xs btn-danger" onclick="hapusOrder(this,' . $time . ')">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </strong>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>';
            // $harga -=  $disc;
        } else {
            $item = Item::find($request->item_id);
            $sub = $item->sale_price;
            // $pajak = $item->sale_price - $sub;
            $harga = $item->sale_price * $request->qty;

            $discount = is_null($item->discount) ? 0 : ($harga) * $item->discount / 100;
            $cost = 0;

            $html = '<div class="row item-detail" id="detail-' . $time . '">
            <div class="col-2">
                <img src="' . asset('img/no-pict.png') . '" width="100%" height="84px" class="rounded">
            </div>
            <div class="col-10">
                <dl>
                    <dd style="margin-bottom: 0px">' . strtoupper($item->name) . '</dd>
                <input type="hidden" name="item_name[]" value="' . $item->name . '">
                <input type="hidden" name="index[]" value="' . $time . '">
                    <input type="hidden" name="item_id[' . $time . '][0]" value="' . $item->id . '">
                    <dd style="margin-bottom: 0px;color:#626E73"><strong>x' . $request->qty . '</strong></dd>
                    <input type="hidden" name="item_qty[' . $time . '][0]" value="' . $request->qty . '">
                    <input type="hidden" name="item_price[' . $time . '][0]" value="' . $sub . '">
                    <input type="hidden" name="item_discount[' . $time . '][0]" value="' . $discount . '">
                    <input type="hidden" name="discount[]" value="' . $discount . '">
                    <input type="hidden" name="qty[]" value="' . $request->qty . '">
                    <dd style="margin-bottom: 0px">
                        <div class="row">
                            <div class="col-5">
                                <textarea class="form-control" rows="1" name="notes[]" placeholder="Catatan" style="min-width: 100%"></textarea>

                            </div>
                            <div class="col-7">
                                <strong class="float-right" style="margin-right: 2rem">
                                Rp. ' . number_format($harga  - $discount, '0', ',', '.') . '
                                    <input type="hidden" name="price[]" id="price_' . $time . '" value="' . $harga - $discount . '">
                                    <input type="hidden" name="cost[]" id="cost_' . $time . '"  value="' . $cost . '">
                                    <input type="hidden" name="sub_price[]" id="sub_price_' . $time . '" value="' . $sub . '">
                                    <button type="button" class="btn btn-xs btn-danger" onclick="hapusOrder(this,' . $time . ')">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </strong>
                            </div>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>';
            $harga -=  $discount;
        }

        $item->harga = $harga;
        $item->cost = $cost;

        $data = [
            'html' => $html,
            'item' => $item,
        ];

        $content = returnJson(true, $data);
        $status = 200;

        return (new Response($content, $status))
            ->header('Content-Type', 'json');
    }

    public function datatable(Request $request)
    {
        $query = Transaction::orderBy('created_at', 'desc');
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                // $string .= '<a href="' . route('invoice.show', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Item"><i class="fas fa-receipt"></i></a>';
                // $string .= '&nbsp;&nbsp;<a href="' . route('invoice.download', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-success" title="Download"><i class="fa fa-download"></i></a>';
                $string .= '<a href="' . route('pos.show', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-inventory" title="Lihat Item">Lihat Pesanan</i></a>';

                $string .= '</div>';
                return
                    $string;
            })
            ->addColumn('payment_type', function ($model) {
                return $model->payment_type_id ? $model->paymentType->name : '';
            })
            ->addColumn('jumlah_item', function ($model) {
                return count($model->details);
            })
            ->addColumn('status', function ($model) {
                switch ($model->status_id) {
                    case 1:
                        $status = '<span class="badge bg-info">' . $model->status->name . '</span>';
                        break;
                    case 2:
                        $status = '<span class="badge bg-success">' . $model->status->name . '</span>';
                        break;
                    case 3:
                        $status = '<span class="badge bg-danger">' . $model->status->name . '</span>';
                        break;
                    default:
                        $status = '<span class="badge bg-secondary"></span>';
                        break;
                }
                return $status;
            })
            ->editColumn('total', function ($model) {
                return format_rupiah($model->total);
            })
            ->editColumn('tanggal', function ($model) {
                return sqlindo_datetime_to_datetime($model->created_at);
            })
            ->editColumn('waktu_pembayaran', function ($model) {
                return $model->status_id == 2 ? sqlindo_datetime_to_datetime($model->updated_at) : '';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function batal(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = Transaction::find(base64_decode($id));
            $model->status_id = 3;
            $model->save();

            foreach ($model->details as $keys => $items) {
                $qty = json_decode($items->qty_item);
                foreach (json_decode($items->item_id) as $key => $value) {

                    $item = Item::find($value);
                    $item->qty = $item->qty + $qty[$key];
                    $item->save();
                }
            }

            DB::commit();
            $data = [
                'url' => route('pos.show', ['id' => base64_encode($model->id)]),
            ];

            $content = returnJson(true, $data);
            $status = 200;

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        } catch (Exception $e) {
            DB::rollBack();

            $data = [
                'error' => json_encode($e),
            ];
            $content = returnJson(false, $data);
            $status = 200;

            Log::error($content);

            return (new Response($content, $status))
                ->header('Content-Type', 'json');
        }
    }

    function invoice($id)
    {
        $id = base64_decode($id);
        $data['transaction'] = Transaction::find($id);
        $data['title'] =  'invoice '.$data['transaction']->invoice_no;
        return view('pos.invoice', $data);
    }

    public function struk($id)
    {
        $id = base64_decode($id);
        $data['transaction'] = Transaction::find($id);
        // $pdf = PDF::loadview('invoice.receipt', $data);
        // return $pdf->download('receipt ' . $data['transaction']->invoice_no . ' .pdf');

        return view('invoice.receipt', $data);
    }
}
