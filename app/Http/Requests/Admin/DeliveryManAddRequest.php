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
class DeliveryManAddRequest extends FormRequest
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
            'email' => 'required|unique:delivery_men',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:delivery_men',
            'zone_id' => 'required',
            'earning' => 'required',
            'vehicle_id' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised(),
                function ($attribute, $value, $fail) {
                    if (strpos($value, ' ') !== false) {
                        $fail('The :attribute cannot contain white spaces.');
                    }
                },
            ],
            'aadhar_number' => 'required|digits:12',
            'aadhar_image' => 'required|image|max:2000',
            'pan_number' => 'required|regex:/[A-Z]{5}[0-9]{4}[A-Z]{1}/',
            'pan_image' => 'required|image|max:2000',
            'bike_registration_number' => 'required|string|max:20',
            'bike_registration_image' => 'required|image|max:2000',
            'bike_insurance_image' => 'required|image|max:2000',
            'driving_license_number' => 'required|string|max:20',
            'driving_license_image' => 'required|image|max:2000',
            'bank_account_number' => 'required|numeric',
            'bank_name' => 'required|string|max:191',
            'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'account_type' => 'required|in:savings,current',
        ];
    }

    public function messages(): array
    {
        return [
            'f_name.required' => translate('messages.first_name_is_required'),
            'zone_id.required' => translate('messages.select_a_zone'),
            'vehicle_id.required' => translate('messages.select_a_vehicle'),
            'earning.required' => translate('messages.select_dm_type'),
            'password.required' => translate('The password is required'),
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
            'password.custom' => translate('The password cannot contain white spaces.'),
            'aadhar_number.required' => translate('messages.aadhar_number_required'),
            'aadhar_number.digits' => translate('messages.aadhar_number_must_be_12_digits'),
            'aadhar_image.required' => translate('messages.aadhar_image_required'),
            'pan_number.required' => translate('messages.pan_number_required'),
            'pan_number.regex' => translate('messages.invalid_pan_format'),
            'pan_image.required' => translate('messages.pan_image_required'),
            'bike_registration_number.required' => translate('messages.bike_registration_number_required'),
            'bike_registration_image.required' => translate('messages.bike_registration_image_required'),
            'bike_insurance_image.required' => translate('messages.bike_insurance_image_required'),
            'driving_license_number.required' => translate('messages.driving_license_number_required'),
            'driving_license_image.required' => translate('messages.driving_license_image_required'),
            'bank_account_number.required' => translate('messages.bank_account_number_required'),
            'bank_name.required' => translate('messages.bank_name_required'),
            'ifsc_code.required' => translate('messages.ifsc_code_required'),
            'ifsc_code.regex' => translate('messages.invalid_ifsc_format'),
            'account_type.required' => translate('messages.account_type_required'),
            'account_type.in' => translate('messages.invalid_account_type'),
        ];
    }
}
