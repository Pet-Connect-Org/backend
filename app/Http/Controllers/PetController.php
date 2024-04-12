<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pet\CreatePetRequest;
use App\Http\Requests\Pet\GetPetListByUserIdRequest;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function getPetListByUserId(GetPetListByUserIdRequest $request)
    {
        $petlist = Pet::where("user_id", $request->input('user_id'))->get();

        return response()->json([
            "message" => "Query success.",
            "data" => $petlist
        ], 201);
    }

    public function create(Request $request)
    {
        $user = User::where('account_id', auth()->user()->id)->first();

        $pet = Pet::create([
            'image' => $request->input('image'),
            'name' => $request->input('name'),
            'birthday' => $request->input('birthday'),
            'sex' => $request->input('sex'),
            'description' => $request->input('description'),
            'pet_type_id' => $request->input('pet_type_id'),
            'user_id' => $user->id,
        ]);

        if ($pet) {
            return response()->json([
                "message" => "Create new pet success.",
                "data" => $pet
            ], 201);
        } else {
            return response()->json([
                "message" => "Failed to create new pet.",
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                "message" => "Pet not found."
            ], 404);
        }

        $pet->fill([
            'image' => $request->input('image'),
            'name' => $request->input('name'),
            'birthday' => $request->input('birthday'),
            'sex' => $request->input('sex'),
            'description' => $request->input('description'),
            'pet_type_id' => $request->input('pet_type_id'),
        ]);

        $pet->save();

        return response()->json([
            "message" => "Pet updated successfully.",
            "data" => $pet
        ], 201);
    }
}
