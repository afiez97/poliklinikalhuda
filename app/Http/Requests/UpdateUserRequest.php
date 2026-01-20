<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('users.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->route('user');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$userId, 'alpha_dash'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^01[0-9]{8,9}$/'],
            'status' => ['required', 'in:'.implode(',', config('security.user_statuses', ['active', 'inactive', 'suspended', 'pending']))],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,name'],
            'mfa_required' => ['nullable', 'boolean'],
        ];

        // Password is optional on update, but if provided must meet requirements
        if ($this->filled('password')) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(config('security.password.min_length', 12))
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'username' => 'nama pengguna',
            'email' => 'emel',
            'phone' => 'nombor telefon',
            'password' => 'kata laluan',
            'password_confirmation' => 'pengesahan kata laluan',
            'status' => 'status',
            'roles' => 'peranan',
            'mfa_required' => 'MFA diperlukan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama diperlukan.',
            'username.required' => 'Nama pengguna diperlukan.',
            'username.unique' => 'Nama pengguna sudah digunakan.',
            'username.alpha_dash' => 'Nama pengguna hanya boleh mengandungi huruf, nombor, sengkang dan garis bawah.',
            'email.required' => 'Emel diperlukan.',
            'email.email' => 'Sila masukkan format emel yang sah.',
            'email.unique' => 'Emel sudah digunakan.',
            'phone.regex' => 'Format nombor telefon tidak sah. Contoh: 0123456789',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
            'password.min' => 'Kata laluan mestilah sekurang-kurangnya :min aksara.',
            'status.required' => 'Status diperlukan.',
            'status.in' => 'Status tidak sah.',
            'roles.required' => 'Sekurang-kurangnya satu peranan diperlukan.',
            'roles.*.exists' => 'Peranan yang dipilih tidak sah.',
        ];
    }
}
