<?php

namespace App\Http\Controllers\API;

use App\Models\Contactus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactusController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $contactuses = Contactus::all();
            return response()->json([
                'success' => true,
                'message' => 'Contactus entries retrieved successfully',
                'data' => $contactuses
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contactus entries',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'contactuse_name' => 'required|string|max:255',
                'contactuse_description' => 'required|string',
            ], [
                'contactuse_name.required' => 'Name is required',
                'contactuse_description.required' => 'Description is required',
            ]);

            $contactus = Contactus::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Contactus entry created successfully',
                'data' => $contactus
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create contactus entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contactus $contactus): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Contactus entry retrieved successfully',
                'data' => $contactus
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contactus entry not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contactus entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contactus $contactus)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contactus $contactus): JsonResponse
    {
        try {
            $request->validate([
                'contactuse_name' => 'sometimes|string|max:255',
                'contactuse_description' => 'sometimes|string',
            ], [
                'contactuse_name.required' => 'Name is required',
                'contactuse_description.required' => 'Description is required',
            ]);

            $contactus->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Contactus entry updated successfully',
                'data' => $contactus
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contactus entry not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update contactus entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contactus $contactus): JsonResponse
    {
        try {
            $contactus->delete();
            return response()->json([
                'success' => true,
                'message' => 'Contactus entry deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Contactus entry not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contactus entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
