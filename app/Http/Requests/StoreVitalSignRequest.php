<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalSignRequest extends FormRequest
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
            'temperature' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'pulse_rate' => ['nullable', 'integer', 'min:20', 'max:250'],
            'respiratory_rate' => ['nullable', 'integer', 'min:5', 'max:60'],
            'systolic_bp' => ['nullable', 'integer', 'min:50', 'max:300'],
            'diastolic_bp' => ['nullable', 'integer', 'min:30', 'max:200'],
            'spo2' => ['nullable', 'integer', 'min:50', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:0.5', 'max:500'],
            'height' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'blood_glucose' => ['nullable', 'numeric', 'min:1', 'max:50'],
            'pain_score' => ['nullable', 'integer', 'min:0', 'max:10'],
            'pain_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'temperature' => 'suhu badan',
            'pulse_rate' => 'kadar nadi',
            'respiratory_rate' => 'kadar pernafasan',
            'systolic_bp' => 'tekanan darah sistolik',
            'diastolic_bp' => 'tekanan darah diastolik',
            'spo2' => 'ketepuan oksigen (SpO2)',
            'weight' => 'berat badan',
            'height' => 'ketinggian',
            'blood_glucose' => 'paras glukosa darah',
            'pain_score' => 'skor kesakitan',
            'pain_location' => 'lokasi kesakitan',
            'notes' => 'nota',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'temperature.min' => 'Suhu badan mestilah sekurang-kurangnya :min°C.',
            'temperature.max' => 'Suhu badan tidak boleh melebihi :max°C.',
            'pulse_rate.min' => 'Kadar nadi mestilah sekurang-kurangnya :min bpm.',
            'pulse_rate.max' => 'Kadar nadi tidak boleh melebihi :max bpm.',
            'respiratory_rate.min' => 'Kadar pernafasan mestilah sekurang-kurangnya :min/min.',
            'respiratory_rate.max' => 'Kadar pernafasan tidak boleh melebihi :max/min.',
            'systolic_bp.min' => 'Tekanan darah sistolik mestilah sekurang-kurangnya :min mmHg.',
            'systolic_bp.max' => 'Tekanan darah sistolik tidak boleh melebihi :max mmHg.',
            'diastolic_bp.min' => 'Tekanan darah diastolik mestilah sekurang-kurangnya :min mmHg.',
            'diastolic_bp.max' => 'Tekanan darah diastolik tidak boleh melebihi :max mmHg.',
            'spo2.min' => 'Ketepuan oksigen mestilah sekurang-kurangnya :min%.',
            'spo2.max' => 'Ketepuan oksigen tidak boleh melebihi :max%.',
            'weight.min' => 'Berat badan mestilah sekurang-kurangnya :min kg.',
            'weight.max' => 'Berat badan tidak boleh melebihi :max kg.',
            'height.min' => 'Ketinggian mestilah sekurang-kurangnya :min cm.',
            'height.max' => 'Ketinggian tidak boleh melebihi :max cm.',
            'blood_glucose.min' => 'Paras glukosa darah mestilah sekurang-kurangnya :min mmol/L.',
            'blood_glucose.max' => 'Paras glukosa darah tidak boleh melebihi :max mmol/L.',
            'pain_score.min' => 'Skor kesakitan mestilah sekurang-kurangnya :min.',
            'pain_score.max' => 'Skor kesakitan tidak boleh melebihi :max.',
        ];
    }
}
