<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** 
 * @OA\Schema(
 *       required={"name", "sex", "birthday", "address"},
 *       @OA\Property(property="name", type="string", example="Bui Thuy Ngoc"),
 *       @OA\Property(property="sex", type="string", enum={"male", "female"}, example="female"),
 *       @OA\Property(property="birthday", type="string", format="date", example="13-02-2003"),
 *       @OA\Property(property="address", type="string", example="Ha Dong"),     
 * )
 */

class UpdateUserRequest extends FormRequest
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
            'name' => 'required',
            'sex' => ['required', Rule::in(['male', 'female'])],
            'birthday' => 'required',
            'address' => 'required',
        ];
    }
}
