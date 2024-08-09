<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{

    public $layout = 'layouts.auth'; // auth layout

    public function render()
    {
        return view('livewire.auth.logout')->layout($this->layout);
    }

    public function logout()
    {
        dd('logout');
        Auth::logout();
        return redirect('/login');
    }
}
