<?php

namespace App\Http\Controllers;

use App\Models\WeightHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WeightHistoryController extends Controller
{

    /**
     * @OA\Post(
     *     path="/weight_history",
     *     tags={"Weight History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Create a new weight history record",
     *     operationId="storeWeightHistory",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass weight history data",
     *         @OA\JsonContent(
     *             required={"medical_record_id","weight","time"},
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="weight", type="number", format="float", example=20.5),
     *             @OA\Property(property="description", type="string", example="Healthy weight"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-04-14"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Weight history created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Weight history created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="weight", type="number", format="float", example=20.5),
     *                 @OA\Property(property="description", type="string", example="Healthy weight"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation errors"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="weight", type="array",
     *                     @OA\Items(type="string", example="The weight field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'required|integer|exists:medical_records,id',
            'weight' => 'required|numeric',
            'description' => 'string|nullable',
            'time' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $weightHistory = WeightHistory::create($validator->validated());

        return response()->json([
            'message' => 'Weight history created successfully',
            'data' => $weightHistory
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/weight_history/{id}",
     *     tags={"Weight History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Update an existing weight history record",
     *     operationId="updateWeightHistory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Weight History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass weight history data",
     *         @OA\JsonContent(
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="weight", type="number", format="float", example=22.0),
     *             @OA\Property(property="description", type="string", example="Gained weight after diet"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-05-14T12:00:00Z"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Weight history updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Weight history updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="weight", type="number", format="float", example=20.5),
     *                 @OA\Property(property="description", type="string", example="Healthy weight"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *             )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Weight history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Weight history not found")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'integer|exists:medical_records,id',
            'weight' => 'numeric',
            'description' => 'string|nullable',
            'time' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $weightHistory = WeightHistory::find($id);
        if (!$weightHistory) {
            return response()->json(['message' => 'Weight history not found'], 404);
        }

        $weightHistory->update($validator->validated());

        return response()->json([
            'message' => 'Weight history updated successfully',
            'data' => $weightHistory->refresh()
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/weight_history/{id}",
     *     tags={"Weight History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Delete a weight history record",
     *     operationId="deleteWeightHistory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Weight History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Weight history deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Weight history deleted successfully"),
     *              @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="weight", type="number", format="float", example=20.5),
     *                 @OA\Property(property="description", type="string", example="Healthy weight"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Weight history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Weight history not found")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $weightHistory = WeightHistory::find($id);
        if (!$weightHistory) {
            return response()->json(['message' => 'Weight history not found'], 404);
        }

        $weightHistory->delete();

        return response()->json(['message' => 'Weight history deleted successfully'], 200);
    }
}
