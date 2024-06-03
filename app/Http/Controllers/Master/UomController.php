<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Uom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response as res;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst.uom.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Uom();
        return view('mst.uom.form', ['model' => $model]);
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
            'code' => 'required|max:6',
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = new Uom();
            $model->code = $request->code;
            $model->name = $request->name;
            $model->created_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.uom.index')->with('alert.success', 'Unit Of Material Has Been Added');
            } else {
                $content = 'failed';
                $status = '400';
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            print($e);
        }

        return (new Response($content, $status))
            ->header('Content-Type', 'html');
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
        $model = Uom::find($id);
        return view('mst.uom.form', ['model' => $model]);
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
            'code' => 'required|max:6',
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $id = base64_decode($id);

            $model = Uom::find($id);
            $model->code = $request->code;
            $model->name = $request->name;
            $model->updated_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.uom.index')->with('alert.success', 'Unit Of Material Has Been Updated');
            } else {
                return redirect()->route('master.uom.create')->with('alert.failed', 'Something Wrong');
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
        $model = Uom::find($id);
        $model->deleted_by = Auth::user()->id;
        $model->save();
        $model->delete();
    }

    public function datatable(Request $request)
    {
        $query = Uom::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('master.uom.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit UoM"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('master.uom.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function data(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            $datas = Uom::all();
        } else {
            $datas = Uom::where('name', 'like', '%' . $term . '%')->get();
        }


        $res = [];

        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name];
        }
        return res::json($res);


        // $data = Material::all();

        // return response()->json($data);

    }

    public function formUpload()
    {
        return view('mst.uom.upload');
    }

    public function formatExcel()
    {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'No');
        $activeWorksheet->setCellValue('B1', 'Code');
        $activeWorksheet->setCellValue('C1', 'Name');

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Upload Unit Of Material.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file_upload');
        $nama_file = rand() . '_' . $file->getClientOriginalName();
        $file->move('file/upload_uom/', $nama_file);
        $inputFileName = public_path('/file/upload_uom/' . $nama_file);


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
            $dataTemp[] = $temp;
        }
        unlink(public_path('/file/upload_uom/' . $nama_file));

        foreach ($dataTemp as $key => $value) {
            $model = new Uom();
            $model->code = $value['code'];
            $model->name = $value['name'];
            $model->created_by = Auth::user()->id;
            $model->save();
        }

        $res = ['res' => 'success'];
        return $res;
    }
}