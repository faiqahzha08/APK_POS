<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    
    public function index(SearchRequest $request)
    {
        $keyword = $request->input('search');

        if ($keyword) {
            $users = User::whereRaw("MATCH(name,email) AGAINST(? IN BOOLEAN MODE)", [$keyword])
                ->paginate(10)
                ->withQueryString();
        } else {
            $users = User::latest()->paginate(10)->withQueryString();
        }
        return view('users.index', compact('users'));
    }


    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        $dataReq = $request->validated();

        $data['name'] = $dataReq['name'];
        $data['email'] = $dataReq['email'];
        $data['password'] = Hash::make ($dataReq['password']);
        $data['role_id'] = $dataReq['role_id'];

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
{
    $roles = Role::all();

    return view('users.edit', compact('user', 'roles'));
}

    public function update(UpdateRequest $request, User $user)
{
    $dataReq = $request->validated();

    $user->name    = $dataReq['name'];
    $user->email   = $dataReq['email'];
    $user->role_id = $dataReq['role_id'];

    if (!empty($dataReq['password'])) {
        $user->password = Hash::make($dataReq['password']);
    }

    $user->save();

        return redirect()->route('admin.users.edit', $user->id)->with('success', 'User updated');
    }

    

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('succes', 'User deleted');
    }

}
