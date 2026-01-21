<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEncounterRequest extends FormRequest
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
            'patient_id' => ['required', 'exists:patients,id'],
            'patient_visit_id' => ['nullable', 'exists:patient_visits,id'],
            'doctor_id' => ['required', 'exists:staff,id'],
            'template_id' => ['nullable', 'exists:clinical_templates,id'],
            'chief_complaint' => ['required', 'string', 'max:1000'],
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

            // Vital signs (optional, can be added later)
            'vital_signs' => ['nullable', 'array'],
            'vital_signs.temperature' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'vital_signs.pulse_rate' => ['nullable', 'integer', 'min:20', 'max:250'],
            'vital_signs.respiratory_rate' => ['nullable', 'integer', 'min:5', 'max:60'],
            'vital_signs.systolic_bp' => ['nullable', 'integer', 'min:50', 'max:300'],
            'vital_signs.diastolic_bp' => ['nullable', 'integer', 'min:30', 'max:200'],
            'vital_signs.spo2' => ['nullable', 'integer', 'min:50', 'max:100'],
            'vital_signs.weight' => ['nullable', 'numeric', 'min:0.5', 'max:500'],
            'vital_signs.height' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'vital_signs.blood_glucose' => ['nullable', 'numeric', 'min:1', 'max:50'],
            'vital_signs.pain_score' => ['nullable', 'integer', 'min:0', 'max:10'],
            'vital_signs.pain_location' => ['nullable', 'string', 'max:255'],
            'vital_signs.notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'patient_id' => 'pesakit',
            'patient_visit_id' => 'lawatan pesakit',
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
            'vital_signs.temperature' => 'suhu badan',
            'vital_signs.pulse_rate' => 'kadar nadi',
            'vital_signs.respiratory_rate' => 'kadar pernafasan',
            'vital_signs.systolic_bp' => 'tekanan darah sistolik',
            'vital_signs.diastolic_bp' => 'tekanan darah diastolik',
            'vital_signs.spo2' => 'ketepuan oksigen',
            'vital_signs.weight' => 'berat badan',
            'vital_signs.height' => 'ketinggian',
            'vital_signs.blood_glucose' => 'paras glukosa darah',
            'vital_signs.pain_score' => 'skor kesakitan',
            'vital_signs.pain_location' => 'lokasi kesakitan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Sila pilih pesakit.',
            'patient_id.exists' => 'Pesakit tidak sah.',
            'doctor_id.required' => 'Sila pilih doktor.',
            'doctor_id.exists' => 'Doktor tidak sah.',
            'chief_complaint.required' => 'Sila isi aduan utama pesakit.',
            'chief_complaint.max' => 'Aduan utama tidak boleh melebihi :max aksara.',
            'follow_up_date.after' => 'Tarikh susulan mestilah selepas hari ini.',
            'referral_specialty.required_if' => 'Sila nyatakan kepakaran rujukan.',
        ];
    }
}
