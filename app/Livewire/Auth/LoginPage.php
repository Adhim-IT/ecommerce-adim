<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LoginPage extends Component
{

    public string $email = '';
    public string $password = '';

    public function login() {
        $response = Http::post(config('app.url') . '/api/v1/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        if ($response->successful()) {
            session(['jwt_token' => $response->json()['token']]);
            return redirect()->route('home');
        } else {
            session()->flash('error', 'Login failed. Please check your credentials.');
        }
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}