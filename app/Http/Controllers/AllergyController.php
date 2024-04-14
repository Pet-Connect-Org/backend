<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    /**
     * @OA\Post(
     *     path="/allergies",
     *     security={{"bearerAuth":{}}},
     * 
     *     tags={"Allergies"},
     *     summary="Create a new allergy",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"description", "medical_record_id"},
     *             @OA\Property(property="description", type="string", example="Peanut allergy"),
     *             @OA\Property(property="medical_record_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Allergy created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OK."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="description", type="string", example="Peanut allergy"),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create allergy",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed.")
     *         )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $allergy = Allergy::create([
            'description' => $request->input('description'),
            'medical_record_id' => $request->input("medical_record_id")
        ]);

        if ($allergy) {
            return response()->json([
                'data' => $allergy,
                'message' => 'OK.'
            ], 201);
        }
        return response()->json([
            'message' => 'Failed.'
        ], 500);
    }


    /**
     * @OA\Put(
     *     path="/allergies/{id}",
     *     tags={"Allergies"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Update an existing allergy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Allergy ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"description", "medical_record_id"},
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="medical_record_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Allergy updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Allergy updated successfully."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="description", type="string", example="Peanut allergy"),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Allergy not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Allergy not found.")
     *         )
     *     )
     * )
     */
    public function update(String $id, Request $request)
    {
        $allergy = Allergy::find($id);
        if (!$allergy) {
            return response()->json([
                'message' => 'Allergy not found.'
            ], 404);
        }

        $allergy->update([
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'data' => $allergy->refresh(),
            'message' => 'Allergy updated successfully.'
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/allergies/{id}",
     *     security={{"bearerAuth":{}}},
     * 
     *     tags={"Allergies"},
     *     summary="Delete an allergy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Allergy ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Allergy deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Allergy deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Allergy not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Allergy not found.")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $allergy = Allergy::find($id);
        if (!$allergy) {
            return response()->json([
                'message' => 'Allergy not found.'
            ], 404);
        }

        $allergy->delete();

        return response()->json([
            'message' => 'Allergy deleted successfully.'
        ], 200);
    }
}
