<?php

namespace App\Http\Requests\PetProfile;

use Illuminate\Foundation\Http\FormRequest;

class CreaatePetProfileRequest extends FormRequest
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
            //
            'name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'sex' => 'required|string|in:male,female',
            'description' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'pet_type_id' => 'required|integer|exists:pet_types,id',

            'favoriteFood' => 'required|string|max:255',
            'isFriendlyWithDog' => 'required',
            'isFriendlyWithCat' => 'required',
            'isCleanProperly' => 'required',
            'isHyperactive' => 'required',
            'isFriendlyWithKid' => 'required',
            'isShy' => 'required',
        ];
    }
}
