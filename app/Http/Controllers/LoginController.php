<?php

namespace App\Http\Controllers;

use App\Models\Authenticate;
use App\Models\Login;
use App\Models\NewStudent;
use Illuminate\Http\Request;
use App\Models\PrevApp;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $matric = $request->input('matric');
        $password = $request->input('password');

        // Authenticate the user
        $authenticate = Authenticate::where('matric', $matric)->where('password', $password)->first();

        if ($authenticate) {
            // Retrieve the user from the 'prev_app' table using the matric
            $prevApp = PrevApp::where('matric', $matric)->first();

            if ($prevApp) {
                // Retrieve the user's information from the 'new_student' table using the user_id from 'prev_app'
                $user = NewStudent::find($prevApp->user_id);
                
                // Store the user's information in the session
                session(['user' => $user]);

                return redirect()->route('dashboard');
            } else {
                return back()->withErrors(['message' => 'User not found']);
            }
        } else {
            return back()->withErrors(['message' => 'Invalid matric number or password']);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Login $login)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Login $login)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Login $login)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Login $login)
    {
        session()->forget('authenticated_matric');
        return redirect()->route('login.index');
    }
}



