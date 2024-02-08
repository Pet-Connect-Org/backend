<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'sex'=> ['required', Rule::in(['male', 'female'])],
            'birthday'=> 'required',
            'address'=> 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'sex' => $request->input('sex'),
                'birthday' => $request->input('birthday'),
                'address' => $request->input('address'),
                'account_id' => $request->input('account_id')
            ]);
            if ($user) {
                return response()->json(['message' => 'User created successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to create user'], 500);
            }        
        } catch (\Exception $e) {
            $accountController = new AccountController();
            $accountController->destroy($request->input('account_id'));
    
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
