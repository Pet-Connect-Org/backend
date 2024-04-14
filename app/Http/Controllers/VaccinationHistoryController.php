<?php

namespace App\Http\Controllers;

use App\Models\VaccinationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VaccinationHistoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/vaccination_history",
     *     tags={"Vaccination History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Create a new vaccination history record",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass vaccination history data",
     *         @OA\JsonContent(
     *             required={"medical_record_id","time", "name", "description"},
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Routine vaccination"),
     *             @OA\Property(property="name", type="string", example="Routine vaccination"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-04-14"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vaccination history created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vaccination history created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="Routine vaccination"),
     *                 @OA\Property(property="name", type="string", example="Routine vaccination"),
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
            'description' => 'string',
            'name' => 'string',
            'time' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        

        $vaccinationHistory = VaccinationHistory::create($validator->validated());

        return response()->json([
            'message' => 'Vaccination history created successfully',
            'data' => $vaccinationHistory
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/vaccination_history/{id}",
     *     tags={"Vaccination History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Update an existing vaccination history record",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vaccination History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass vaccination history data",
     *         @OA\JsonContent(
     *             @OA\Property(property="medical_record_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="Updated routine vaccination"),
     *             @OA\Property(property="time", type="string", format="date-time", example="2024-05-14"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vaccination history updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vaccination history updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="medical_record_id", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", example="Updated routine vaccination"),
     *                 @OA\Property(property="name", type="string", example="Updated routine vaccination"),
     *                 @OA\Property(property="time", type="string", format="date-time", example="2024-05-14")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vaccination history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vaccination history not found")
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

        $vaccinationHistory = VaccinationHistory::find($id);
        if (!$vaccinationHistory) {
            return response()->json(['message' => 'Vaccination history not found'], 404);
        }

        $vaccinationHistory->update($validator->validated());

        return response()->json([
            'message' => 'Vaccination history updated successfully',
            'data' => $vaccinationHistory->refresh()
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/vaccination_history/{id}",
     *     tags={"Vaccination History"},
     *     security={{"bearerAuth":{}}},
     * 
     *     summary="Delete a vaccination history record",
     *     operationId="deleteVaccinationHistory",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vaccination History ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vaccination history deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vaccination history deleted successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vaccination history not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vaccination history not found")
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $vaccinationHistory = VaccinationHistory::find($id);
        if (!$vaccinationHistory) {
            return response()->json(['message' => 'Vaccination history not found'], 404);
        }

        $vaccinationHistory->delete();

        return response()->json(['message' => 'Vaccination history deleted successfully'], 200);
    }
}
