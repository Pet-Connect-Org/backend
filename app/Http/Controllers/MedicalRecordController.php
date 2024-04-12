<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalRecord\CreateMedicalRecordRequest;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    //

    public function create(Request $request)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        $pet = Pet::where('id', $request->input('pet_id'))->first();

        if ($user->id !== $pet->user_id) {
            return response()->json([
                'message' => "Not have permission"
            ], 403);
        }

        $medicalRecord = MedicalRecord::create([
            'pet_id' => $request->input('pet_id'),
            'favoriteFood' => $request->input('favoriteFood'),
            'isFriendlyWithDog' => $request->boolean('isFriendlyWithDog') ? 1 : 0,
            'isFriendlyWithCat' => $request->boolean('isFriendlyWithCat') ? 1 : 0,
            'isFriendlyWithKid' => $request->boolean('isFriendlyWithKid') ? 1 : 0,
            'isCleanProperly' => $request->boolean('isCleanProperly') ? 1 : 0,
            'isShy' => $request->boolean('isShy') ? 1 : 0,
            'isHyperactive' => $request->boolean('isHyperactive') ? 1 : 0
        ]);

        if ($medicalRecord) {
            return response()->json([
                'message' => "OK",
                'data' => $medicalRecord
            ], 201);
        } else {
            return response()->json([
                'message' => "Error"
            ], 500);
        }
    }
}
