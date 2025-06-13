<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndikatorRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $indikatorId = $this->route('indikator')?->id;
        // $isUpdate = $this->isMethod('PUT'); // Variabel ini tidak digunakan, bisa dihapus jika tidak diperlukan.

        return [
            'nama_indikator' => [
                'required',
                'string',
                'max:255',
                // Nama indikator harus unik per OPD
                Rule::unique('indikator')->where(function ($query) {
                    return $query->where('opd_id', $this->input('opd_id'));
                })->ignore($indikatorId),
            ],
            // Pastikan nama tabel di sini sesuai dengan database Anda (opd atau opds)
            'opd_id' => ['required', 'exists:opd,id'], // Menggunakan 'opds' (plural) sesuai konvensi Laravel
            // --- HANYA tambahkan dimensi_label di sini ---
            'dimensi_label' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_indikator.required' => 'Nama indikator wajib diisi.',
            'nama_indikator.unique' => 'Nama indikator ini sudah ada untuk OPD yang sama.',
            'opd_id.required' => 'OPD wajib dipilih.',
            'opd_id.exists' => 'OPD yang dipilih tidak valid.',
            // --- Pesan kustom untuk dimensi_label ---
            'dimensi_label.required' => 'Label Dimensi wajib diisi.',
            'dimensi_label.string' => 'Label Dimensi harus berupa teks.',
            'dimensi_label.max' => 'Label Dimensi tidak boleh lebih dari 255 karakter.',
        ];
    }
}