<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperatorRequest;
use App\Http\Requests\UpdateOperatorRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('operators.index', [
            'operators' => User::operatorsForCompany($request->user()->company_id)
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('operators.create', ['operator' => new User]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperatorRequest $request): RedirectResponse
    {
        $operator = User::create([
            'company_id' => $request->user()->company_id,
            ...$request->validated(),
        ]);
        $operator->assignRole('Operario');

        return to_route('operators.index')->with('status', 'Operario creado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $operator): View
    {
        return view('operators.edit', [
            'operator' => $this->operatorForCompany($request, $operator),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperatorRequest $request, User $operator): RedirectResponse
    {
        $operator = $this->operatorForCompany($request, $operator);
        $operator->update(array_filter($request->validated(), fn (mixed $value): bool => $value !== null));

        return to_route('operators.index')->with('status', 'Operario actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $operator): RedirectResponse
    {
        $this->operatorForCompany($request, $operator)->delete();

        return to_route('operators.index')->with('status', 'Operario eliminado.');
    }

    private function operatorForCompany(Request $request, User $operator): User
    {
        return User::operatorsForCompany($request->user()->company_id)->findOrFail($operator->id);
    }
}
