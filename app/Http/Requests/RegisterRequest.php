<?php

namespace App\Http\Requests;

use App\Rules\ValidMail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:75',
            'last_name' => 'required|string|max:75',
            'country_code' => [Rule::in(['EG'])],
            'phone_number' => 'required|unique:users,phone_number|min:10|max:15|regex:/(01)[0-9]{9}/',
            'gender' => [Rule::in(['male', 'female'])],
            'birthdate' => 'required|string|max:75|date_format:YYYY-MM-DD|before:today',
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif',
            'email' => ['required', 'unique:users,email', new ValidMail],
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
//    public function messages()
//    {
//        return [
//            'required' => ['error' => 'blank'],
//            'numeric' => ['error' => "not_a_number"],
//            'phone_number.regex' => ['error' => "invalid"],
//            'in' => ['error' => 'inclusion'],
//            'phone_number.min' => ['string' => ['error' => "too_short", 'count' => ':min']],
//            'phone_number.max' => ['string' => ['error' => "too_long", 'count' => ':max']],
//            'birthdate.date_format' => ['error' => "invalid"],
//            'birthdate.before' => ['error' => "in_the_future"],
//            'avatar.mimes' => ['error' => "invalid_content_type"],
//            'unique' => ['error' => "taken"],
//        ];
//    }

}
