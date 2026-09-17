<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterCompanyRequest $request): RedirectResponse
    {
        $administrator = DB::transaction(function () use ($request): User {
            $company = Company::create([
                'name' => $request->validated('company_name'),
                'cuit' => $request->validated('cuit'),
                'address' => $request->validated('address'),
                'phone' => $request->validated('phone'),
            ]);

            $administrator = User::create([
                'company_id' => $company->id,
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password'),
            ]);

            $administrator->assignRole('Administrador');

            return $administrator;
        });

        Auth::login($administrator);
        $request->session()->regenerate();

        return to_route('dashboard');
    }
}
