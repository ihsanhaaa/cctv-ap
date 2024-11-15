<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use DB;
use Hash;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // $data = User::orderBy('id','DESC')->get();
        // $data = User::where('name', '!=', 'User Test PSPIG')->orderBy('id','DESC')->get();
        $users = User::orderBy('id','DESC')->get();

        return view('users.index',compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // dd($request->input('desa'));
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
    
        return redirect()->route('data-user.index')
                        ->with('success','User berhasil ditambahkan');
    }
    
    public function show($id)
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }
    
    public function edit($id)
    {
        $user = User::find($id);
    
        return view('users.edit',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);

        $user->update($input);
    
        return redirect()->route('data-user.index')
                        ->with('success','User berhasil diubah');
    }
    
    public function destroy($id)
    {
        User::find($id)->delete();

        return redirect()->route('data-user.index')
                        ->with('success','User berhasil dihapus');
    }
}
