<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medicine_code' => 'nullable|string|unique:medicines,medicine_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', config('medicine.categories')),
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'batch_number' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('medicine.name')]),
            'category.required' => __('validation.required', ['attribute' => __('medicine.category')]),
            'category.in' => __('validation.in', ['attribute' => __('medicine.category')]),
            'unit_price.required' => __('validation.required', ['attribute' => __('medicine.unit_price')]),
            'unit_price.numeric' => __('validation.numeric', ['attribute' => __('medicine.unit_price')]),
            'stock_quantity.required' => __('validation.required', ['attribute' => __('medicine.stock_quantity')]),
            'minimum_stock.required' => __('validation.required', ['attribute' => __('medicine.minimum_stock')]),
            'expiry_date.after' => __('validation.after', ['attribute' => __('medicine.expiry_date'), 'date' => 'today']),
        ];
    }
}
