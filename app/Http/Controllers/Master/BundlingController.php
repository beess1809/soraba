<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Bundling;
use App\Models\Master\Category;
use App\Models\Master\Item;
use App\Models\Master\Uom;
use App\Models\Master\Vendor;
use App\Models\Master\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Response as res;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use Symfony\Component\HttpFoundation\Response;

class BundlingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['model'] = new Bundling();
        $data['item'] = Item::all();
        return view('item.formBundling', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = [];
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Nama Paket Wajib Diisi',
            'price.required' => 'Harga Wajib Diisi'
        ]);
        
        DB::beginTransaction();
        try {
            $model = new Bundling();

            foreach($request->item as $key => $value) {
                $item[] = [
                    'item' => $request->item[$key],
                    'qty' => $request->qty[$key]
                ];
            }
    
            $model->name = $request->name;
            $model->price = str_replace('.', '', $request->price);
            $model->item_id = json_encode($item);
            $model->created_by = Auth::user()->id;
            if ($model->save()) {
                DB::commit();
                return redirect()->route('items.index')->with('alert.success', 'Bundling Has Been Added');
            } else {
                return redirect()->route('items.create')->with('alert.failed', 'Something Wrong');
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
        $id = base64_decode($id);
        $model = Bundling::find($id);
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
        $data['model'] = Bundling::find($id);
        $data['item'] = Item::all();
        return view('item.formBundling', $data);
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
        $item = [];
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Nama Paket Wajib Diisi',
            'price.required' => 'Harga Wajib Diisi'
        ]);

        DB::beginTransaction();
        try {
            $id = base64_decode($id);

            $model = Bundling::find($id);

            foreach($request->item as $key => $value) {
                $item[] = [
                    'item' => $request->item[$key],
                    'qty' => $request->qty[$key]
                ];
            }

            $model->name = $request->name;
            $model->price = str_replace('.', '', $request->price);
            $model->item_id = json_encode($item);
            $model->updated_by = Auth::user()->id;
            if ($model->save()) {
                DB::commit();
                return redirect()->route('items.index')->with('alert.success', 'Bundling Has Been Updated');
            } else {
                return redirect()->route('items.create')->with('alert.failed', 'Something Wrong');
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
        $model = Bundling::find($id);
        $model->deleted_by = Auth::user()->id;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->save();
    }

    public function data(Request $request)
    {
        $term = trim($request->q);

        if (empty($term)) {
            $datas = Bundling::all();
        } else {
            $datas = Bundling::where('name', 'like', '%' . $term . '%')->get();
        }
        $res = [];
        foreach ($datas as $data) {
            $res[] = ['id' => $data->id, 'text' => $data->name];
        }
        return res::json($res);
    }

    public function datatable(Request $request)
    {
        $query = Bundling::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('bundling.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info" title="Edit Bundling"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('bundling.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
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
                foreach($items as $key => $i) {
                    $item = Item::find($i->item);
                    $string .= '<li><b>Item : </b>'.$item->name.' | <b>Qty : </b>'.$i->qty.'</li>';
                }
                $string .= '</ul>';
                return  $string;
            })
            
            ->addIndexColumn()
            ->rawColumns(['action','items'])
            ->make(true);
    }

    public function getItemByBundling(Request $request)
    {
        // $id = base64_decode($id);
        $id = $request->bundling_id;
        $model = Bundling::find($id);
        $items = Item::all();
        $data['html'] = '';

        foreach($items as $i) {
            $all_items[] = [
                'id' => $i->id,
                'text' => $i->name,
            ];
        }

        if($model) {
            $data['harga'] = format_rupiah($model->price);
            $item_c = json_decode($model->item_id);
            
            foreach($item_c as $i) {
                $data['html'] .=  '<div class=""with-item>';
                $data['html'] .=    '<div class="row ">';
                $data['html'] .=    '   <div class="form-group col-lg-6 col-md-5 col-8">';
                $data['html'] .=    '       <label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>';
                $data['html'] .=    '       <div class="col-sm-12">';
                $data['html'] .=    '           <select class="form-control select-item" name="__item_id" id="__item_ids_'.$model->id.$i->item.'" disabled>';
                $data['html'] .=    '               <option value="">Pilih Item</option>';
                foreach($items as $it) {
                    $selected = $i->item==$it->id ? 'selected' : '';
                    $data['html'] .=    '               <option value="'.$it->id.'" '.$selected.'>'.$it->name.'</option>';
                }
                $data['html'] .=    '           </select>';
                $data['html'] .=    '           <input type="hidden" class="item-formula" value="'.$i->item.'">';
                $data['html'] .=    '       </div>';
                $data['html'] .= '      </div>';
                $data['html'] .= '      <div class="form-group col-lg-3 col-md-3 col-4">';
                $data['html'] .= '          <label for="qty" class="col-sm-12 col-form-label">Item Quantity <span class="text-red">*</span></label>';
                $data['html'] .= '          <div class="col-sm-12">';
                $data['html'] .= '              <input type="number" class="form-control qty-formula" name="__qty_item" id="__qty_item_'.$model->id.$i->item.'" value="'.$i->qty.'" readonly>';
                $data['html'] .= '          </div>';
                $data['html'] .= '      </div>';
    
                $data['html'] .= '  </div>';
                $data['html'] .= '</div>';
            //     $item['items'][] = [
            //         'selected_value' => $i->item,
            //         'options' => $all_items
            //     ];
            }

        }
        return res::json($data);
    }


    public function addBundling()
    {
        $indexBundling = time();
        $model = new Item();
        $item = Item::all();
        return view('item.bundling', ['model' => $model, 'item' => $item, 'indexBundling' => $indexBundling]);
    }

}
