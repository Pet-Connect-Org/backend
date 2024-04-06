<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetType\CreatePetTypeRequest;
use App\Models\PetType;
use Illuminate\Http\Request;

class PetTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pet_type",
     *     tags={"Pet Type"},
     *     summary="List all pet types",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all pet types",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="List all pet types success."),
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="type", type="string", example="dog"),
     *                      @OA\Property(property="image", type="string", example="image_url"),
     *                      @OA\Property(property="name", type="string", example="German Shepherd"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-26T12:00:00Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-26T12:00:00Z")
     *                  )
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */

    public function listAll(Request $request)
    {
        $petList = PetType::all();
        return response()->json([
            "message" => "List all pet type success.",
            "data" => $petList
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/pet_type",
     *     tags={"Pet Type"},
     *     summary="Create a new pet type",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CreatePetTypeRequest"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pet type created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pet type created successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="image", type="string", example="image_url"),
     *                 @OA\Property(property="type", type="string", example="dog"),
     *                 @OA\Property(property="name", type="string", example="German Shepherd"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-06T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-06T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */

    public function create(CreatePetTypeRequest $request)
    {
        $pet = PetType::create([
            'image' => $request->input('image'),
            'name' => $request->input('name'),
            'type' => $request->input('type')
        ]);
        if ($pet) {
            return response()->json([
                "message" => "Create new pet type success.",
                "data" => $pet
            ], 201);
        } else {
            return response()->json([
                "message" => "Failed to create new pet type.",
            ], 400);
        }
    }
}
