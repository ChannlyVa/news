<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Contactus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ContactusController extends Controller
{
    //
    public function index(): JsonResponse
    {
        try {
            $contactus = Contactus::all();
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
