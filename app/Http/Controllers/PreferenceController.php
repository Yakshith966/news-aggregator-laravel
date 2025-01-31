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
     /**
     * @OA\Get(
     *     path="/api/preferences",
     *     summary="Get user preferences",
     *     description="Get list of user preferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Preference")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(GetUserPreferencesAction $getUserPreferencesAction)
    {
        $preferences = $getUserPreferencesAction->execute();

        return PreferenceResource::collection($preferences);
    }
    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     summary="Set user preference",
     *     description="Create or update user preference",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *                 required={"preferencable_id", "preferencable_type"},
     *
     *                 @OA\Property(
     *                     property="preferencable_id",
     *                     type="integer",
     *                     description="ID of the preferencable"
     *                 ),
     *                 @OA\Property(
     *                     property="preferencable_type",
     *                     type="string",
     *                     enum={"category", "author", "source"},
     *                     description="Type of the preferencable entity"
     *                 )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Preference created/updated",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Preference"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
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

        $validatedData = $requestData->validated();

        $preference = $setUserPreferenceAction->execute($validatedData);

        return new PreferenceResource($preference);
    }

}
