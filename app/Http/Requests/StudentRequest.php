<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('ADMIN');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'admission_session_id' => 'required',
            'name' => 'required',
            'department' => 'sometimes',
            'roll' => 'sometimes',
            'registration' => ['required', Rule::unique('App\Student')->ignore($this->student)],
            'hall' => 'sometimes',
        ];
    }
}
