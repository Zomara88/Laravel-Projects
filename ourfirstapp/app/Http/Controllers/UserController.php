<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request) {
        $incomingFields = $request->validate([    // Ensures name and password are put in, or else request is rejected
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
        }

        return redirect('/');
    }
    public function logout() {
        auth()->logout();
        return redirect('/');
    }
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:10', Rule::unique('users', 'name')], // name needs to be at least 3 characters and at most 10 characters
            'email' => ['required', 'email', Rule::unique('users', 'email')], // must be in email format: textstring@textstring
            'password' => ['required', 'min:8', 'max:100'] // name needs to be at least 8 characters and at most 100 characters
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }
}
