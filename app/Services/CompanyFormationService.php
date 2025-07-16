<?php

namespace App\Services;

use App\Models\CompanyFormation;
use App\Models\FormSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyFormationService
{
    /**
     * Create a new company formation application
     */
    public function createApplication(array $data): CompanyFormation
    {
        return DB::transaction(function () use ($data) {
            $companyFormation = CompanyFormation::create([
                'reference_number' => CompanyFormation::generateReferenceNumber(),
                'status' => CompanyFormation::STATUS_SUBMITTED,
                'company_name' => $data['company_info']['company_name'],
                'alternative_company_name' => $data['company_info']['alternative_company_name'] ?? null,
                'designation' => $data['company_info']['designation'],
                'point_of_contact' => $data['point_of_contact'],
                'company_info' => $data['company_info'],
                'countries_of_interest' => $data['countries_of_interest'],
                'shares_structure' => $data['shares_structure'],
                'shareholders' => $data['shareholders'],
                'beneficial_owners' => $data['beneficial_owners'],
                'directors' => $data['directors'],
                'submitted_at' => now(),
            ]);

            // Log the submission
            Log::info('Company formation application submitted', [
                'reference_number' => $companyFormation->reference_number,
                'company_name' => $companyFormation->company_name,
                'contact_email' => $data['point_of_contact']['email']
            ]);

            // TODO: Send confirmation email
            // TODO: Notify admin team

            return $companyFormation;
        });
    }

    /**
     * Save form data for auto-save functionality
     */
    public function saveFormData(string $sessionId, int $step, array $data): FormSession
    {
        return FormSession::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'current_step' => $step,
                'form_data' => $data,
                'last_saved_at' => now()
            ]
        );
    }

    /**
     * Retrieve saved form data
     */
    public function getFormData(string $sessionId): ?array
    {
        $session = FormSession::bySessionId($sessionId)->first();
        
        return $session ? [
            'current_step' => $session->current_step,
            'form_data' => $session->form_data,
            'last_saved_at' => $session->last_saved_at
        ] : null;
    }

    /**
     * Clean up old form sessions
     */
    public function cleanupOldSessions(int $daysOld = 7): int
    {
        return FormSession::where('last_saved_at', '<', now()->subDays($daysOld))->delete();
    }

    /**
     * Get application statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_applications' => CompanyFormation::count(),
            'submitted_today' => CompanyFormation::whereDate('created_at', today())->count(),
            'pending_review' => CompanyFormation::byStatus(CompanyFormation::STATUS_SUBMITTED)->count(),
            'completed_this_month' => CompanyFormation::byStatus(CompanyFormation::STATUS_COMPLETED)
                ->whereMonth('processed_at', now()->month)
                ->count(),
            'by_status' => CompanyFormation::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray()
        ];
    }
}
