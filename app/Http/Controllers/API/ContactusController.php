<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Contactus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ContactusController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $contactus = Contactus::pagenation(10);

            if ($contactus->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No contactus entries found',
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Contactus entries retrieved successfully',
                'data' => $contactus
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contactus entries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'message' => 'required|string'
            ], [
                'username.required' => 'Username is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email must be valid',
                'message.required' => 'Message is required',
            ]);

            $contactus = Contactus::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Contact message submitted successfully',
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
                'message' => 'Failed to submit contact message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $contactus = Contactus::find($id);
        if (!$contactus) {
            return response()->json([
                'success' => false,
                'message' => 'Contactus entry not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contactus entry retrieved successfully',
            'data' => $contactus->toArray()
        ]);
    }




    public function destroy($id): JsonResponse
    {
        $contactus = Contactus::find($id);

        if (!$contactus) {
            return response()->json([
                'success' => false,
                'message' => 'Contactus entry not found'
            ], 404);
        }

        try {
            $contactus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contactus entry deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contactus entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
