<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetProfile\CreaatePetProfileRequest;
use Illuminate\Http\Request;
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
}
