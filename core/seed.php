<?php

    $Controller = new Controller();

    $Database = new Database();
    $specialization_category = [
    [
        'name' => 'Contruction',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    [
        'name' => 'Engineering',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    [
        'name' => 'Tech',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    [
        'name' => 'Photography & Videography',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    [
        'name' => 'Interior Decoration',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    
    [
        'name' => 'Beauty & Cosmetics',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    [
        'name' => 'Clothing & Textile',
        'description' => '',
        'image' => '',
        'icon' => '',
        'visibility' => 'show',
        'created_at' => 'NOW()',
        'updated_at' => 'NOW()',
    ],
    // [
    //     'name' => '',
    //     'description' => '',
    //     'image' => '',
    //     'icon' => '',
    //     'visibility' => 'show',
    //     'created_at' => 'NOW()',
    //     'updated_at' => 'NOW()',
    // ],
];

$categories = [
    [
        'id'=> 15,
        'name' => 'Construction',
        'description' => '',
        'hash_id' => $Controller->generateUuid() ,
        'created_at' => 'NOW()',
    ],
    [
        'created_at' => 'NOW()',
        'name' => 'Engineering',
        
        'hash_id' => $Controller->generateUuid(),
        'description' => '',
    ],

];


// $Database->seed('specialization_category', $specialization_category);
$Database->seed('categories', $categories);


?>