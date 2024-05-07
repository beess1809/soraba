<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Permission();
        return view('auth.permission.form', ['model' => $model]);
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
            'name' => 'required|unique:roles',
            'display_name' => 'required',
        ]);


        $model = new Permission();
        $model->name = $request->name;
        $model->display_name = $request->display_name;
        $model->description = $request->description;
        
        if ($model->save()) {
            $content = 'success'; 
            $status = '200'; 
        } else {
            $content = 'failed'; 
            $status = '400'; 
        }

        return (new Response($content,$status))
        ->header('Content-Type','html');
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
        $model = Permission::findOrFail($id);
        return view('auth.permission.form', ['model' => $model]);
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
            'name' => 'required|unique:roles',
            'display_name' => 'required',
        ]);

        $id = base64_decode($id);

        $model = Permission::findOrFail($id);
        $model->name = $request->name;
        $model->display_name = $request->display_name;
        $model->description = $request->description;
        
        if ($model->save()) {
            $content = 'success'; 
            $status = '200'; 
        } else {
            $content = 'failed'; 
            $status = '400'; 
        }

        return (new Response($content,$status))
        ->header('Content-Type','html');
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

        Permission::destroy($id);
    }

    public function datatable(Request $request)
    {
        $query = Permission::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="'.route('auth.permission.edit',['id' => base64_encode($model->id)]).'" type="button"  class="btn btn-sm btn-info modal-show" title="Edit Permission"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('auth.permission.destroy',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
            return
                $string;
            })
            ->addIndexColumn()
            ->rawColumns(['roles','action'])
            ->make(true);
    }
}
