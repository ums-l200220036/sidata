<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndikatorRequest extends FormRequest // Nama request: IndikatorRequest
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
        $indikatorId = $this->route('indikator')?->id; // Menggunakan 'indikator' untuk route parameter
        $isUpdate = $this->isMethod('PUT');

        return [
            'nama_indikator' => [ // Nama kolom: nama_indikator
                'required',
                'string',
                'max:255',
                // Nama indikator harus unik per OPD
                Rule::unique('indikator')->where(function ($query) { // Nama tabel: indikator
                    return $query->where('opd_id', $this->input('opd_id'));
                })->ignore($indikatorId), // Ignore current ID on update
            ],
            'opd_id' => ['required', 'exists:opd,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_indikator.required' => 'Nama indikator wajib diisi.',
            'nama_indikator.unique' => 'Nama indikator ini sudah ada untuk OPD yang sama.',
            'opd_id.required' => 'OPD wajib dipilih.',
            'opd_id.exists' => 'OPD yang dipilih tidak valid.',
        ];
    }
}