<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Master\FlashSale;
use App\Models\Master\FlashSaleBundling;
use App\Models\Master\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('flash-sale.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new FlashSale();
        return view('flash-sale.form', ['model' => $model]);
    }

    public function createBundling()
    {
        $data['model'] = new FlashSaleBundling();
        $data['item'] = Item::all();
        return view('flash-sale.formBundling', $data);
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
            $model = new FlashSale();
            $model->time_start = $request->time_start;
            $model->time_end = $request->time_end;
            $model->active = $request->active;
            if ($model->save()) {
                DB::commit();
                return redirect()->route('flash-sale.index')->with('alert.success', 'Bundling Has Been Added');
            } else {
                return redirect()->route('flash-sale.createBundling')->with('alert.failed', 'Something Wrong');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            print($e);
        }
    }

    public function storeBundling(Request $request)
    {
        $item = [];
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $model = new FlashSaleBundling();

            foreach ($request->item as $key => $value) {
                $item[] = [
                    'item' => $request->item[$key],
                    'qty' => $request->qty[$key]
                ];
            }

            $model->name = $request->name;
            $model->price = str_replace('.', '', $request->price);
            $model->item_id = json_encode($item);
            if ($model->save()) {
                DB::commit();
                return redirect()->route('flash-sale.index')->with('alert.success', 'Bundling Has Been Added');
            } else {
                return redirect()->route('flash-sale.createBundling')->with('alert.failed', 'Something Wrong');
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
        $model = FlashSale::find(base64_decode($id));
        return view('flash-sale.form', ['model' => $model]);
    }

    public function editBundling($id)
    {
        $id = base64_decode($id);
        $data['model'] = FlashSaleBundling::find($id);
        $data['item'] = Item::all();
        return view('flash-sale.formBundling', $data);
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
        DB::beginTransaction();
        try {
            $model =  FlashSale::find(base64_decode($id));
            $model->time_start = $request->time_start;
            $model->time_end = $request->time_end;
            $model->active = $request->active;
            if ($model->save()) {
                DB::commit();
                return redirect()->route('flash-sale.index')->with('alert.success', 'Bundling Has Been Added');
            } else {
                return redirect()->route('flash-sale.createBundling')->with('alert.failed', 'Something Wrong');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            print($e);
        }
    }

    public function updateBundling(Request $request, $id)
    {
        $item = [];
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $id = base64_decode($id);

            $model = FlashSaleBundling::find($id);

            foreach ($request->item as $key => $value) {
                $item[] = [
                    'item' => $request->item[$key],
                    'qty' => $request->qty[$key]
                ];
            }

            $model->name = $request->name;
            $model->price = str_replace('.', '', $request->price);
            $model->item_id = json_encode($item);

            if ($model->save()) {
                DB::commit();
                return redirect()->route('flash-sale.index')->with('alert.success', 'Bundling Has Been Updated');
            } else {
                return redirect()->route('flash-sale.createBundling')->with('alert.failed', 'Something Wrong');
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
        //
    }

    public function destroyBundling($id)
    {
        $id = base64_decode($id);
        $model = FlashSaleBundling::find($id);
        $model->delete();
    }

    public function datatable(Request $request)
    {
        $query = FlashSale::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('flash-sale.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('flash-sale.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
    public function datatableBundling(Request $request)
    {
        $query = FlashSaleBundling::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('flash-sale.editBundling', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Bundling"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('flash-sale.destroyBundling', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })
            ->editColumn('price', function ($model) {
                return  format_rupiah($model->price);
            })
            ->addColumn('items', function ($model) {
                $string = '';
                $items = json_decode($model->item_id);
                $string .= '<ul>';
                foreach ($items as $key => $i) {
                    $item = Item::find($i->item);
                    $string .= '<li><b>Item : </b>' . $item->name . ' | <b>Qty : </b>' . $i->qty . '</li>';
                }
                $string .= '</ul>';
                return  $string;
            })

            ->addIndexColumn()
            ->rawColumns(['action', 'items'])
            ->make(true);
    }
}