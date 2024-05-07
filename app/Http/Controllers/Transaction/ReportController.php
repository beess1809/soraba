<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\Item;
use App\Models\Transaction\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Exception;
use PDF;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('report.index');
    }
    public function detail()
    {
        return view('report.detail');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function transaction(Request $request)
    {
        $tanggal = explode(' sd ', $request->tanggal);
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $query = Transaction::where('status_id', 2)->whereBetween('date', [$start, $end]);
        return DataTables::of($query)
            ->addColumn('payment_type', function ($model) {
                return $model->payment_type_id ? $model->paymentType->name : '';
            })
            ->editColumn('total', function ($model) {
                return format_rupiah($model->total);
            })
            ->editColumn('subtotal', function ($model) {
                return format_rupiah($model->sub_total - $model->cost);
            })
            ->editColumn('sub_total', function ($model) {
                return format_rupiah($model->sub_total);
            })
            ->editColumn('pajak', function ($model) {
                return format_rupiah($model->pajak);
            })
            ->editColumn('discount', function ($model) {
                return format_rupiah($model->discount);
            })
            ->editColumn('grand_total', function ($model) {
                return format_rupiah($model->grand_total);
            })
            ->addColumn('waktu_pembayaran', function ($model) {
                return $model->status_id == 2 ? sqlindo_datetime_to_datetime($model->updated_at) : '';
            })
            ->editColumn('waktu_pemesanan', function ($model) {
                return sqlindo_datetime_to_datetime($model->created_at);
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
            ->addColumn('cost', function ($model) {
                return format_rupiah($model->cost);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function exportTransaction($tanggal)
    {
        $tanggal = explode(' sd ', urldecode($tanggal));
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $data = Transaction::where('status_id', 2)->whereBetween('date', [$start, $end])->get();

        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Invoice');
        $sheet->setCellValue('C1', 'Nama Pelanggan');
        $sheet->setCellValue('D1', 'Tanggal Pemesanan');
        $sheet->setCellValue('E1', 'Tipe Pembayaran');
        $sheet->setCellValue('F1', 'Tanggal Pembayaran');
        $sheet->setCellValue('G1', 'Subtotal Item');
        $sheet->setCellValue('H1', 'Biaya Embalase');
        $sheet->setCellValue('I1', 'Subtotal');
        $sheet->setCellValue('J1', 'Pajak');
        $sheet->setCellValue('K1', 'Total');
        $sheet->setCellValue('L1', 'Diskon');
        $sheet->setCellValue('M1', 'Grand Total');
        $no = 2;
        foreach ($data as $key => $item) {

            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $item->invoice_no);
            $sheet->setCellValue('C' . $no, $item->customer_name);
            $sheet->setCellValue('D' . $no, sqlindo_datetime_to_datetime($item->created_at));
            $sheet->setCellValue('E' . $no, $item->paymentType->name);
            $sheet->setCellValue('F' . $no, sqlindo_datetime_to_datetime($item->updated_at));
            $sheet->setCellValue('G' . $no, $item->sub_total);
            $sheet->getStyle('G' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $no, $item->cost);
            $sheet->getStyle('H' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('I' . $no, $item->sub_total);
            $sheet->getStyle('I' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('J' . $no, $item->pajak);
            $sheet->getStyle('J' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('K' . $no, $item->total);
            $sheet->getStyle('K' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('L' . $no, $item->discount);
            $sheet->getStyle('L' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('M' . $no, $item->grand_total);
            $sheet->getStyle('M' . $no)->getNumberFormat()->setFormatCode('#,##0');

            $no++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Transaksi ' . $tanggal[0] . ' - ' . $tanggal[1] . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function detailTable(Request $request)
    {
        $tanggal = explode(' sd ', $request->tanggal);
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $query = Transaction::join('transaction_details as td', 'transactions.id', '=', 'td.transaction_id')->where('status_id', 2)->whereBetween('date', [$start, $end])->get();

        $details = [];

        foreach ($query as $key => $value) {
            $items = json_decode($value->item_id);
            $qty = json_decode($value->qty_item);
            $pajak = json_decode($value->item_pajak);
            $price = json_decode($value->item_price);
            $discount = json_decode($value->item_discount);

            foreach ($items as $idx => $item) {

                $dtl = Item::withTrashed()->find($item); //->where('id', $item)->get();
                // dd($dtl);
                $details[] = array(
                    'no_invoice' => $value->invoice_no,
                    'name' => $dtl->name,
                    'qty' => $qty[$idx],
                    'price' => $price[$idx] + $pajak[$idx],
                    'subtotal' => $price[$idx],
                    'pajak' => $pajak[$idx],
                    'discount' => $discount[$idx],
                    'total' => (($price[$idx] + $pajak[$idx] - $discount[$idx]))
                );
            }
        }

        return DataTables::of($details)
            ->editColumn('price', function ($model) {
                return format_rupiah($model['price']);
            })
            ->editColumn('total', function ($model) {
                return format_rupiah($model['total']);
            })
            ->editColumn('subtotal', function ($model) {
                return format_rupiah($model['subtotal']);
            })
            ->editColumn('pajak', function ($model) {
                return format_rupiah($model['pajak']);
            })
            ->editColumn('discount', function ($model) {
                return format_rupiah($model['discount']);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function exportDetail($tanggal)
    {
        $tanggal = explode(' sd ', $tanggal);
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $query = Transaction::join('transaction_details as td', 'transactions.id', '=', 'td.transaction_id')->where('status_id', 2)->whereBetween('date', [$start, $end])->get();

        $details = [];

        foreach ($query as $key => $value) {
            $items = json_decode($value->item_id);
            $qty = json_decode($value->qty_item);
            $pajak = json_decode($value->item_pajak);
            $price = json_decode($value->item_price);
            $discount = json_decode($value->item_discount);

            foreach ($items as $idx => $item) {
                $dtl = Item::withTrashed()->find($item); //->where('id', $item)->get();

                $details[] = array(
                    'no_invoice' => $value->invoice_no,
                    'name' => $dtl->name,
                    'qty' => $qty[$idx],
                    'price' => $price[$idx] + $pajak[$idx],
                    'subtotal' => $price[$idx],
                    'pajak' => $pajak[$idx],
                    'discount' => $discount[$idx],
                    'total' => (($price[$idx] + $pajak[$idx] - $discount[$idx]))
                );
            }
        }

        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Invoice');
        $sheet->setCellValue('C1', 'Nama Item');
        $sheet->setCellValue('D1', 'Kuantitas');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Subtotal');
        $sheet->setCellValue('G1', 'Pajak');
        $sheet->setCellValue('H1', 'Diskon');
        $sheet->setCellValue('I1', 'Total');
        $no = 2;
        foreach ($details as $key => $item) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $item['no_invoice']);
            $sheet->setCellValue('C' . $no, $item['name']);
            $sheet->setCellValue('D' . $no, $item['qty']);
            $sheet->setCellValue('E' . $no, $item['price']);
            $sheet->getStyle('E' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('F' . $no, $item['subtotal']);
            $sheet->getStyle('F' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('G' . $no, $item['pajak']);
            $sheet->getStyle('G' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $no, $item['discount']);
            $sheet->getStyle('H' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('I' . $no, $item['total']);
            $sheet->getStyle('I' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $no++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Detail Transaksi ' . $tanggal[0] . ' - ' . $tanggal[1] . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function printTransaction(Request $request)
    {
        $tanggal = $request->date;
        $tanggal = explode(' sd ', urldecode($tanggal));
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $data['transaction'] = Transaction::where('status_id', 2)->whereBetween('date', [$start, $end])->get();

        // return response()->json($data);
        $pdf = PDF::loadView('report.transaction', $data)->setPaper('a4', 'landscape');


        $pdfPath = public_path('file/' . uniqid() . '.pdf');
        $pdf->save($pdfPath);

        $publicUrl = asset(str_replace(public_path(), '', $pdfPath));

        return response()->json(['pdfPath' => $publicUrl, 'fileName' => 'Daftar Transaksi ' . $tanggal[0] . ' - ' . $tanggal[1]]);
        // return $pdf->download('transaction ' . ' .pdf');
    }

    public function printDetailTransaction(Request $request)
    {
        $tanggal = $request->date;
        $tanggal = explode(' sd ', urldecode($tanggal));
        $start = tanggalDb($tanggal[0]);
        $end = tanggalDb($tanggal[1]);

        $query = Transaction::join('transaction_details as td', 'transactions.id', '=', 'td.transaction_id')->where('status_id', 2)->whereBetween('date', [$start, $end])->get();

        $details = [];

        foreach ($query as $key => $value) {
            $items = json_decode($value->item_id);
            $qty = json_decode($value->qty_item);
            $pajak = json_decode($value->item_pajak);
            $price = json_decode($value->item_price);
            $discount = json_decode($value->item_discount);

            foreach ($items as $idx => $item) {
                $dtl = Item::withTrashed()->find($item); //->where('id', $item)->get();

                $details[] = array(
                    'no_invoice' => $value->invoice_no,
                    'name' => $dtl->name,
                    'qty' => $qty[$idx],
                    'price' => $price[$idx] + $pajak[$idx],
                    'subtotal' => $price[$idx],
                    'pajak' => $pajak[$idx],
                    'discount' => $discount[$idx],
                    'total' => (($price[$idx] + $pajak[$idx] - $discount[$idx]) * $qty[$idx])
                );
            }
        }

        $data['details'] = $details;

        // return response()->json($data);
        $pdf = PDF::loadView('report.detail_transaction', $data)->setPaper('a4', 'landscape');


        $pdfPath = public_path('file/' . uniqid() . '.pdf');
        $pdf->save($pdfPath);

        $publicUrl = asset(str_replace(public_path(), '', $pdfPath));

        return response()->json(['pdfPath' => $publicUrl, 'fileName' => 'Daftar Detail Transaksi ' . $tanggal[0] . ' - ' . $tanggal[1]]);
        // return $pdf->download('transaction ' . ' .pdf');
    }
}
