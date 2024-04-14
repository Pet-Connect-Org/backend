<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetProfile\CreaatePetProfileRequest;
use App\Models\Pet;
use Carbon\Carbon;

class PetProfileController extends Controller
{
    //

    public function createProfile(CreaatePetProfileRequest $request)
    {
        $birthday = Carbon::parse($request->birthday)->format('Y-m-d');

        $petController = new PetController();
        $medicalRecordController = new MedicalRecordController();

        $pet = $petController->create($request->merge(['birthday' => $birthday]))->getData(true)['data'];

        $request->merge(['pet_id' => $pet['id']]);

        $medicalRecord = $medicalRecordController->create($request);

        if ($medicalRecord) {
            return response()->json([
                "message" => "Success",
                "data" => true
            ]);
        } else {
            return response()->json([
                "message" => "Failed",
                "data" => false
            ]);
        }
    }
    /**
     * @OA\Get(
     *     path="/pet_profile/{id}",
     *     tags={"Pet Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the pet to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet profile retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Get pet profile successful"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="pet_type_id", type="integer", example=4),
     *                 @OA\Property(property="user_id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="Bùi Thúy Ngọc"),
     *                 @OA\Property(property="sex", type="string", example="female"),
     *                 @OA\Property(property="description", type="string", example="Rất xinh đẹp tuyệt vời"),
     *                 @OA\Property(property="birthday", type="string", example="2005-12-03"),
     *                 @OA\Property(property="image", type="string", example="https://demo/demo-image.png"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-20T09:43:36.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-20T09:43:36.000000Z"),
     *                 @OA\Property(
     *                     property="med",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="pet_id", type="integer", example=37),
     *                     @OA\Property(property="favoriteFood", type="string", example="pate"),
     *                     @OA\Property(property="isFriendlyWithDog", type="boolean", example=false),
     *                     @OA\Property(property="isFriendlyWithCat", type="boolean", example=true),
     *                     @OA\Property(property="isCleanProperly", type="boolean", example=true),
     *                     @OA\Property(property="isHyperactive", type="boolean", example=true),
     *                     @OA\Property(property="isFriendlyWithKid", type="boolean", example=true),
     *                     @OA\Property(property="isShy", type="boolean", example=false),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-14T03:03:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-14T03:03:38.000000Z"),
     *                     @OA\Property(
     *                          property="allergies",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                              @OA\Property(property="id", type="integer", example=7),
     *                              @OA\Property(property="description", type="string", example="Pollen allergy"),
     *                              @OA\Property(property="medical_record_id", type="integer", example=2),
     *                              @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-30T17:07:19.000000Z"),
     *                              @OA\Property(property="updated_at", type="string", format="date-time", example="2024-03-30T17:07:19.000000Z")
     *                          )
     *                      ),
     *                      @OA\Property(
     *                          property="weights",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="medical_record_id", type="integer", example=1),
     *                          @OA\Property(property="weight", type="number", format="float", example=20.5),
     *                          @OA\Property(property="description", type="string", example="Healthy weight"),
     *                          @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *                        )
     *                      ),
     *                      @OA\Property(
     *                          property="deworms",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="medical_record_id", type="integer", example=1),
     *                          @OA\Property(property="description", type="string", example="Deworm"),
     *                          @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *                        )
     *                      ),
     *                      @OA\Property(
     *                          property="vaccinations",
     *                          type="array",
     *                          @OA\Items(
     *                              type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="medical_record_id", type="integer", example=1),
     *                          @OA\Property(property="description", type="string", example="vaccinations ne"),
     *                          @OA\Property(property="name", type="string", example="vaccinations"),
     *                          @OA\Property(property="time", type="string", format="date-time", example="2024-04-14")
     *                        )
     *                      )
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function getPetProfileByPetId(String $id)
    {
        $pet = Pet::with(['med' => function ($m) {
            $m->with(['allergies', 'weights', 'deworms', 'vaccinations']);
        }])->find($id);
        return response()->json([
            'message' => "Success",
            'data' => $pet
        ], 201);
    }
}
