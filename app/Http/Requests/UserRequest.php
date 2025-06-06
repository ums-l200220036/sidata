<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function rules()
    {
        $userId = $this->route('user')?->id;
        $isUpdate = $userId !== null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                $isUpdate
                    ? Rule::unique('users', 'email')->ignore($userId)
                    : Rule::unique('users', 'email'),
            ],
            'password' => $isUpdate ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|in:admin,opd,kecamatan,kelurahan',

            'opd_id' => 'required_if:role,opd,kecamatan,kelurahan|exists:opd,id',

            'wilayah_id' => 'required_if:role,kelurahan,kecamatan|exists:wilayah,id',
        ];
    }


    public function authorize()
    {
        return true;
    }
}
