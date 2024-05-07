<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Category;
use App\Models\Master\Item;
use App\Models\Master\Uom;
use App\Models\Master\Vendor;
use App\Models\Master\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Response as res;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Item();
        return view('item.form', ['model' => $model]);
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
            'name' => 'required',
            'qty' => 'required',
            'uom' => 'required',
            'warehouse_id' => 'required',
            'category_id' => 'required',
            'warehouse_id' => 'required',
            'sale_price' => 'required',
        ]);

        $model = new Item();
        $model->name = strtoupper($request->name);
        $model->composition = $request->composition;
        $model->warehouse_id = $request->warehouse_id;
        $model->vendor_id = $request->vendor_id;
        $model->category_id = $request->category_id;
        $model->qty = str_replace('.', '', $request->qty);
        $model->uom_id = $request->uom;
        $model->sale_price = str_replace('.', '', $request->sale_price);
        $model->discount = $request->discount;
        $model->expired_discount = $request->expired_date;

        if ($model->save()) {
            return redirect()->route('items.index')->with('alert.success', 'Item Has Been Added');
        } else {
            return redirect()->route('items.create')->with('alert.failed', 'Something Wrong');
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
        $model = Item::find($id);
        return res::json($model);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        $model = Item::find($id);
        return view('item.form', ['model' => $model]);
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
        $request->validate([
            'name' => 'required',
            'qty' => 'required',
            'uom' => 'required',
            'warehouse_id' => 'required',
            'category_id' => 'required',
            'warehouse_id' => 'required',
            'sale_price' => 'required',
        ]);

        $id = base64_decode($id);

        $model = Item::find($id);
        $model->name = strtoupper($request->name);
        $model->composition = $request->composition;
        $model->warehouse_id = $request->warehouse_id;
        $model->vendor_id = $request->vendor_id;
        $model->category_id = $request->category_id;
        $model->qty = str_replace('.', '', $request->qty);
        $model->uom_id = $request->uom;
        $model->sale_price = str_replace('.', '', $request->sale_price);
        $model->discount = $request->discount;
        $model->expired_discount = $request->expired_date;

        if ($model->save()) {
            return redirect()->route('items.index')->with('alert.success', 'Item Has Been Updated');
        } else {
            return redirect()->route('items.create')->with('alert.failed', 'Something Wrong');
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
        $id = base64_decode($id);
        $model = Item::find($id);
        $model->delete();
    }

    public function data(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            $datas = Item::all();
        } else {
            $datas = Item::where('name', 'like', '%' . $term . '%')->get();
        }
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name . '(' . $data->qty . ' ' . $data->uom->name . ')'];
        }
        return res::json($res);
    }

    public function datatable(Request $request)
    {
        $query = Item::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('items.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Item"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('items.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->addColumn('uom', function ($model) {
                return $model->uom_id ? $model->uom->name : "";
            })
            ->addColumn('warehouse', function ($model) {
                return $model->warehouse_id ? $model->warehouse->name : "";
            })
            ->addColumn('vendor', function ($model) {
                return $model->vendor_id ? $model->vendor->name : "";
            })
            ->addColumn('category', function ($model) {
                return $model->category_id ? $model->category->name : "";
            })
            ->editColumn('qty', function ($model) {
                return format_rupiah($model->qty);
            })
            ->editColumn('sale_price', function ($model) {
                return  format_rupiah($model->sale_price);
            })
            ->editColumn('discount', function ($model) {
                return  $model->discount . '%';
            })
            ->editColumn('expired_date', function ($model) {
                if ($model->expired_discount) {
                    return  date('d-m-Y', strtotime($model->expired_discount));
                } else {
                    return '';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function formUpload()
    {
        return view('item.upload');
    }

    public function formatExcel()
    {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'No');
        $activeWorksheet->setCellValue('B1', 'Name');
        $activeWorksheet->setCellValue('C1', 'Category');
        $activeWorksheet->setCellValue('D1', 'Composition');
        $activeWorksheet->setCellValue('E1', 'Quantity');
        $activeWorksheet->setCellValue('F1', 'Uom');
        $activeWorksheet->setCellValue('G1', 'Vendor');
        $activeWorksheet->setCellValue('H1', 'Warehouse');
        $activeWorksheet->setCellValue('I1', 'Sell Price');

        $sheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Categories');
        $spreadsheet->addSheet($sheet1, 1);
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Categories');
        $categories = Category::all();
        $idx = 2;

        foreach ($categories as $key => $value) {
            $sheet1->setCellValue('A' . $idx, $value->id);
            $sheet1->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Warehouse');
        $spreadsheet->addSheet($sheet2, 2);
        $sheet2 = $spreadsheet->getSheet(2);
        $sheet2->setCellValue('A1', 'ID');
        $sheet2->setCellValue('B1', 'Warehouse');
        $warehouses = Warehouse::all();
        $idx = 2;

        foreach ($warehouses as $key => $value) {
            $sheet2->setCellValue('A' . $idx, $value->id);
            $sheet2->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Vendor');
        $spreadsheet->addSheet($sheet3, 3);
        $sheet3 = $spreadsheet->getSheet(3);
        $sheet3->setCellValue('A1', 'ID');
        $sheet3->setCellValue('B1', 'Vendor');
        $vendor = Vendor::all();
        $idx = 2;

        foreach ($vendor as $key => $value) {
            $sheet3->setCellValue('A' . $idx, $value->id);
            $sheet3->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Uoms');
        $spreadsheet->addSheet($sheet4, 4);
        $sheet4 = $spreadsheet->getSheet(4);
        $sheet4->setCellValue('A1', 'ID');
        $sheet4->setCellValue('B1', 'Code');
        $sheet4->setCellValue('C1', 'Name');
        $uom = Uom::all();
        $idx = 2;

        foreach ($uom as $key => $value) {
            $sheet4->setCellValue('A' . $idx, $value->id);
            $sheet4->setCellValue('B' . $idx, $value->code);
            $sheet4->setCellValue('C' . $idx, $value->name);
            $idx++;
        }


        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Upload Items.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file_upload');
        $nama_file = rand() . '_' . $file->getClientOriginalName();
        $file->move('file/upload_item/', $nama_file);
        $inputFileName = public_path('/file/upload_item/' . $nama_file);


        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();
        $dataTemp = [];
        for ($row = 2; $row <= $lastRow; $row++) {
            $temp = [];
            $temp['no'] = $worksheet->getCell('A' . $row)->getValue();
            $temp['name'] = $worksheet->getCell('B' . $row)->getFormattedValue();
            $temp['category'] = $worksheet->getCell('C' . $row)->getValue();
            $temp['composition'] = $worksheet->getCell('D' . $row)->getValue();
            $temp['quantity'] = $worksheet->getCell('E' . $row)->getValue();
            $temp['uom'] = $worksheet->getCell('F' . $row)->getValue();
            $temp['vendor'] = $worksheet->getCell('G' . $row)->getValue();
            $temp['warehouse'] = $worksheet->getCell('H' . $row)->getValue();
            $temp['sell_price'] = $worksheet->getCell('I' . $row)->getValue();
            $dataTemp[] = $temp;
        }
        unlink(public_path('/file/upload_item/' . $nama_file));

        foreach ($dataTemp as $key => $value) {
            $model = new Item();
            $model->name = strtoupper($value['name']);
            $model->category_id = $value['category'];
            $model->warehouse_id = $value['warehouse'];
            $model->vendor_id = $value['vendor'];
            $model->uom_id = $value['uom'];
            $model->composition = $value['composition'];
            $model->qty = $value['quantity'];
            $model->sale_price = $value['sell_price'];
            $model->save();
        }

        $res = ['res' => 'success'];
        return $res;
    }

    public function getItemByCategory(Request $request)
    {
        // $id = base64_decode($id);
        $id = $request->cat_id;
        if ($id) {
            $model = Item::where('category_id', $id)->get();
        } else {
            $model = Item::all();
        }
        // return res::json($model);

        //     if (!is_null($param)) {
        //         $items = Item::where('name', 'like', '%' . $param . '%')->get();
        //     } else {
        //         $items = Item::all();
        //     }

        //     foreach ($items as $key => $value) {
        //         $stock = ($value->qty == 0) ? '<small class="text-red">Out of Stock !</small>' : '<small>In Stock</small>';
        //         $pajak = pajak($value->sale_price);
        //         $harga = $value->sale_price + $pajak;

        //         if (!is_null($value->discount)) {
        //             $total = '<dd style="color:grey"><strong><s>Rp. ' . number_format($harga, 0, ',', '.') . '</s></strong></dd>';
        //             $harga = $harga - ($harga * $value->discount / 100);
        //             $discount = '<span><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></span>';
        //         } else {
        //             $total = '<dd style=""><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></dd>';
        //             $discount = '';
        //         }

        //         $html = '<div class="col-4">
        //         <div class="card">
        //     <div class="card-body" style="background-color: #F2F2F280">
        //         <div class="row">
        //             <div class="col-md-4">
        //                 <img src="' . asset('img/no-pict.png') . '" alt="" width="100%" height="100px" class="rounded">
        //             </div>
        //             <div class="col-md-8">
        //                 <dl>
        //                     <dd>' . $value->name . '</dd>
        //                     <dd><small>' . $value->composition . '</small></dd>

        //                 </dl>
        //             </div>
        //         </div>
        //         <br>
        //         <div class="row">
        //             <div class="col-md-6">
        //                 <dl>
        //                     ' . $total . '
        //                     <dd style="margin-bottom: 0px"><span>' . $value->qty . '</span></dd>
        //                     <dd style="margin-bottom: 0px">' . $stock . '</dd>
        //                 </dl>
        //             </div>
        //             <div class="col-md-6">
        //                 ' . $discount . '
        //                 <div class="input-group" style="margin-top: 0.5rem">
        //                     <span class="input-group-btn">
        //                         <button type="button" class="btn btn-xs btn-number-' . $value->id . '" onclick="minusItem(' . $value->id . ')" data-type="minus" data-field="quant[2]">
        //                             <img src="' . asset('img/icon/icon-minus.svg') . '" alt="">
        //                         </button>
        //                     </span>
        //                     <input type="text" name="quant[2]" class="form-control input-number-' . $value->id . '" value="0"
        //                         min="0" max="' . $value->qty . '" style="background-color: #F2F2F280;border: 0px;text-align:center">
        //                     <span class="input-group-btn">
        //                         <button type="button" class="btn btn-xs btn-number-' . $value->id . '" onclick="plusItem(' . $value->id . ')" data-type="plus" data-field="quant[2]">
        //                             <img src="' . asset('img/icon/icon-plus.svg') . '" alt="">
        //                         </button>
        //                     </span>
        //                 </div>
        //             </div>
        //         </div>
        //         <button class="btn btn-block btn-inventory" onclick="pesan(' . $value->id . ',1)">Tambahkan ke Pesanan</button>
        //     </div>
        // </div>
        // </div>';

        //         $htmls[$key] = $html;
        //     }


        //     return $htmls;

        if ($model->count() > 0) {
            foreach ($model as $key => $value) {
                $stock = ($value->qty == 0) ? '<small class="text-red">Out of Stock !</small>' : '<small>In Stock</small>';
                $pajak = pajak($value->sale_price);
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
                                                <dd style="margin-bottom: 0px"><span>' . $value->qty . '</span></dd>
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
                                                    <button type="button" class="btn btn-xs btn-success btn-number-' . $value->id . '" onclick="plusItem(' . $value->id . ')" data-type="plus" data-field="quant[2]">
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
        } else {
            $htmls = 'kosong';
        }
        return $htmls;
    }

    public function formUpdateUpload()
    {
        return view('item.update_upload');
    }

    public function formatUpdateExcel()
    {
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'ID Item (Jangan Diganti)');
        $activeWorksheet->setCellValue('B1', 'Name');
        $activeWorksheet->getColumnDimension('B')->setWidth(30);
        $activeWorksheet->setCellValue('C1', 'Quantity');
        $activeWorksheet->setCellValue('D1', 'Sell Price');
        $activeWorksheet->setCellValue('E1', 'Category');
        $activeWorksheet->setCellValue('F1', 'ID Category');
        $activeWorksheet->setCellValue('G1', 'Uom');
        $activeWorksheet->setCellValue('H1', 'Uom ID');
        $activeWorksheet->setCellValue('I1', 'Vendor');
        $activeWorksheet->getColumnDimension('I')->setWidth(23);
        $activeWorksheet->setCellValue('J1', 'Vendor ID');
        $activeWorksheet->setCellValue('K1', 'Warehouse');
        $activeWorksheet->getColumnDimension('K')->setWidth(21);
        $activeWorksheet->setCellValue('L1', 'Warehouse ID');
        $no = 2;
        $data = Item::all();
        foreach ($data as $key => $item) {
            $activeWorksheet->setCellValue('A' . $no, $item->id);
            $style = $activeWorksheet->getStyle('A' . $no);
            $style->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            $activeWorksheet->setCellValue('B' . $no, $item->name);
            $activeWorksheet->setCellValue('C' . $no, $item->qty);
            $activeWorksheet->getStyle('C' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $activeWorksheet->setCellValue('D' . $no, $item->sale_price);
            $activeWorksheet->getStyle('D' . $no)->getNumberFormat()->setFormatCode('#,##0');
            $activeWorksheet->setCellValue('E' . $no, $item->category->name);
            $activeWorksheet->setCellValue('F' . $no, $item->category_id);
            $activeWorksheet->setCellValue('G' . $no, $item->uom->name);
            $activeWorksheet->setCellValue('H' . $no, $item->uom_id);
            $activeWorksheet->setCellValue('I' . $no, $item->vendor->name);
            $activeWorksheet->setCellValue('J' . $no, $item->vendor_id);
            $activeWorksheet->setCellValue('K' . $no, $item->warehouse->name);
            $activeWorksheet->setCellValue('L' . $no, $item->warehouse_id);

            $no++;
        }


        $sheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Categories');
        $spreadsheet->addSheet($sheet1, 1);
        $sheet1 = $spreadsheet->getSheet(1);
        $sheet1->setCellValue('A1', 'ID');
        $sheet1->setCellValue('B1', 'Categories');
        $categories = Category::all();
        $idx = 2;

        foreach ($categories as $key => $value) {
            $sheet1->setCellValue('A' . $idx, $value->id);
            $sheet1->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Warehouse');
        $spreadsheet->addSheet($sheet2, 2);
        $sheet2 = $spreadsheet->getSheet(2);
        $sheet2->setCellValue('A1', 'ID');
        $sheet2->setCellValue('B1', 'Warehouse');
        $warehouses = Warehouse::all();
        $idx = 2;

        foreach ($warehouses as $key => $value) {
            $sheet2->setCellValue('A' . $idx, $value->id);
            $sheet2->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Vendor');
        $spreadsheet->addSheet($sheet3, 3);
        $sheet3 = $spreadsheet->getSheet(3);
        $sheet3->setCellValue('A1', 'ID');
        $sheet3->setCellValue('B1', 'Vendor');
        $vendor = Vendor::all();
        $idx = 2;

        foreach ($vendor as $key => $value) {
            $sheet3->setCellValue('A' . $idx, $value->id);
            $sheet3->setCellValue('B' . $idx, $value->name);
            $idx++;
        }

        $sheet4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Uoms');
        $spreadsheet->addSheet($sheet4, 4);
        $sheet4 = $spreadsheet->getSheet(4);
        $sheet4->setCellValue('A1', 'ID');
        $sheet4->setCellValue('B1', 'Code');
        $sheet4->setCellValue('C1', 'Name');
        $uom = Uom::all();
        $idx = 2;

        foreach ($uom as $key => $value) {
            $sheet4->setCellValue('A' . $idx, $value->id);
            $sheet4->setCellValue('B' . $idx, $value->code);
            $sheet4->setCellValue('C' . $idx, $value->name);
            $idx++;
        }

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Items.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function updateuploadExcel(Request $request)
    {
        $file = $request->file('file_upload');
        $nama_file = rand() . '_' . $file->getClientOriginalName();
        $file->move('file/upload_item/', $nama_file);
        $inputFileName = public_path('/file/upload_item/' . $nama_file);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();
        $dataTemp = [];
        for ($row = 2; $row <= $lastRow; $row++) {
            $temp = [];
            $temp['id'] = $worksheet->getCell('A' . $row)->getValue();
            $temp['name'] = $worksheet->getCell('B' . $row)->getFormattedValue();
            $temp['quantity'] = $worksheet->getCell('C' . $row)->getValue();
            $temp['sell_price'] = $worksheet->getCell('D' . $row)->getValue();
            $temp['category_id'] = $worksheet->getCell('F' . $row)->getValue();
            $temp['uom_id'] = $worksheet->getCell('H' . $row)->getValue();
            $temp['vendor_id'] = $worksheet->getCell('J' . $row)->getValue();
            $temp['warehouse_id'] = $worksheet->getCell('L' . $row)->getValue();
            $dataTemp[] = $temp;
        }
        unlink(public_path('/file/upload_item/' . $nama_file));

        foreach ($dataTemp as $key => $value) {
            $model = Item::find($value['id']);
            $model->name = strtoupper($value['name']);
            $model->qty = $value['quantity'];
            $model->sale_price = $value['sell_price'];
            $model->category_id = $value['category_id'];
            $model->uom_id = $value['uom_id'];
            $model->vendor_id = $value['vendor_id'];
            $model->warehouse_id = $value['warehouse_id'];
            $model->save();
        }

        $res = ['res' => 'success'];
        return $res;
    }
}
