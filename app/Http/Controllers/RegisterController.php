<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * @desc Show register form
     * @route GET /register
     */
    public function register (): View
    {
        return view('auth.register');
    }

    /**
     * @desc Save user to the database
     * @route POST /register
     */
    public function store (Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'      =>  'required|string|max:100',
            'email'     =>  'required|string|email|max:100|unique:users',
            'password'  =>  'required|string|min:8|confirmed',
        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create user
        $user = User::create($validatedData);

        return redirect()->route('login')->with('success', 'You are registerd. You can now log in.');
    }
}
