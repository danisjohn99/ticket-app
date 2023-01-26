<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use Gate;


//MODEL
use App\Models\User;


/**
 * UserController
 * This controlleris used to interact user operations.
 *
 * @author :  <danisjohn99@gmail.com>
 */
class UserController extends Controller
{
    
        /**
         * Users List.
         *
         * @return \Illuminate\Contracts\Support\Renderable
         */
        public function users()
        {
            if (Gate::allows('isAdmin') || Gate::allows('isTechnician')) {
                $authUser = Auth()->user();
                return view('users.userlist',compact('authUser'));
            }
            return Redirect::to('/home'); 
        }

    
        /**
         * Users List Datatable Response
         *
         * @param  Request  $request
         */
        public function usersApi(Request $request)
        {
                $data = User::latest('created_at')->get();
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->make(true);     
        }


        /**
         * Create User
         *
         * @param  Request  $request
         */
        public function createUser(Request $request)
        {
            if (Gate::allows('isAdmin') || Gate::allows('isTechnician')) {

                $validator = Validator::make($request->all(), [
                    'name'  => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'role'  => 'required',
                    'password' => 'required|string|min:8|confirmed',
                ]);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
                
                $password = Hash::make($request->password);
                $userData = ['name'=>$request->name,'email'=>$request->email,'role'=>$request->role,'password'=>$password];
                User::create($userData);
                return redirect()->route('users.list')->with('success','User Created Successfully');  

            }
            return Redirect::to('/home'); 
        }
}
