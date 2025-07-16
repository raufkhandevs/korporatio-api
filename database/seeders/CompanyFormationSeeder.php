<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyFormation;

class CompanyFormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleApplications = [
            [
                'reference_number' => CompanyFormation::generateReferenceNumber(),
                'status' => CompanyFormation::STATUS_SUBMITTED,
                'company_name' => 'Tech Innovations Ltd',
                'designation' => 'ltd',
                'point_of_contact' => [
                    'full_name' => 'Alice Johnson',
                    'email' => 'alice@techinnovations.com'
                ],
                'company_info' => [
                    'company_name' => 'Tech Innovations Ltd',
                    'designation' => 'ltd'
                ],
                'countries_of_interest' => [
                    'jurisdiction_of_operation' => 'us'
                ],
                'shares_structure' => [
                    'number_of_shares' => 1000,
                    'all_shares_issued' => true,
                    'value_per_share' => 1.00
                ],
                'shareholders' => [
                    [
                        'type' => 'individual',
                        'full_name' => 'Alice Johnson',
                        'nationality' => 'us',
                        'address' => '456 Tech Ave, San Francisco, CA 94105',
                        'share_percentage' => 100
                    ]
                ],
                'beneficial_owners' => [
                    [
                        'full_name' => 'Alice Johnson',
                        'nationality' => 'us',
                        'address' => '456 Tech Ave, San Francisco, CA 94105',
                        'ownership_percentage' => 100,
                        'source_of_funds' => 'business',
                        'politically_exposed' => false
                    ]
                ],
                'directors' => [
                    [
                        'full_name' => 'Alice Johnson',
                        'nationality' => 'us',
                        'address' => '456 Tech Ave, San Francisco, CA 94105',
                        'occupation' => 'executive',
                        'experience' => 'CEO with 15 years experience in tech industry',
                        'consent_to_act' => true
                    ]
                ],
                'submitted_at' => now(),
            ],
            [
                'reference_number' => CompanyFormation::generateReferenceNumber(),
                'status' => CompanyFormation::STATUS_UNDER_REVIEW,
                'company_name' => 'Global Trading Corp',
                'designation' => 'corp',
                'point_of_contact' => [
                    'full_name' => 'Bob Smith',
                    'email' => 'bob@globaltrading.com'
                ],
                'company_info' => [
                    'company_name' => 'Global Trading Corp',
                    'designation' => 'corp'
                ],
                'countries_of_interest' => [
                    'jurisdiction_of_operation' => 'uk'
                ],
                'shares_structure' => [
                    'number_of_shares' => 10000,
                    'all_shares_issued' => false,
                    'number_of_issued_shares' => 5000,
                    'value_per_share' => 0.10
                ],
                'shareholders' => [
                    [
                        'type' => 'individual',
                        'full_name' => 'Bob Smith',
                        'nationality' => 'uk',
                        'address' => '789 Trading St, London, UK EC1A 1BB',
                        'share_percentage' => 60
                    ],
                    [
                        'type' => 'individual',
                        'full_name' => 'Carol Wilson',
                        'nationality' => 'uk',
                        'address' => '321 Business Rd, Manchester, UK M1 1AA',
                        'share_percentage' => 40
                    ]
                ],
                'beneficial_owners' => [
                    [
                        'full_name' => 'Bob Smith',
                        'nationality' => 'uk',
                        'address' => '789 Trading St, London, UK EC1A 1BB',
                        'ownership_percentage' => 60,
                        'source_of_funds' => 'investment',
                        'politically_exposed' => false
                    ]
                ],
                'directors' => [
                    [
                        'full_name' => 'Bob Smith',
                        'nationality' => 'uk',
                        'address' => '789 Trading St, London, UK EC1A 1BB',
                        'occupation' => 'business_owner',
                        'experience' => 'International trading experience for 20+ years',
                        'consent_to_act' => true
                    ]
                ],
                'submitted_at' => now()->subDays(2),
            ]
        ];

        foreach ($sampleApplications as $application) {
            CompanyFormation::create($application);
        }
    }
}
