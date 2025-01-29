<?php

return [
    'inheritance_patterns' => [
        'extension' => [
            'normal' => ['dominant' => true,
                'allele' => 'E'],
            'recessive_red' => ['dominant' => false,
                'allele' => 'e'],
        ],
        'dominant_black' => [
            'solid_black' => ['dominant' => true,
                'allele' => 'K'],
            'brindle' => ['intermediate' => true,
                'allele' => 'kbr'],
            'recessive_non_black' => ['dominant' => false,
                'allele' => 'ky'],
        ],
        'agouti' => [
            'sable' => ['dominant' => true,
                'allele' => 'Ay'],
            'wild' => ['dominant' => true,
                'allele' => 'Aw'],
            'tan_points' => ['dominant' => false,
                'allele' => 'at'],
            'recessive_black' => ['dominant' => false,
                'allele' => 'a'],
        ],
        'base_color' => [
            'black' => ['dominant' => true,
                'allele' => 'B'],
            'liver' => ['dominant' => false,
                'allele' => 'b'],
        ],
        'white_spotting' => [
            'solid' => ['dominant' => true,
                'allele' => 'S'],
            'pied' => ['dominant' => false,
                'allele' => 'sp'],
        ],
        'dilution' => [
            'normal' => ['dominant' => true,
                'allele' => 'D'],
            'dilute' => ['dominant' => false,
                'allele' => 'd'],
        ],
    ],
];
