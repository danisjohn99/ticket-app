<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

//MODEL
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $authUser = Auth::user();
        $users = User::where('role','user')->get();
        if(Auth::user()->role =="user"){
         $users = User::where('id',Auth::user()->id)->get();
        }
        return view('home',compact('users','authUser'));
    }
}
