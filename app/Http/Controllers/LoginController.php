<?php

namespace App\Http\Controllers;

use App\Models\Authenticate;
use App\Models\NewStudent;
use App\Models\PrevApp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $matric = $request->input('matric');
    $password = $request->input('password');

    $authenticate = User::where('matric', $matric)->first();

    if ($authenticate) {
        $prevApp = PrevApp::where('matric', $matric)->first();

        if ($prevApp) {
            $user = NewStudent::find($prevApp->user_id);

            if (Auth::attempt($request->only(['matric', 'password']))) {
                Session::put('user', $user);
                Session::put('matric', $matric); 
                return redirect()->route('dashboard');
            }

            return back()->withErrors(['message' => 'User not found'])->withInput();
        } else {
            return back()->withErrors(['message' => 'User not found'])->withInput();
        }
    } else {
        return back()->withErrors(['message' => 'Invalid Matric or Password'])->withInput();
    }
}


    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Auth::logout();
        Session::forget('user');
        return redirect()->route('login');
    }
}
