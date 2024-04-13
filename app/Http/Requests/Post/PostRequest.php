<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"content"},
 *     @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
*     @OA\Property(
 *         property="images",
 *         type="array",
 *         description="An array of image URLs",
 *         @OA\Items(
 *             type="string",
 *             format="url",
 *             example="http://example.com/image.jpg"
 *         )
 *     ),
 *     @OA\Property(property="latitude", type="number", example="20"),
 *     @OA\Property(property="longitude", type="number", example="106")
 * )
 */
class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return boolean
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
            'content' => ['required'],
        ];
    }
}
