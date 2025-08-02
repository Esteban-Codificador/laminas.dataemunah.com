<?php
return [
    // ...

    'navigation' => [

        // Navigation with name default
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
            ],
            [
                'label' => 'Page #1',
                'route' => 'page-1',
                'pages' => [
                    [
                        'label' => 'Child #1',
                        'route' => 'page-1-child',
                    ],
                ],
            ],
            [
                'label' => 'Page #2',
                'route' => 'page-2',
            ],
        ],

        // Navigation with name special
        'special' => [
            [
                'label' => 'Special',
                'route' => 'special',
            ],
            [
                'label' => 'Special Page #2',
                'route' => 'special-2',
            ],
        ],

        // Navigation with name sitemap
        'sitemap' => [
            [
                'label' => 'Sitemap',
                'route' => 'sitemap',
            ],
            [
                'label' => 'Sitemap Page #2',
                'route' => 'sitemap-2',
            ],
        ],
    ],
    // ...
];