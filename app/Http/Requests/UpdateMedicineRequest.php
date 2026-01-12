<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $medicineId = $this->route('medicine')->id ?? $this->route('medicine');

        return [
            'medicine_code' => 'required|string|unique:medicines,medicine_code,' . $medicineId,
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
            'status' => 'required|in:' . implode(',', config('medicine.statuses')),
        ];
    }

    public function messages(): array
    {
        return [
            'medicine_code.required' => __('validation.required', ['attribute' => __('medicine.medicine_code')]),
            'medicine_code.unique' => __('validation.unique', ['attribute' => __('medicine.medicine_code')]),
            'name.required' => __('validation.required', ['attribute' => __('medicine.name')]),
            'category.required' => __('validation.required', ['attribute' => __('medicine.category')]),
            'category.in' => __('validation.in', ['attribute' => __('medicine.category')]),
            'unit_price.required' => __('validation.required', ['attribute' => __('medicine.unit_price')]),
            'stock_quantity.required' => __('validation.required', ['attribute' => __('medicine.stock_quantity')]),
            'minimum_stock.required' => __('validation.required', ['attribute' => __('medicine.minimum_stock')]),
            'status.required' => __('validation.required', ['attribute' => __('medicine.status')]),
            'status.in' => __('validation.in', ['attribute' => __('medicine.status')]),
        ];
    }
}
