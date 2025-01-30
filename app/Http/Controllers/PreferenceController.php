<?php

namespace App\Http\Controllers;

use App\Actions\Preference\GetUserPreferencesAction;
use App\Actions\Preference\SetUserPreferenceAction;
use App\Http\Requests\AddPreferenceRequest;
use App\Http\Resources\PreferenceResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function index(GetUserPreferencesAction $getUserPreferencesAction)
    {
        $preferences = $getUserPreferencesAction->execute();

        return PreferenceResource::collection($preferences);
    }
    public function store(Request $request, SetUserPreferenceAction $setUserPreferenceAction)
    {
        // Validate the request data
        $requestData = Validator::make($request->all(), [
            'preferencable_id' => 'required|integer',
            'preferencable_type' => 'required|string|in:category,author,source',
        ]);

        // Check if validation fails
        if ($requestData->fails()) {
            return response()->json(['errors' => $requestData->errors()], 422);
        }

        // Get the validated data from the validator
        $validatedData = $requestData->validated();

        // dd($validatedData);

        // Pass the validated data to the action
        $preference = $setUserPreferenceAction->execute($validatedData);

        return new PreferenceResource($preference);
    }

}
