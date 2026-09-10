<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', Rule::in(['entrada', 'salida'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'required_if:type,entrada', 'string', 'max:120'],
            'reason' => ['nullable', 'required_if:type,salida', 'string', 'max:255'],
            'moved_at' => ['required', 'date'],
        ];
    }
}
