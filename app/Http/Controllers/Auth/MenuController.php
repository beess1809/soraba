<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Menu;
use App\Models\Auth\MenuRole;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Menu();
        $roles = Role::all();
        return view('auth.menu.form', ['model' => $model, 'roles' => $roles]);
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
            'name' => 'required|unique:menus',
            'display_name' => 'required',
            'url' => 'required',
            'icon' => 'required',
        ]);
        
        $model = new Menu();
        $model->name = $request->name;
        $model->display_name = $request->display_name;
        $model->menu_id = $request->menu_id;
        $model->url = $request->url;
        $model->icon = $request->icon;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {

            if($request->role)
            {
                foreach ($request->role as $key => $value) 
                {
                    $menuRole = new MenuRole();
                    $menuRole->role_id = $value;
                    $menuRole->menu_id = $model->id;
                    $menuRole->save();
                }
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
        $model = Menu::findOrFail($id);
        $roles = Role::all();
        $menuR = MenuRole::where('menu_id',$id)->get();
        return view('auth.menu.form', ['model' => $model, 'roles' => $roles, 'menuR'=>$menuR]);
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
            // 'name' => 'required|unique:menus',
            'display_name' => 'required',
            'url' => 'required',
            'icon' => 'required',
        ]);
        
        $id = base64_decode($id);
        $model = Menu::findOrFail($id);
        $model->name = $request->name;
        $model->display_name = $request->display_name;
        $model->menu_id = $request->menu_id;
        $model->url = $request->url;
        $model->icon = $request->icon;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {

        $MenuRoleD   = MenuRole::where('menu_id','=',$id)->delete();

        if($request->role)
            {
                foreach ($request->role as $key => $value) 
                {
                    $menuRole = new MenuRole();
                    $menuRole->role_id = $value;
                    $menuRole->menu_id = $model->id;
                    $menuRole->save();
                }
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
        MenuRole::where('menu_id','=',$id)->delete();
        Menu::destroy($id);
    }

    public function datatable(Request $request)
    {
        $query = Menu::all();
        return DataTables::of($query)
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="'.route('auth.menu.edit',['id' => base64_encode($model->id)]).'" type="button"  class="btn btn-sm btn-info modal-show" title="Edit Menu"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="'.route('auth.menu.destroy',['id' => base64_encode($model->id)]).'" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
            return
                $string;
            })
            ->addIndexColumn()
            ->rawColumns(['roles','action'])
            ->make(true);
    }
}
