<?php
use App\Core\Env;

Env::load(BASE_PATH . '/.env');

define('APP_NAME', Env::get('APP_NAME', 'Jolis SMS'));
define('APP_URL', Env::get('APP_URL', 'http://localhost'));
define('APP_ENV', Env::get('APP_ENV', 'production'));
define('APP_DEBUG', Env::get('APP_DEBUG', false));

define('DB_HOST', Env::get('DB_HOST', 'localhost'));
define('DB_PORT', Env::get('DB_PORT', '3306'));
define('DB_DATABASE', Env::get('DB_DATABASE', 'jolis_sms'));
define('DB_USERNAME', Env::get('DB_USERNAME', 'root'));
define('DB_PASSWORD', Env::get('DB_PASSWORD', ''));

define('SMTP_HOST', Env::get('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', Env::get('SMTP_PORT', 587));
define('SMTP_USERNAME', Env::get('SMTP_USERNAME', ''));
define('SMTP_PASSWORD', Env::get('SMTP_PASSWORD', ''));
define('SMTP_ENCRYPTION', Env::get('SMTP_ENCRYPTION', 'tls'));
define('SMTP_FROM_EMAIL', Env::get('SMTP_FROM_EMAIL', ''));
define('SMTP_FROM_NAME', Env::get('SMTP_FROM_NAME', 'Jolis SMS'));

define('RECAPTCHA_SITE_KEY', Env::get('RECAPTCHA_SITE_KEY', ''));
define('RECAPTCHA_SECRET_KEY', Env::get('RECAPTCHA_SECRET_KEY', ''));

define('SESSION_LIFETIME', Env::get('SESSION_LIFETIME', 120));
define('PASSWORD_COST', Env::get('PASSWORD_COST', 12));

date_default_timezone_set('Africa/Kampala');

error_reporting(APP_DEBUG ? E_ALL : 0);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
