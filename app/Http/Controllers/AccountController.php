<?php

namespace App\Http\Controllers;

use App\Mail\AccountEmailChanged;
use App\Mail\AccountPasswordChanged;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * New controller instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Show user their servers
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function servers(Request $request){
        return view("account.servers")->with([
            "servers" => $request->user()->servers
        ]);
    }

    /**
     * Show user their servers
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request){
        $orders = Transaction::whereHas("server", function($q){
            $q->where("user_id", auth()->user()->id);
        })
        ->orderBy("created_at", "DESC")
        ->paginate(20);
        return view("account.orders")->with([
            "orders" => $orders
        ]);
    }

    /**
     * Show user settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request){
        return view("account.settings")->with([
            "user" => $request->user()
        ]);
    }

    /**
     * Update user details
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request){
        $request->validate([
            "username" => ["required",  'string', "min:5", 'max:50', 'regex:/^[A-Za-z0-9_]+$/', Rule::unique("users")->ignore($request->user()->id)],
            "email" => ['required', 'string', 'email', 'indisposable', 'max:255', Rule::unique("users")->ignore($request->user()->id)],
        ]);
        if($request->user()->email != $request->input("email")){

            Mail::to($request->user()->email)->send(new AccountEmailChanged);
            
            $request->user()->email = $request->input("email");
            $request->user()->email_verified_at = null;
            $request->user()->save();

            $request->user()->sendEmailVerificationNotification();
        }
        $request->user()->update([
        ]);
        return redirect()->back()->with("success", "Your details have been updated");
    }

    /**
     * Update user password
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request){
        $request->validate([
            "current_password" => ["required",  'string'],
            "new_password" => [
                'required', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            ],
        ]);
        
        if(!Hash::check($request->input("current_password"), $request->user()->password)){
            return redirect()->back()->withErrors(["current_password"=>"The password you entered is incorrect, please try again."]);
        }

        Mail::to($request->user()->email)->send(new AccountPasswordChanged);

        $request->user()->update([
            "password"=>Hash::make($request->input("new_password"))
        ]);
        return redirect()->back()->with("success", "Your password has been changed.");
    }

    /**
     * Delete User Account
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destory(Request $request){
        $request->validate([
            "current_password" => ["required",  'string'],
        ]);
        
        if(!Hash::check($request->input("confirm_password"), $request->user()->password)){
            return redirect()->back()->withErrors(["confirm_password"=>"The password you entered is incorrect, please try again."]);
        }

        $user = $request->user();
        Auth::logout();
        $user->delete();

        return redirect(route("home"));
    }
}
