<?php

namespace App\Http\Requests\Laralum;

use App\StudentEmailApply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentEmailApplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'admission_session_id' => 'required|integer',
            'department_id' => 'required|integer',
            'program_id' => 'required|integer',
            'hall_id' => 'required',
            'global_status_id' => 'sometimes',
            'username' => ['required', Rule::unique('App\StudentEmailApply')->ignore($this->email)],
            'password' => 'sometimes|required|min:8',
            'registration_number' => 'required|alpha_num',
            'first_name' => ['required', 'regex:/^[a-zA-Z\s]*$/'],
            'last_name' => ['required', 'regex:/^[a-zA-Z\s]*$/'],
            'middle_name' => 'sometimes',
            'last_name' => 'sometimes',
            'contact_phone' => ['required', 'regex:/^01[0-9]{9}$/'],
            'existing_email' => ['required', 'email', 'regex:/gmail\.com|yahoo\.com|hotmail\.com/'],
            'id_card' => $this->IdCardRules()
        ];
    }

    /**
     * id card rules will be change depends on create or update
     *
     * @return void
     */
    public function IdCardRules()
    {
        $rules = ['required', 'image', 'max:' . StudentEmailApply::$maxIDUploadSize];
        !$this->email ?: array_unshift($rules, 'sometimes');
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'admission_session_id' => 'Admission Session',
            'department_id' => 'Department',
            'program_id' => 'Program',
            'hall_id' => 'Hall',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.regex' => 'First Name Only Allow Character and Space',
            'last_name.regex' => 'Last Name Only Allow Character and Space',
            'existing_email.regex' => 'Only Gmail/Yahoo/Hotmail Emails Are Allowed',
            'contact_phone.regex' => 'Contact Phone Should Be 11 Digits & Start With 01',
        ];
    }
}
