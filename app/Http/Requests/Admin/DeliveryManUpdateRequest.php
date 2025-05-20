<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Password;

/**
 * @property int id
 * @property array|string title
 * @property array translations
 * @property string|null|array description
 * @property string bonus_type
 * @property float bonus_amount
 * @property float minimum_add_amount
 * @property float maximum_bonus_amount
 * @property Carbon|null start_date
 * @property Carbon|null end_date
 * @property bool status
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 * @property array lang
 */
class DeliveryManUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'identity_number' => 'required|max:30',
            'email' => 'required|unique:delivery_men,email,'.$this->id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:delivery_men,phone,'.$this->id,
            'vehicle_id' => 'required',
            'earning' => 'required',
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised(),
                function ($attribute, $value, $fail) {
                    if (strpos($value, ' ') !== false) {
                        $fail('The :attribute cannot contain white spaces.');
                    }
                },
            ],
            'aadhar_number' => 'nullable|digits:12',
            'aadhar_image' => 'nullable|image|max:2000',
            'pan_number' => 'nullable|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/',
            'pan_image' => 'nullable|image|max:2000',
            'bike_registration_number' => 'nullable|string|max:20',
            'bike_registration_image' => 'nullable|image|max:2000',
            'bike_insurance_image' => 'nullable|image|max:2000',
            'driving_license_number' => 'nullable|string|max:20',
            'driving_license_image' => 'nullable|image|max:2000',
            'bank_account_number' => 'nullable|numeric',
            'bank_name' => 'nullable|string|max:191',
            'ifsc_code' => 'nullable|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'account_type' => 'nullable|in:savings,current',
        ];
    }

    public function messages(): array
    {
        return [
            'f_name.required' => translate('messages.first_name_is_required'),
            'vehicle_id.required' => translate('messages.select_a_vehicle'),
            'earning.required' => translate('messages.select_dm_type'),
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
            'aadhar_number.digits' => translate('messages.aadhar_number_must_be_12_digits'),
            'pan_number.regex' => translate('messages.invalid_pan_format'),
            'ifsc_code.regex' => translate('messages.invalid_ifsc_format'),
            'account_type.in' => translate('messages.invalid_account_type'),
        ];
    }
}
