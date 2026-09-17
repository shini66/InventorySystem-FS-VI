<?php

use App\Livewire\Login;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('login component renders correctly', function (): void {
    Livewire::test(Login::class)
        ->assertViewIs('livewire.login')
        ->assertViewHas('email', '')
        ->assertViewHas('password', '');
});

test('login via HTTP test with session', function (string $role, string $expectedRoute): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
    $user->assignRole($role);

    $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ])->assertRedirect(route($expectedRoute));

    $this->assertAuthenticatedAs($user);
})->with([
    'administrador redirects to dashboard' => ['Administrador', 'dashboard'],
    'operario redirects to movements.index' => ['Operario', 'movements.index'],
]);

test('invalid credentials show validation error', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
    $user->assignRole('Administrador');

    Livewire::test(Login::class)
        ->set('email', 'test@example.com')
        ->set('password', 'wrongpassword')
        ->call('login')
        ->assertHasErrors(['email' => 'Las credenciales proporcionadas no son válidas.'])
        ->assertNoRedirect();

    expect(Auth::check())->toBeFalse();
});

test('empty credentials show validation errors', function (): void {
    Livewire::test(Login::class)
        ->set('email', '')
        ->set('password', '')
        ->call('login')
        ->assertHasErrors(['email', 'password']);
});

test('invalid email format shows validation error', function (): void {
    Livewire::test(Login::class)
        ->set('email', 'not-an-email')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors(['email']);
});

test('non-existent user shows validation error', function (): void {
    Livewire::test(Login::class)
        ->set('email', 'nonexistent@example.com')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors(['email' => 'Las credenciales proporcionadas no son válidas.'])
        ->assertNoRedirect();

    expect(Auth::check())->toBeFalse();
});

test('session regenerates on successful login', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
    $user->assignRole('Administrador');

    $originalSessionId = session()->getId();

    $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    expect(session()->getId())->not->toBe($originalSessionId);
});

test('redirect route name returns correct route for role', function (): void {
    $administrator = User::factory()->create();
    $administrator->assignRole('Administrador');

    $operario = User::factory()->create();
    $operario->assignRole('Operario');

    Auth::login($administrator);
    expect(Login::redirectRouteName())->toBe('dashboard');

    Auth::login($operario);
    expect(Login::redirectRouteName())->toBe('movements.index');

    Auth::logout();
    expect(Login::redirectRouteName())->toBe('dashboard');
});

test('login form submits via wire:submit', function (): void {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);
    $user->assignRole('Administrador');

    $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});
