<?php

namespace App\Http\Controllers;

use App\Models\DewormHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DewormHistoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/deworm_history",
     *     tags={"Deworm History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Create a new deworm history record",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass deworm history data",
     *         @OA\JsonContent(
     *             required={"medical_record_id","time"},
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Routine deworming"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-04-14"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Deworm history created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deworm history created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="Routine deworming"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *             )
     *         )
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'required|integer|exists:medical_records,id',
            'description' => 'string|nullable',
            'time' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dewormHistory = DewormHistory::create($validator->validated());

        return response()->json([
            'message' => 'Deworm history created successfully',
            'data' => $dewormHistory
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/deworm_history/{id}",
     *     tags={"Deworm History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Update an existing deworm history record",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Deworm History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass deworm history data",
     *         @OA\JsonContent(
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Updated routine deworming"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-05-14"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deworm history updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deworm history updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="Updated routine deworming"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-05-14")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deworm history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deworm history not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'integer|exists:medical_records,id',
            'description' => 'string|nullable',
            'time' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $dewormHistory = DewormHistory::find($id);
        if (!$dewormHistory) {
            return response()->json(['message' => 'Deworm history not found'], 404);
        }

        $dewormHistory->update($validator->validated());

        return response()->json([
            'message' => 'Deworm history updated successfully',
            'data' => $dewormHistory->refresh()
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/deworm_history/{id}",
     *     tags={"Deworm History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Delete a deworm history record",
     *     operationId="deleteDewormHistory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Deworm History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Deworm history deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deworm history deleted successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Deworm history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deworm history not found")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $dewormHistory = DewormHistory::find($id);
        if (!$dewormHistory) {
            return response()->json(['message' => 'Deworm history not found'], 404);
        }

        $dewormHistory->delete();

        return response()->json(['message' => 'Deworm history deleted successfully'], 200);
    }
}
