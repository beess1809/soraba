<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Role();
        $permissions = Permission::all();
        return view('auth.role.form', ['model' => $model, 'permissions' => $permissions]);
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


        $model = new Role();
        $model->name = $request->name;
        $model->role_id = $request->role_id;
        $model->display_name = $request->display_name;
        $model->description = $request->description;
        
        if ($model->save()) {

            if($request->permission)
            {
                $model->attachPermissions($request->permission);
            }

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
        $model = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('auth.role.form', ['model' => $model, 'permissions' => $permissions]);
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
            'display_name' => 'required',
        ]);

        $id = base64_decode($id);

        $model = Role::findOrFail($id);
        $model->name = $request->name;
        $model->role_id = $request->role_id;
        $model->display_name = $request->display_name;
        $model->description = $request->description;
        
        if ($model->save()) {

            $oldRole    = $model->permissions;
            $model->detachPermissions($oldRole);

            if($request->permission)
            {
                $model->attachPermissions($request->permission);
            }
            
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

        Role::destroy($id);
    }

    public function datatable(Request $request)
    {
        $query = Role::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="'.route('auth.role.edit',['id' => base64_encode($model->id)]).'" type="button"  class="btn btn-sm btn-info modal-show" title="Edit Role"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('auth.role.destroy',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
            return
                $string;
            })
            ->addIndexColumn()
            ->rawColumns(['roles','action'])
            ->make(true);
    }
}
