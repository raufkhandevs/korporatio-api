<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyFormation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'status',
        'company_name',
        'alternative_company_name',
        'designation',
        'point_of_contact',
        'company_info',
        'countries_of_interest',
        'shares_structure',
        'shareholders',
        'beneficial_owners',
        'directors',
        'submitted_at',
        'processed_at',
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'point_of_contact' => 'array',
            'company_info' => 'array',
            'countries_of_interest' => 'array',
            'shares_structure' => 'array',
            'shareholders' => 'array',
            'beneficial_owners' => 'array',
            'directors' => 'array',
            'submitted_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    /**
     * The possible statuses for a company formation application
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get all possible statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    /**
     * Generate a unique reference number
     */
    public static function generateReferenceNumber(): string
    {
        do {
            $referenceNumber = 'BVI-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for recent applications
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
