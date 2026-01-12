<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => __('validation.required', ['attribute' => 'action']),
            'action.in' => __('validation.in', ['attribute' => 'action']),
            'quantity.required' => __('validation.required', ['attribute' => 'quantity']),
            'quantity.integer' => __('validation.integer', ['attribute' => 'quantity']),
            'quantity.min' => __('validation.min.numeric', ['attribute' => 'quantity', 'min' => 1]),
        ];
    }
}
