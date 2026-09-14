<?php
// Define the folder and file paths
$folder = '.env';
$file = $folder . '/config.php';

// Configuration content
$configContent = <<<'CONFIG'
<?php

putenv('APP_DOMAIN=example.com');
putenv('APP_NAME=MyApp');
putenv('APP_ENV=local');
putenv('PRODUCTION_MODE=true');

// 'true' renders from v1/views/includes/static_content.php and opens no
// database connection at all. Set to 'false' once the CMS tables exist.
putenv('DESIGN_MODE=true');
putenv('DEBUG_MODE=true');
putenv('APP_VERSION=v1');
putenv('APP_SECRET_KEY=1234');

// Database configuration
putenv('DB_CONNECTION=mysql');
putenv('DB_HOST=localhost');
putenv('DB_PORT=3306');
putenv('DB_NAME=my_database');
// The app layer reads DB_USER, the core/ layer reads DB_USERNAME.
// Both are written so either resolves. Keep them identical.
putenv('DB_USER=root');
putenv('DB_USERNAME=root');
putenv('DB_PASSWORD=password');

// Mail configuration
putenv('MAIL_HOST=smtp.mailtrap.io');
putenv('MAIL_PORT=2525');
putenv('MAIL_USERNAME=your_username');
putenv('MAIL_PASSWORD=your_password');
putenv('MAIL_ENCRYPTION=tls');
putenv('MAIL_FROM_ADDRESS=example@mail.com');
putenv('MAIL_FROM_NAME=MyApp Support');

// External services
putenv('AWS_ACCESS_KEY_ID=your_aws_access_key');
putenv('AWS_SECRET_ACCESS_KEY=your_aws_secret_key');
putenv('AWS_REGION=us-east-1');

// Logging
putenv('LOG_CHANNEL=stack');
putenv('LOG_LEVEL=debug');

// Cache and queue
putenv('CACHE_DRIVER=file');
putenv('QUEUE_CONNECTION=sync');

// Timezone and locale
putenv('DEFAULT_LOCALE=en');
putenv('TIMEZONE=UTC');
?>
CONFIG;

// Check if the folder exists, if not create it
if (!is_dir($folder)) {
    mkdir($folder, 0755, true);
    echo "Folder '$folder' created successfully.\n";
}

// Write the configuration content to the file
if (file_put_contents($file, $configContent)) {
    echo "File '$file' created and content written successfully.\n";
} else {
    echo "Failed to write content to '$file'.\n";
}
?>
