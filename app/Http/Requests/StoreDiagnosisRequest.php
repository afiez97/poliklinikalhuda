<?php

namespace App\Http\Requests;

use App\Models\Diagnosis;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosisRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'icd10_id' => ['nullable', 'exists:icd10_codes,id'],
            'icd10_code' => ['nullable', 'string', 'max:20'],
            'diagnosis_text' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:'.implode(',', array_keys(Diagnosis::TYPES))],
            'status' => ['required', 'in:'.implode(',', array_keys(Diagnosis::STATUS_OPTIONS))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'icd10_id' => 'kod ICD-10',
            'icd10_code' => 'kod ICD-10',
            'diagnosis_text' => 'diagnosis',
            'type' => 'jenis diagnosis',
            'status' => 'status',
            'notes' => 'nota',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'diagnosis_text.required' => 'Sila isi diagnosis.',
            'diagnosis_text.max' => 'Diagnosis tidak boleh melebihi :max aksara.',
            'type.required' => 'Sila pilih jenis diagnosis.',
            'type.in' => 'Jenis diagnosis tidak sah.',
            'status.required' => 'Sila pilih status diagnosis.',
            'status.in' => 'Status diagnosis tidak sah.',
        ];
    }
}
