<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $username_check = User::whereNull('deleted_at')
                ->where('username', $request->username)
                ->first();

            if (!is_null($username_check)) {
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password], isset($request->remember))) {
                    $request->session()->regenerate();
                    // For Request Url
                    $intended_url = session()->pull('url.intended', route('home'));
                    return redirect()->to($intended_url);
                } else {
                    return redirect()
                        ->back()
                        ->withErrors(['username' => 'These credentials do not match our records.'])
                        ->withInput();
                }
            } else {
                return redirect()
                    ->back()
                    ->withErrors(['username' => 'These credentials do not match our records.'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['username' => $e->getMessage()])
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
