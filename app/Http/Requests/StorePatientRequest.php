<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ic_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('patients', 'ic_number')->whereNull('deleted_at'),
                'required_if:id_type,ic',
            ],
            'passport_number' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('patients', 'passport_number')->whereNull('deleted_at'),
                'required_if:id_type,passport',
            ],
            'id_type' => 'required|in:ic,passport,military,police,birth_cert,other',
            'name' => 'required|string|max:150',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'nationality' => 'nullable|string|max:50',
            'race' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'occupation' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'phone_alt' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'postcode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'emergency_name' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:20',
            'emergency_relationship' => 'nullable|string|max:50',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string|max:1000',
            'chronic_diseases' => 'nullable|string|max:1000',
            'current_medications' => 'nullable|string|max:1000',
            'has_panel' => 'boolean',
            'panel_company' => 'nullable|string|max:100',
            'panel_member_id' => 'nullable|string|max:50',
            'panel_expiry_date' => 'nullable|date',
            'pdpa_consent' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ic_number.unique' => __('No. Kad Pengenalan sudah wujud dalam sistem.'),
            'passport_number.unique' => __('No. Pasport sudah wujud dalam sistem.'),
            'ic_number.required_if' => __('No. Kad Pengenalan diperlukan untuk jenis ID Kad Pengenalan.'),
            'passport_number.required_if' => __('No. Pasport diperlukan untuk jenis ID Pasport.'),
        ];
    }
}
