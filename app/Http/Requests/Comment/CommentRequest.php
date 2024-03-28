<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"content", "post_id"},
 *     @OA\Property(property="content", type="string", example="Bui Thuy Ngoc rat xinh dep=))"),
 *     @OA\Property(property="post_id", type="integer", example="1"),
 * )
 */
class CommentRequest extends FormRequest
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
            'content' => ['required', 'string'],
            'post_id' => ['required'],
        ];
    }
}
