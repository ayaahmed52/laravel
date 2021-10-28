<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loginrequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function getlogin(){
        return view('admin.auth.login');
    }
    public function login(Loginrequest $request){

      //  $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {
            // notify()->success('تم الدخول بنجاح  ');
            return redirect() -> route('admin.dashboard');
        }
        // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);
    }
    public function save(){
        $admin = new App\Models\Admin();
        $admin -> name= 'aya';
        $admin -> email= 'ayaahmed@gmail.com';
        $admin -> password= bcrypt('1234');
        $admin ->save();
    }

}
