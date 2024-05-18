<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** 
 * @OA\Schema(
 *       required={"email", "password", "confirmPassword", "name", "sex", "birthday", "address"},
 *       @OA\Property(property="email", type="string", format="email", example="buithuyngoc1@gmail.com"),
 *       @OA\Property(property="password", type="string", minLength=6, maxLength=30, example="buithuyngoc2003"),
 *       @OA\Property(property="confirmPassword", type="string", example="buithuyngoc2003"),
 *       @OA\Property(property="name", type="string", example="Bui Thuy Ngoc"),
 *       @OA\Property(property="sex", type="string", enum={"male", "female"}, example="female"),
 *       @OA\Property(property="birthday", type="string", format="date", example="13/02/2003"),
 *       @OA\Property(property="address", type="string", example="Ha Dong"),     
 * )
 */

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => [
                'required',
                'min:6',
                'max:30',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
            'confirmPassword' => ['required', 'same:password'],
            'name' => 'required',
            'sex' => ['required', Rule::in(['male', 'female'])],
            'birthday' => 'required',
            'address' => 'required',
        ];
    }
}
