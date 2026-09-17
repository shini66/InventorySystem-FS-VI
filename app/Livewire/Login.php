<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    /**
     * @var array<string, list<string>>
     */
    protected array $rules = [
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ];

    public function login(): void
    {
        $credentials = $this->validate();

        if (! Auth::attempt($credentials)) {
            $this->addError('email', 'Las credenciales proporcionadas no son válidas.');

            return;
        }

        request()->session()->regenerate();

        $this->redirectRoute(self::redirectRouteName(), navigate: true);
    }

    public static function redirectRouteName(): string
    {
        return Auth::user()?->hasRole('Operario')
            ? 'movements.index'
            : 'dashboard';
    }

    public function render(): View
    {
        return view('livewire.login');
    }
}
