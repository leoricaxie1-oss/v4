<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Barangay identity
    |--------------------------------------------------------------------------
    */
    'barangay' => [
        'name'         => 'Barangay Panipuan',
        'municipality' => 'City of San Fernando',
        'province'     => 'Pampanga',
        'region'       => 'Region III (Central Luzon)',
        'hotline'      => env('BARANGAY_HOTLINE', '(045) 000-0000'),
        'email'        => env('BARANGAY_EMAIL', 'info@panipuan.gov.ph'),
        'address'      => 'Brgy. Panipuan, City of San Fernando, Pampanga',
    ],

    /*
    |--------------------------------------------------------------------------
    | The seven puroks of Barangay Panipuan
    |--------------------------------------------------------------------------
    */
    'puroks' => [
        'Purok 1',
        'Purok 2',
        'Purok 3',
        'Purok 4',
        'Purok 5',
        'Purok 6',
        'Purok 7',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pre-defined skill / service categories.
    | If the resident picks "Others", a free-text field is exposed.
    |--------------------------------------------------------------------------
    */
    'skill_categories' => [
        'Plumbing', 'Electrical Repair', 'Carpentry', 'Welding', 'Masonry',
        'House Cleaning', 'Gardening', 'Painting', 'Appliance Repair',
        'Automotive Repair', 'Delivery Services', 'Laundry Services',
        'Tutoring', 'Computer Repair', 'Technical Support', 'Tailoring',
        'Photography', 'Haircutting', 'Cooking', 'Others',
    ],

    /*
    |--------------------------------------------------------------------------
    | Complaint categories
    |--------------------------------------------------------------------------
    */
    'complaint_categories' => [
        'Noise Complaint', 'Property Dispute', 'Public Disturbance',
        'Harassment', 'Theft', 'Vandalism', 'Domestic Concern',
        'Community Conflict', 'Others',
    ],

    /*
    |--------------------------------------------------------------------------
    | Document types residents can request
    |--------------------------------------------------------------------------
    */
    'documents' => [
        'barangay_clearance'        => [
            'label' => 'Barangay Clearance',
            'fee'   => 50.00,
        ],
        'certificate_of_residency'  => [
            'label' => 'Certificate of Residency',
            'fee'   => 30.00,
        ],
        'certificate_of_indigency'  => [
            'label' => 'Certificate of Indigency',
            'fee'   => 0.00,
        ],
        'business_clearance'        => [
            'label' => 'Business Clearance',
            'fee'   => 200.00,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Approval workflow — the order MUST be respected. Each level may only
    | act once the previous level has approved.
    |--------------------------------------------------------------------------
    */
    'approval_chain' => ['secretary', 'kagawad', 'captain'],

    /*
    |--------------------------------------------------------------------------
    | Upload validation defaults
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_size_kb' => 5120, // 5 MB
        'allowed_mimes' => ['jpg', 'jpeg', 'png', 'pdf'],
    ],
];
