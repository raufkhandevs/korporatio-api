<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CompanyFormationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Point of Contact
            'point_of_contact.full_name' => 'required|string|min:2|max:255',
            'point_of_contact.email' => 'required|email|max:255',

            // Company Information
            'company_info.company_name' => 'required|string|min:2|max:255',
            'company_info.alternative_company_name' => 'nullable|string|max:255',
            'company_info.designation' => 'required|in:ltd,inc,corp,llc',

            // Countries of Interest
            'countries_of_interest.jurisdiction_of_operation' => 'required|string|in:us,uk,ca,au,de,sg,fr,it,es,nl',
            'countries_of_interest.target_jurisdictions' => 'nullable|array',
            'countries_of_interest.target_jurisdictions.*' => 'string',

            // Shares Structure
            'shares_structure.number_of_shares' => 'required|integer|min:1',
            'shares_structure.all_shares_issued' => 'required|boolean',
            'shares_structure.number_of_issued_shares' => 'nullable|integer|min:1',
            'shares_structure.value_per_share' => 'required|numeric|min:0.01',

            // Shareholders
            'shareholders' => 'required|array|min:1',
            'shareholders.*.type' => 'required|in:individual,corporate',
            'shareholders.*.full_name' => 'required|string|min:2|max:255',
            'shareholders.*.nationality' => 'required|string|max:100',
            'shareholders.*.address' => 'required|string|min:10|max:500',
            'shareholders.*.share_percentage' => 'required|numeric|min:0.01|max:100',
            'shareholders.*.date_of_birth' => 'nullable|date|before:today',
            'shareholders.*.passport_number' => 'nullable|string|max:50',
            'shareholders.*.corporate_name' => 'nullable|string|max:255',
            'shareholders.*.registration_number' => 'nullable|string|max:100',

            // Beneficial Owners
            'beneficial_owners' => 'required|array|min:1',
            'beneficial_owners.*.full_name' => 'required|string|min:2|max:255',
            'beneficial_owners.*.nationality' => 'required|string|max:100',
            'beneficial_owners.*.address' => 'required|string|min:10|max:500',
            'beneficial_owners.*.date_of_birth' => 'required|date|before:today',
            'beneficial_owners.*.passport_number' => 'required|string|max:50',
            'beneficial_owners.*.ownership_percentage' => 'required|numeric|min:0.01|max:100',
            'beneficial_owners.*.source_of_funds' => 'required|in:salary,business,investment,inheritance,savings,other',
            'beneficial_owners.*.politically_exposed' => 'required|boolean',

            // Directors
            'directors' => 'required|array|min:1',
            'directors.*.full_name' => 'required|string|min:2|max:255',
            'directors.*.nationality' => 'required|string|max:100',
            'directors.*.address' => 'required|string|min:10|max:500',
            'directors.*.date_of_birth' => 'required|date|before:today',
            'directors.*.passport_number' => 'required|string|max:50',
            'directors.*.occupation' => 'required|in:business_owner,executive,professional,consultant,investor,retired,other',
            'directors.*.experience' => 'required|string|min:10|max:1000',
            'directors.*.consent_to_act' => 'required|boolean|accepted',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'point_of_contact.full_name.required' => 'Full name is required',
            'point_of_contact.email.required' => 'Email address is required',
            'point_of_contact.email.email' => 'Please provide a valid email address',
            
            'company_info.company_name.required' => 'Company name is required',
            'company_info.designation.required' => 'Company designation is required',
            
            'countries_of_interest.jurisdiction_of_operation.required' => 'Jurisdiction of operation is required',
            
            'shareholders.required' => 'At least one shareholder is required',
            'shareholders.*.share_percentage.required' => 'Share percentage is required for all shareholders',
            
            'beneficial_owners.required' => 'At least one beneficial owner is required',
            'beneficial_owners.*.ownership_percentage.required' => 'Ownership percentage is required for all beneficial owners',
            
            'directors.required' => 'At least one director is required',
            'directors.*.consent_to_act.accepted' => 'Director must consent to act',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate total share percentage equals 100%
            if ($this->has('shareholders')) {
                $totalSharePercentage = collect($this->input('shareholders'))
                    ->sum('share_percentage');
                
                if ($totalSharePercentage != 100) {
                    $validator->errors()->add(
                        'shareholders',
                        'Total share percentage must equal 100%'
                    );
                }
            }

            // Validate issued shares don't exceed total shares
            if ($this->has('shares_structure')) {
                $sharesStructure = $this->input('shares_structure');
                if (!$sharesStructure['all_shares_issued'] && 
                    isset($sharesStructure['number_of_issued_shares']) &&
                    $sharesStructure['number_of_issued_shares'] > $sharesStructure['number_of_shares']) {
                    
                    $validator->errors()->add(
                        'shares_structure.number_of_issued_shares',
                        'Number of issued shares cannot exceed total number of shares'
                    );
                }
            }
        });
    }
}
