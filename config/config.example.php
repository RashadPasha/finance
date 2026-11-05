<?php
declare(strict_types=1);

/*
 * Copy to config.php and fill in server-side values.
 * Never commit config.php or place API keys in frontend/mobile code.
 */
return [
    'app' => [
        'name' => 'RashadFinance',
        'base_url' => 'https://example.com',
        'environment' => 'production',
        'timezone' => 'Asia/Baku',
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'YOUR_DATABASE',
        'username' => 'YOUR_DB_USER',
        'password' => 'CHANGE_THIS',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        // Generate with password_hash('your-password', PASSWORD_DEFAULT).
        'admin_password_hash' => 'REPLACE_WITH_PASSWORD_HASH',
        'session_name' => 'rashadfinance_admin',
    ],
    'openai' => [
        // Set this as a server environment variable; do not commit a real key.
        'api_key' => getenv('OPENAI_API_KEY') ?: '',
        'model' => getenv('OPENAI_MODEL') ?: 'gpt-4.1-mini',
    ],
];
