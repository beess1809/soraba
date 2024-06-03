<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Category;
use Exception;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mst.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Category();
        return view('mst.category.form', ['model' => $model]);
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
            // 'code' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = new Category();
            // $model->code = $request->code;
            $model->name = $request->name;
            $model->created_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.category.index')->with('alert.success', 'Category Has Been Added');
            } else {
                return redirect()->route('master.category.create')->with('alert.failed', 'Something Wrong');
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
        $model = Category::find($id);
        return view('mst.category.form', ['model' => $model]);
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
            // 'code' => 'required',
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $id = base64_decode($id);

            $model = Category::find($id);
            // $model->code = $request->code;
            $model->name = $request->name;
            $model->updated_by = Auth::user()->id;

            if ($model->save()) {
                DB::commit();
                return redirect()->route('master.category.index')->with('alert.success', 'Category Has Been Updated');
            } else {
                return redirect()->route('master.category.create')->with('alert.failed', 'Something Wrong');
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
        $model = Category::find($id);
        $model->deleted_by = Auth::user()->id;
        $model->save();
        $model->delete();
    }

    public function datatable(Request $request)
    {
        $query = Category::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('master.category.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Category"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('master.category.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
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
        return view('mst.category.upload');
    }

    public function formatExcel()
    {

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'No');
        // $activeWorksheet->setCellValue('B1', 'Code');
        $activeWorksheet->setCellValue('B1', 'Name');

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Upload Category.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file_upload');
        $nama_file = rand() . '_' . $file->getClientOriginalName();
        $file->move('file/upload_category/', $nama_file);
        $inputFileName = public_path('/file/upload_category/' . $nama_file);


        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadSheet = $reader->load($inputFileName);
        $worksheet = $spreadSheet->getActiveSheet();
        $lastRow = $worksheet->getHighestRow();
        $dataTemp = [];
        for ($row = 2; $row <= $lastRow; $row++) {
            $temp = [];
            $temp['no'] = $worksheet->getCell('A' . $row)->getValue();
            // $temp['code'] = $worksheet->getCell('B' . $row)->getFormattedValue();
            $temp['name'] = $worksheet->getCell('B' . $row)->getValue();
            $dataTemp[] = $temp;
        }
        unlink(public_path('/file/upload_category/' . $nama_file));

        DB::beginTransaction();
        try {
            foreach ($dataTemp as $key => $value) {
                $model = new Category();
                // $model->code = $value['code'];
                $model->name = $value['name'];
                $model->created_by = Auth::user()->id;
                $model->save();
            }
            DB::commit();
            $res = ['res' => 'success'];
            return $res;
        }
        catch (Exception $e) {
            DB::rollBack();
            print($e);
            $res = ['res' => 'error', 'error' => json_encode($e),];
            return $res;
        }
    }
}