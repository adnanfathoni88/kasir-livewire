<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Register extends Component
{
    public $name, $email, $password;

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }

    public function register()
    {
        // dd('register');
        $this->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ],
            [
                'name.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 6 karakter'
            ]
        );


        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        session()->flash('success', 'Registrasi berhasil, Silahkan Login');
        return redirect('/login');
    }
}
