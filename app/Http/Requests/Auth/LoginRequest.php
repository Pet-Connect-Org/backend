<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/** 
 * @OA\Schema(
 *       required={"email", "password"},
 *       @OA\Property(property="email", type="string", format="email", example="buithuyngoc1@gmail.com"),
 *       @OA\Property(property="password", type="string", minLength=6, maxLength=30, example="buithuyngoc2003"),
 * )
*/

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:filter'],
            'password' => ['required'],
        ];
    }
}
