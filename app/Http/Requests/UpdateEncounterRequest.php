<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEncounterRequest extends FormRequest
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
            'doctor_id' => ['sometimes', 'exists:staff,id'],
            'template_id' => ['nullable', 'exists:clinical_templates,id'],
            'chief_complaint' => ['sometimes', 'required', 'string', 'max:1000'],
            'history_present_illness' => ['nullable', 'string', 'max:5000'],
            'subjective' => ['nullable', 'string', 'max:10000'],
            'objective' => ['nullable', 'string', 'max:10000'],
            'assessment' => ['nullable', 'string', 'max:10000'],
            'plan' => ['nullable', 'string', 'max:10000'],
            'clinical_notes' => ['nullable', 'string', 'max:10000'],
            'private_notes' => ['nullable', 'string', 'max:5000'],
            'follow_up_date' => ['nullable', 'date', 'after:today'],
            'follow_up_instructions' => ['nullable', 'string', 'max:2000'],
            'needs_referral' => ['boolean'],
            'referral_specialty' => ['nullable', 'required_if:needs_referral,true', 'string', 'max:100'],
            'referral_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'doctor_id' => 'doktor',
            'template_id' => 'templat',
            'chief_complaint' => 'aduan utama',
            'history_present_illness' => 'sejarah penyakit semasa',
            'subjective' => 'subjektif',
            'objective' => 'objektif',
            'assessment' => 'penilaian',
            'plan' => 'pelan rawatan',
            'clinical_notes' => 'nota klinikal',
            'private_notes' => 'nota peribadi',
            'follow_up_date' => 'tarikh susulan',
            'follow_up_instructions' => 'arahan susulan',
            'needs_referral' => 'perlu rujukan',
            'referral_specialty' => 'kepakaran rujukan',
            'referral_notes' => 'nota rujukan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'doctor_id.exists' => 'Doktor tidak sah.',
            'chief_complaint.required' => 'Sila isi aduan utama pesakit.',
            'chief_complaint.max' => 'Aduan utama tidak boleh melebihi :max aksara.',
            'follow_up_date.after' => 'Tarikh susulan mestilah selepas hari ini.',
            'referral_specialty.required_if' => 'Sila nyatakan kepakaran rujukan.',
        ];
    }
}
