<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CompanyFormationService;
use App\Http\Requests\CompanyFormationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\CompanyFormation;

class CompanyFormationController extends Controller
{
    public function __construct(
        private readonly CompanyFormationService $companyFormationService
    ) {}

    /**
     * Store a new company formation application
     */
    public function store(CompanyFormationRequest $request): JsonResponse
    {
        try {
            $companyFormation = $this->companyFormationService->createApplication($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Company formation application submitted successfully',
                'data' => [
                    'id' => $companyFormation->id,
                    'reference_number' => $companyFormation->reference_number,
                    'status' => $companyFormation->status,
                    'created_at' => $companyFormation->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Company formation submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit company formation application',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Auto-save form data
     */
    public function autoSave(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string|max:255',
                'step' => 'required|integer|min:1|max:4',
                'data' => 'required|array'
            ]);

            $this->companyFormationService->saveFormData(
                $validated['session_id'],
                $validated['step'],
                $validated['data']
            );

            return response()->json([
                'success' => true,
                'message' => 'Form data saved successfully',
                'saved_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Auto-save failed', [
                'error' => $e->getMessage(),
                'session_id' => $request->input('session_id'),
                'step' => $request->input('step')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save form data'
            ], 500);
        }
    }

    /**
     * Retrieve saved form data
     */
    public function getFormData(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string|max:255'
            ]);

            $formData = $this->companyFormationService->getFormData($validated['session_id']);

            return response()->json([
                'success' => true,
                'data' => $formData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve form data', [
                'error' => $e->getMessage(),
                'session_id' => $request->input('session_id')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve form data'
            ], 500);
        }
    }

    /**
     * Get application status
     */
    public function getStatus(string $referenceNumber): JsonResponse
    {
        try {
            $companyFormation = CompanyFormation::where('reference_number', $referenceNumber)->first();

            if (!$companyFormation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'reference_number' => $companyFormation->reference_number,
                    'status' => $companyFormation->status,
                    'company_name' => $companyFormation->company_name,
                    'submitted_at' => $companyFormation->created_at,
                    'updated_at' => $companyFormation->updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get application status', [
                'error' => $e->getMessage(),
                'reference_number' => $referenceNumber
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve application status'
            ], 500);
        }
    }
}
