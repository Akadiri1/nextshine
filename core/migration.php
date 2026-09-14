<?php
// Include core files
require_once __DIR__ . '/autoload.php';

// Database connection
$pdo = new PDO('mysql:host='.DB_HOST.':'. DB_PORT, DB_USERNAME, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



// $Database = new Database();
// $pdo = $Database->getDB();

if (!$pdo) {
    die("Database connection failed.");
}
// Initialize schema
Schema::init($pdo, DB_NAME);



/* 

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `email`, `hash`, `hash_id`, `portfolio`, `bio`, `phone_number`, `facebook_link`, `twitter_link`, `linkedin_link`, `instagram_link`, `location`, `image_1`, `image_2`, `image_3`, `time_created`, `date_created`, `last_login`, `last_logout`, `login_status`, `level`, `verification`, `profile_status`, `user_status`, `defaulted`, `usname`, `thumbnail`, `is_premium`, `expiration`, `country_id`, `country_name`, `country_short`, `created_by`) VALUES (NULL, 'Mckodev', 'Admin', 'mckodev@admin', '$2y$10$2itf3YG1y7bFWS0HY4nENOrBtONnBby2eQoFyKFcLE6M8Sj8DXoyi', '74484589_83484894', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1545335942mailIMG-20181022-WA0003.jpg', 'b12b9681-2bdc-4237-bfa1-51db8b8c2d81', NULL, '14:25:12', '2018-02-28', '2020-03-15 17:31:46', '2019-01-01 19:53:53', 'Logged In', 'MASTER', '1', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '')

*/
Schema::migrate('admin', function ($table) {
    $table->id(); // Primary key
    $table->string('firstname');
    $table->string('lastname');
    $table->string('email')->unique();
    $table->string('hash');
    $table->string('hash_id')->nullable();
    $table->text('portfolio')->nullable();
    $table->text('bio')->nullable();
    $table->string('phone_number')->nullable();
    $table->string('facebook_link')->nullable();
    $table->string('twitter_link')->nullable();
    $table->string('linkedin_link')->nullable();
    $table->string('instagram_link')->nullable();
    $table->string('location')->nullable();
    $table->text('image_1')->nullable();
    $table->text('image_2')->nullable();
    $table->text('image_3')->nullable();
    $table->time('time_created');
    $table->date('date_created');
    $table->timestamp('last_login')->nullable();
    $table->timestamp('last_logout')->nullable();
    $table->string('login_status')->nullable();
    $table->string('level')->nullable();
    $table->string('verification')->nullable();
    $table->integer('profile_status')->nullable();
    $table->string('user_status')->nullable();
    $table->integer('defaulted')->nullable();
    $table->text('usname')->nullable();
    $table->text('thumbnail')->nullable();
    $table->string('is_premium')->nullable();
    $table->date('expiration')->nullable();
    $table->string('country_id', 10)->nullable();
    $table->string('country_name', 40)->nullable();
    $table->string('country_short', 3)->nullable();
    $table->string('created_by')->nullable();
});


// Categories table
Schema::migrate('categories', function ($table) {
    $table->id(); // Primary key
    $table->string('hash_id');
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
});

// Products table
Schema::migrate('products', function ($table) {
    $table->id();
    $table->string('hash_id');
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->integer('category_id')->nullable();
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
});

// Variant Types table – stores types like "Size", "Color", etc.
Schema::migrate('variant_types', function ($table) {
    $table->id();
    $table->string('hash_id');
    $table->string('name');
    // $table->text('description')->nullable();
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
});

// Product Variant Groups table
// This table holds groups for product variants.
// If parent_option_id is null, it’s a top‐level group; if set, it’s nested under a specific option.
Schema::migrate('product_variant_groups', function ($table) {
    $table->id();
    $table->integer('product_id'); // FK to products.id
    $table->integer('variant_type_id'); // FK to variant_types.id
    $table->integer('parent_option_id')->nullable(); // FK to product_variant_options.id if nested
    $table->string('hash_id');
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
});

// Product Variant Options table
// This table stores the options for each variant group.
Schema::migrate('product_variant_options', function ($table) {
    $table->id();
    $table->integer('group_id'); // FK to product_variant_groups.id
    $table->string('option_name');
    $table->decimal('additional_price', 10, 2)->default(0);
    $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
});

// Schema::migrate('cart', function ($table) {
//     $table->id(); // Primary key
//     $table->string('hash_id');
//     $table->integer('user_id')->nullable();
//     $table->integer('product_id')->nullable();
//     $table->integer('variant_id')->nullable();
//     $table->integer('quantity')->default(1);
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
// });

// Schema::migrate('categories', function ($table) {
//     $table->id(); // Primary key
//     $table->string('hash_id');
//     $table->string('name');
//     $table->text('description')->nullable();
//     $table->string('image')->nullable();
//     $table->integer('parent_id')->nullable();
//     $table->integer('status')->default(1);
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
// });

// Schema::migrate('coupons', function ($table) {
//     $table->id(); // Primary key
//     $table->string('hash_id');
//     $table->string('code')->unique();
//     $table->decimal('discount', 10, 2);
//     $table->string('type');
//     $table->date('expiry_date')->nullable();
//     $table->integer('usage_limit')->nullable();
//     $table->integer('used')->default(0);
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');

// });

// Schema::migrate('orders', function ($table) {
//     $table->id(); // Primary key
//     $table->string('hash_id');
//     $table->integer('user_id');
//     $table->decimal('total_amount', 10, 2);
//     $table->string('status')->default('pending');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');

// });

// Schema::migrate('order_items', function ($table) {
//     $table->id(); // Primary key
//     $table->integer('order_id');
//     $table->string('hash_id');
//     $table->integer('product_id');
//     $table->integer('quantity');
//     $table->decimal('price', 10, 2);
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');

// });

// Schema::migrate('payments', function ($table) {
//     $table->id(); // Primary key
//     $table->integer('order_id');
//     $table->string('hash_id');
//     $table->string('payment_method');
//     $table->decimal('amount', 10, 2);
//     $table->string('status')->default('pending');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });

// Schema::migrate('products', function ($table) {
//     $table->id(); // Primary key
//     $table->string('hash_id');
//     $table->string('name');
//     $table->text('description')->nullable();
//     $table->decimal('price', 10, 2);
//     $table->integer('stock')->default(0);
//     $table->integer('category_id');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });

// Schema::migrate('product_variants', function ($table) {
//     $table->id(); // Primary key
//     $table->integer('product_id');
//     $table->string('hash_id');
//     $table->string('variant_name');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });

// Schema::migrate('product_variant_options', function ($table) {
//     $table->id(); // Primary key
//     $table->integer('variant_id');
//     $table->string('hash_id');
//     $table->string('option_name');
//     $table->decimal('additional_price', 10, 2)->default(0);
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });

// Schema::migrate('shipping', function ($table) {
//     $table->id(); // Primary key
//     $table->integer('order_id');
//     $table->string('address');
//     $table->string('city');
//     $table->string('state');
//     $table->string('country');
//     $table->string('postal_code');
//     $table->string('status')->default('pending');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });

// Schema::migrate('users', function ($table) {
//     $table->id(); // Primary key
//     $table->string('name');
//     $table->string('email')->unique();
//     $table->string('password');
//     $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
    
// });



// $Database->close();
?>
