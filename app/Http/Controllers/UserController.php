<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Game;
use App\Models\Exercise;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        $exercises = auth()->user()->exercises()->get();
        if (auth()->user()->isRole('teacher') || auth()->user()->isRole('admin')) {
            $exercises = Exercise::all();
        }
        $games = Game::all();
        foreach ($games as $game) {
            $game->game_content = '';
            $game->result = '';
            $temp_files = glob( public_path('upload\game\game-' . $game->id . '\*.*') );
            foreach($temp_files as $file) {
                $game->game_content = file_get_contents($file);
                $game->result = str_replace( public_path('upload\game\game-' . $game->id), '', $file);
                $game->result = str_replace('\\', '', $game->result);
                $game->result = str_replace('.txt', '', $game->result);
                $game->result = str_replace('-', ' ', $game->result);
                $game->result = ucwords($game->result);
            }
        }
        return view('block.view_user', compact('users', 'exercises', 'games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
        return view('block.add_user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        unset($data['_token']);
        $data['password'] = bcrypt($data['password']);
        unset($data['roles']);
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:16|unique:users',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required',
            'sdt' => 'required|regex:/(0)[0-9]{9}/|unique:users'
        ], [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tên không được quá dài',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được quá dài',
            'email.unique' => 'Email đã được sử dụng',
            'username.required' => 'Username không được để trống',
            'username.max' => 'Username không được quá dài',
            'username.unique' => 'Username đã tồn tại',
            'password.required' => 'Password không được để trống',
            'password.min' => 'Password phải dài hơn 6 ký tự',
            'password.confirmed' => 'Password không trùng nhau',
            'gender.required' => 'Giới tính chưa chọn',
            'sdt.required' => 'Sđt không được để trống',
            'sdt.regex' => 'Sđt không đúng định dạng',
            'sdt.unique' => 'Sdt đã được sử dụng',
        ]);
        try {
            //code...
            DB::beginTransaction();
            $user = User::create($data);
            $user->roles()->attach($request->roles);
            DB::commit();
            return redirect('/');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error: .'.$e->getMessage() . 'line: '.$e->getLine());
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
        $user = User::findOrFail($id);
        $roles = Role::all();
        $listRoles = DB::table('role_user')->where('user_id', $id)->pluck('role_id');
        return view('block.info_user', compact('roles', 'user', 'listRoles'));
    }
    public function updateInfo(Request $request, $id)
    {
        if (!auth()->user()->isRole('student')) {
            $dataValidate['name'] = 'required|max:255';
            $dataValidate['username'] = 'required|max:16|unique:users,username,'.$id.',id';
            $messenger['username.required'] = 'Username không được để trống';
            $messenger['username.max'] = 'Username không được quá dài';
            $messenger['username.unique'] = 'Username đã tồn tại';
            $messenger['name.required'] = 'Tên không được để trống';
            $messenger['name.max'] = 'Tên không được quá dài';
            $data['username'] = $request->username;
            $data['name'] = $request->name;
        }
        $dataValidate = [
            'email' => 'required|email|max:255|unique:users,email,'.$id.',id',
            'gender' => 'required',
            'sdt' => 'required|regex:/(0)[0-9]{9}/|unique:users,sdt,'.$id.',id',
        ];
        $messenger = [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được quá dài',
            'email.unique' => 'Email đã được sử dụng',
            'gender.required' => 'Giới tính chưa chọn',
            'sdt.required' => 'Sđt không được để trống',
            'sdt.regex' => 'Sđt không đúng định dạng',
            'sdt.unique' => 'Sdt đã được sử dụng',
        ];

        if ($request->has('password') && $request->password !== null) {
            $dataValidate['password'] = 'required|min:6|confirmed';
            $messenger['password.required'] = 'Password không được để trống';
            $messenger['password.min'] = 'Password phải dài hơn 6 ký tự';
            $messenger['password.confirmed'] = 'Password không trùng nhau';
            $data['password'] = bcrypt($request->password);
        }

        $check = $request->validate($dataValidate, $messenger);
        
        $data['email'] = $request->email;
        $data['sdt'] = $request->sdt;
        $data['gender'] = $request->gender;
        User::where('id', $id)->update($data);
        return redirect('/');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $roles = Role::all();
        $listRoles = DB::table('role_user')->where('user_id', $id)->pluck('role_id');
        return view('block.edit_user', compact('roles', 'user', 'listRoles'));
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
        //
        try {
            $dataValidate = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$id.',id',
                'username' => 'required|max:16|unique:users,username,'.$id.',id',
                'gender' => 'required',
                'sdt' => 'required|regex:/(0)[0-9]{9}/|unique:users,sdt,'.$id.',id',
                'roles' => 'required',
            ];
            $messenger = [
                'name.required' => 'Tên không được để trống',
                'name.max' => 'Tên không được quá dài',
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.max' => 'Email không được quá dài',
                'email.unique' => 'Email đã được sử dụng',
                'username.required' => 'Username không được để trống',
                'username.max' => 'Username không được quá dài',
                'username.unique' => 'Username đã tồn tại',
                'gender.required' => 'Giới tính chưa chọn',
                'sdt.required' => 'Sđt không được để trống',
                'sdt.regex' => 'Sđt không đúng định dạng',
                'sdt.unique' => 'Sdt đã được sử dụng',
                'roles.required' => 'Vai trò không được để trống',
            ];

            $check = $request->validate($dataValidate, $messenger);
            
            $data['username'] = $request->username;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['sdt'] = $request->sdt;
            $data['gender'] = $request->gender;
            DB::beginTransaction();
            User::where('id', $id)->update($data);
            DB::table('role_user')->where('user_id', $id)->delete();
            $userUpdate = User::find($id);
            $userUpdate->roles()->attach($request->roles);
            DB::commit();
            return redirect('/');
        } catch (\ValidationException $v) {
            DB::rollBack();
            \Log::error('Error: .'.$v->getMessage() . 'line: '.$v->getLine());
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
        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->delete();
            $user->roles()->detach();
            DB::commit();
            return redirect('/');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error: .'.$e->getMessage() . 'line: '.$e->getLine());
        }
    }
}
