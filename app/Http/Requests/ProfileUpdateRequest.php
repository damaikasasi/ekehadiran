<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^(?=.*[\pL])[\pL\s.,\'-]+$/u'],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'capaian_kerja' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.regex' => 'Nama harus mengandung huruf, tidak boleh mengandung angka, dan tidak boleh hanya karakter simbol.',
            'foto.image' => 'File yang diunggah harus berupa gambar',
            'foto.mimes' => 'Format foto profil harus berupa jpeg, png, jpg, gif, atau svg',
            'foto.max' => 'Ukuran foto profil terlalu besar, maksimal 2MB',
            'foto.uploaded' => 'Ukuran foto profil terlalu besar, maksimal 2MB',
        ];
    }
}
