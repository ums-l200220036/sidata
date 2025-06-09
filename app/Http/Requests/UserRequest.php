<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $this->isMethod('PUT');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $isUpdate
                    ? Rule::unique('users', 'email')->ignore($userId)
                    : Rule::unique('users', 'email'),
            ],
            'role' => ['required', 'string', Rule::in(['admin', 'opd', 'kecamatan', 'kelurahan'])],
        ];

        // --- Perbaikan Fokus pada Password dan Konfirmasi Password ---
        // Jika ini operasi update:
        if ($isUpdate) {
            $rules['password'] = ['nullable', 'string', 'min:8']; // 'confirmed' DIHAPUS DARI SINI
            $rules['password_confirmation'] = ['nullable', 'string', 'min:8'];

            // Tambahkan rule 'required_if' dan 'same' hanya JIKA password DIISI
            // Ini akan membuat password_confirmation wajib dan harus sama jika password diisi
            $rules['password'][] = Rule::requiredIf(fn () => $this->filled('password_confirmation'));
            $rules['password_confirmation'][] = 'same:password'; // Cek kecocokan dengan password
            $rules['password_confirmation'][] = Rule::requiredIf(fn () => $this->filled('password')); // Konfirmasi wajib jika password diisi

        } else { // Jika ini operasi tambah (create)
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed']; // Untuk create, confirmed tetap ada
            $rules['password_confirmation'] = ['required', 'string', 'min:8'];
        }
        // --- Akhir Perbaikan Fokus pada Password ---


        // Aturan kondisional untuk opd_id dan wilayah_id berdasarkan role (TETAP SAMA)
        $rules['opd_id'] = ['nullable'];
        $rules['wilayah_id'] = ['nullable'];

        if ($this->input('role') === 'opd') {
            $rules['opd_id'] = ['required', 'exists:opd,id'];
        } elseif ($this->input('role') === 'kecamatan') {
            $rules['wilayah_id'] = [
                'required',
                Rule::exists('wilayah', 'id')->where(function ($query) {
                    $query->whereNull('kelurahan');
                }),
            ];
            $rules['opd_id'] = ['nullable', 'exists:opd,id'];
        } elseif ($this->input('role') === 'kelurahan') {
            $rules['wilayah_id'] = [
                'required',
                Rule::exists('wilayah', 'id')->where(function ($query) {
                    $query->whereNotNull('kelurahan');
                }),
            ];
            $rules['opd_id'] = ['nullable'];
        } elseif ($this->input('role') === 'admin') {
            $rules['opd_id'] = ['nullable'];
            $rules['wilayah_id'] = ['nullable'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal :min karakter.',
            // Hapus 'password.confirmed' karena sekarang ditangani dengan 'same:password'
            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi jika kata sandi baru diisi.',
            'password_confirmation.min' => 'Konfirmasi kata sandi minimal :min karakter.',
            'password_confirmation.same' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi.', // Tambahkan pesan ini
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',

            'opd_id.required' => 'Organisasi Perangkat Daerah wajib dipilih untuk role ini.',
            'opd_id.exists' => 'Organisasi Perangkat Daerah yang dipilih tidak valid.',
            'wilayah_id.required' => 'Wilayah wajib dipilih untuk role ini.',
            'wilayah_id.exists' => 'Wilayah yang dipilih tidak valid atau tidak sesuai dengan role.',
        ];
    }
}