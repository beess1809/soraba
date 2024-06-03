<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Warehouse;
use Exception;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Warehouse();
        return view('mst.warehouse.form', ['model' => $model]);
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
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'no_telp' => 'required',
            'email' => 'required',
            'npwp' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = new Warehouse();
            $model->code = $request->code;
            $model->name = $request->name;
            $model->address = $request->address;
            $model->no_telp = $request->no_telp;
            $model->email = $request->email;
            $model->npwp = $request->npwp;
            $model->created_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.warehouse.index')->with('alert.success', 'Warehouse Berhasil Ditambahkan');
            } else {
                return redirect()->route('master.warehouse.create')->with('alert.failed', 'Terjadi Sesuatu');
            }
        }
        catch (Exception $e) {
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
        $id = base64_decode($id);
        $model = Warehouse::find($id);
        return view('mst.warehouse.form', ['model' => $model]);
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
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'no_telp' => 'required',
            'email' => 'required',
            'npwp' => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $id = base64_decode($id);

            $model = Warehouse::find($id);
            $model->code = $request->code;
            $model->name = $request->name;
            $model->address = $request->address;
            $model->no_telp = $request->no_telp;
            $model->email = $request->email;
            $model->npwp = $request->npwp;
            $model->updated_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.warehouse.index')->with('alert.success', 'Warehouse Has Been Updated');
            } else {
                return redirect()->route('master.warehouse.create')->with('alert.failed', 'Something Wrong');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            print($e);
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
        $model = Warehouse::find($id);
        $model->deleted_by = Auth::user()->id;
        $model->save();
        $model->delete();
    }

    public function datatable(Request $request)
    {
        $query = Warehouse::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('master.warehouse.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Warehouse"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('master.warehouse.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function formUpload()
    {
        return view('mst.warehouse.upload');
    }

    public function formatExcel()
    {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'No');
        $activeWorksheet->setCellValue('B1', 'Code');
        $activeWorksheet->setCellValue('C1', 'Name');
        $activeWorksheet->setCellValue('D1', 'Address');

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Upload Warehouse.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file_upload');
        $nama_file = rand() . '_' . $file->getClientOriginalName();
        $file->move('file/upload_warehouse/', $nama_file);
        $inputFileName = public_path('/file/upload_warehouse/' . $nama_file);


        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();
        $dataTemp = [];
        for ($row = 2; $row <= $lastRow; $row++) {
            $temp = [];
            $temp['no'] = $worksheet->getCell('A' . $row)->getValue();
            $temp['code'] = $worksheet->getCell('B' . $row)->getFormattedValue();
            $temp['name'] = $worksheet->getCell('C' . $row)->getValue();
            $temp['address'] = $worksheet->getCell('D' . $row)->getValue();
            $dataTemp[] = $temp;
        }
        unlink(public_path('/file/upload_warehouse/' . $nama_file));

        foreach ($dataTemp as $key => $value) {
            $model = new Warehouse();
            $model->code = $value['code'];
            $model->name = $value['name'];
            $model->address = $value['address'];
            $model->created_by = Auth::user()->id;
            $model->save();
        }

        $res = ['res' => 'success'];
        return $res;
    }
}
