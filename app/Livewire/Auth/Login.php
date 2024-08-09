<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $layout = 'layouts.auth'; // auth layout

    public $email, $password;

    public function render()
    {
        return view('livewire.auth.login')->layout($this->layout);
    }

    public function login()
    {
        $this->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'password.required' => 'Password tidak boleh kosong'
            ]
        );

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            if (Auth::user()->role == 'admin') {
                return redirect('/admin/produk');
            } else {
                return redirect('/');
            }
        }


        session()->flash('error', 'Email atau password salah');
        return $this->redirect('/login');
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('logout', 'Logout berhasil');
        return redirect('/login');
    }
    public function register()
    {
        return view('livewire.auth.login')->layout($this->layout);
    }
}
