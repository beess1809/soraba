<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Master\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('permission:read-auth-user', [
        //     'only' => ['index', 'datatable']
        // ]);
        // $this->middleware('permission:create-auth-user', [
        //     'only' => ['create', 'store']
        // ]);
        // $this->middleware('permission:update-auth-user', [
        //     'only' => ['edit', 'update']
        // ]);
        // $this->middleware('permission:delete-auth-user', [
        //     'only' => ['destroy']
        // ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new User();
        return view('auth.user.form', ['model' => $model]);
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
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $model = new User();
        $model->parent_id = $request->parent_id;
        $model->name = $request->name;
        $model->email = $request->email;
        $model->password = bcrypt($request->password);
        $model->created_by = Auth::user()->id;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            if ($request->role_id) {
                $role = Role::where('id', $request->role_id)->first();
                $model->attachRole($role);
            }
            $content = 'success';
            $status = '200';
        } else {
            $content = 'failed';
            $status = '400';
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
        $model = User::findorFail($id);
        return view('auth.user.form', ['model' => $model]);
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
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        $id = base64_decode($id);

        $model = User::findOrFail($id);
        $model->parent_id = $request->parent_id;
        $model->name = $request->name;
        $model->email = $request->email;
        $model->password = bcrypt($request->password);
        $model->updated_by = Auth::user()->id;
        $model->updated_at = date('Y-m-d H:i:s');

        $oldRole = $model->roles;
        $model->detachRole($oldRole[0]);

        if ($model->save()) {
            if ($request->role_id) {
                $role = Role::where('id', $request->role_id)->first();
                $model->attachRole($role);
            }
            $content = 'success';
            $status = '200';
        } else {
            $content = 'failed';
            $status = '400';
        }

        return (new Response($content, $status))
            ->header('Content-Type', 'html');
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

        $model = User::find($id);

        $model->deleted_by = Auth::user()->id;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->save();
    }

    public function datatable(Request $request)
    {
        $query = User::query();
        if ($request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->email) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }
        if ($request->role_id) {
            $role_id = $request->role_id;
            $query->whereHas('roles', function ($q) use ($role_id) {
                $q->whereIn('id', $role_id);
            });
        }
        return DataTables::of($query)
            ->addColumn('roles', function ($model) {
                $data['roles'] = $model->roles;
                return view('auth.user.role', $data);
            })
            ->addColumn('action', function ($model) {
                $string = '<div class="btn-group">';
                $string .= '<a href="' . route('auth.user.edit', ['id' => base64_encode($model->id)]) . '" type="button"  class="btn btn-sm btn-info modal-show" title="Edit User"><i class="fas fa-edit"></i></a>';
                $string .= '&nbsp;&nbsp;<a href="' . route('auth.user.destroy', ['id' => base64_encode($model->id)]) . '" type="button" class="btn btn-sm btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></a>';
                $string .= '</div>';
                return
                    $string;
            })

            ->addIndexColumn()
            ->rawColumns(['roles', 'action'])
            ->make(true);
    }

    // public function userBranch(Request $request)
    // {
    //     $data['user'] = User::where("branch_id", $request->branch_id)->get(["name", "id"]);
    //     return response()->json($data);
    // }
}
