<?php
/**
 * Vendor Autoload Placeholder
 * Run 'composer install' to generate the actual autoloader
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

// This is a placeholder. Run 'composer install' to generate the actual autoloader.
// For now, we'll manually include PHPMailer if it exists

$phpmailerPath = __DIR__ . '/phpmailer/phpmailer/src/';

if (is_dir($phpmailerPath)) {
    require_once $phpmailerPath . 'Exception.php';
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';
}
