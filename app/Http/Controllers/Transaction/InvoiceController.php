<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Item;
use App\Models\Master\Parameter;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response as res;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        return view('invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Transaction();
        $invoice_no = $this->getNumber();
        return view('invoice.form', ['model' => $model, 'invoice_no' => $invoice_no]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'payment_type' => 'required',
            'item_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = new Transaction();
            $model->payment_type_id = $request->payment_type;
            $model->status_id = 2;
            $model->invoice_no = $this->getNumber();
            $model->date = $request->date;
            $model->customer_name = $request->customer_name;
            $model->customer_phone = $request->customer_phone;
            $model->customer_address = $request->customer_address;

            $model->sub_total = str_replace('.', '', $request->subTotal);
            // $model->pajak = str_replace('.', '', $request->pajak);
            $model->biaya_pengiriman = str_replace('.', '', $request->biayaPengiriman);
            $model->discount = str_replace('.', '', $request->discountTotal);
            $model->total = str_replace('.', '', $request->total);
            $model->save();
            $index = $request->index;
            foreach ($index as $key => $value) {
                $dtl = new TransactionDetail();
                $dtl->transaction_id = $model->id;
                $dtl->item_id = json_encode($request->item_id[$value]);
                $dtl->qty_item = json_encode($request->item_qty[$value]);
                $dtl->qty = $request->qty[$key];
                $dtl->item_name = $request->item_name[$key];
                $dtl->discount = $request->discount[$key];
                $dtl->price = $request->price[$key];
                $dtl->total = $request->totalItem[$key];

                foreach ($request->item_id[$value] as $k => $val) {
                    $item = Item::find($val);
                    $item->qty = $item->qty - $request->item_qty[$value][$k];
                    $item->save();
                }

                $dtl->save();
            }

            DB::commit();
            return redirect()->route('invoice.index')->with('alert.success', 'Invoice Has Been Created');
        } catch (Exception $e) {
            DB::rollBack();
            print($e);
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
        $data['transaction'] = Transaction::find($id);
        return view('invoice.show', $data);
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
        //
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

    public function download($id)
    {
        $id = base64_decode($id);
        $data['transaction'] = Transaction::find($id);
        $pdf = PDF::loadview('invoice.invoice', $data);
        return $pdf->download('invoice ' . $data['transaction']->invoice_no . ' .pdf');
    }

    public function receipt($id)
    {
        $id = base64_decode($id);
        $data['transaction'] = Transaction::find($id);
        $pdf = PDF::loadview('invoice.receipt', $data);
        return $pdf->download('receipt ' . $data['transaction']->invoice_no . ' .pdf');
    }

    public function datatable(Request $request)
    {
        $query = Transaction::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('invoice.show', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Detail"><i class="fas fa-receipt"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('invoice.download', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-success" title="Download"><i class="fa fa-download"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('invoice.receipt', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-primary" title="Print Receipt"><i class="fa fa-print"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->addColumn('payment_type', function ($model) {
                return $model->payment_type_id ? $model->paymentType->name : '';
            })
            ->editColumn('total', function ($model) {
                return format_rupiah($model->total);
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
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function addItem(Request $request)
    {
        $string = '';
        $qty = '';
        $price = '';
        $discount = '';
        $total = '';
        if (count($request->item_id) > 1) { //dispensing
            $harga = 0;
            $string .= '<span>' . $request->item_name . '</span>';
            $string .= '<input type="hidden" name="item_name[]" value="' . $request->item_name . '">';
            $string .= '<input type="hidden" name="index[]" value="' . time() . '">';
            $string .= '<ul>';
            foreach ($request->item_id as $key => $value) {
                $item = Item::find($value);
                if ($item->qty < $request->qty_item[$key]) {
                    $data['success'] = false;
                    $data['message'] = 'Remaining Stock ' . $item->name . ' is ' . $item->qty;
                    $json = res::json($data);
                    return $json;
                }

                $string .= '<li>' . $item->name;
                $string .= '<input type="hidden" name="item_id[' . time() . '][' . $key . ']" value="' . $item->id . '">';
                $string .= '<input type="hidden" name="item_qty[' . time() . '][' . $key . ']" value="' . $request->qty_item[$key] . '">';
                $string .= '</li>';
                $harga += ($item->sale_price * $request->qty_item[$key]);
            }
            $param = Parameter::where('code', 'dispensing')->first();

            $harga += $param->value;
            $string .= '<ul>';
            $kuantitas = $request->qty;
            $disc = ($harga * $request->discount_formula) / 100;

            $discount .= "<span>" . format_rupiah($disc) . "</span>";
            $discount .= '<input type="hidden" name="discount[]" value="' . $request->discount_formula . '">';

            $harga_satuan = $harga / $kuantitas;
            $price .= "<span>" . format_rupiah($harga_satuan) . "</span>";
            $price .= '<input type="hidden" name="price[]" value="' . $harga_satuan . '">';
        } else { //regular
            $item = Item::find($request->item_id[0]);
            if ($item->qty < $request->qty_item[0]) {
                $data['success'] = false;
                $data['message'] = 'Remaining Stock ' . $item->name . ' is ' . $item->qty;
                $json = res::json($data);
                return $json;
            }
            $string .= '<span>' . $item->name . '</span>';
            $string .= '<input type="hidden" name="index[]" value="' . time() . '">';
            $string .= '<input type="hidden" name="item_name[]" value="' . $item->name . '">';
            $string .= '<input type="hidden" name="item_id[' . time() . '][]" value="' . $item->id . '">';
            $string .= '<input type="hidden" name="item_qty[' . time() . '][]" value="' . $request->qty_item[0] . '">';

            $harga = $item->sale_price;
            $kuantitas = $request->qty_item[0];
            $disc = (($harga * $kuantitas) * $request->discount) / 100;
            $harga = $harga * $kuantitas;

            $discount .= "<span>" . format_rupiah($disc) . "</span>";
            $discount .= '<input type="hidden" name="discount[]" value="' . $disc . '">';

            $price .= "<span>" . format_rupiah($item->sale_price) . "</span>";
            $price .= '<input type="hidden" name="price[]" value="' . $item->sale_price . '">';
        }

        $qty .= "<span>" . $kuantitas . "</span>";
        $qty .= '<input type="hidden" name="qty[]" value="' . $kuantitas . '">';

        $tot = $harga - $disc;
        $total .= "<span style='text-align:right' class='float-right'>" . format_rupiah($tot)  . "</span>";
        $total .= '<input type="hidden" name="totalItem[]" id="totalItem" value="' . $tot . '">';
        $id = time();
        $data['row'] = '<tr id="' . $id . '">
                    <td>' . $string . '</td>
                    <td>' . $qty . '</td>
                    <td>' . $price . '</td>
                    <td>' . $discount . '</td>
                    <td>' . $total . '</td>
                    <td><button type="button" class="btn btn-xs btn-danger" onclick="deleteRow(this, ' . $id . ',' . $tot . ')"><i class="fas fa-trash" ></i></button></td>
                </tr>';
        $data['success'] = true;
        $data['subTotal'] = $tot;
        $json = res::json($data);


        return $json;
    }

    private function getNumber()
    {
        $month = date('m');
        $year = date('Y');

        $trx = Transaction::orderByDesc('id')->whereMonth('created_at', Carbon::now()->month)->first();

        if ($trx) {
            $lastTrx = $trx->invoice_no;
            $no = explode("/", $lastTrx);
            $no = $no[1];
        } else {
            $lastTrx =  "INV/0000/" . $month . "/NATHA/HF-PIK/" . $year;
            $no = "0000";
        }


        $lastNumber = "INV/" . sprintf('%04d', $no + 1) . "/" . getRomawi($month) . "/NATHA/HF-PIK/" . $year;

        return $lastNumber;
    }
}
