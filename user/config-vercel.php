<?php
/**
 * YOURLS Config for Vercel
 *
 * Reads configuration from environment variables.
 * Set these in Vercel: Project Settings > Environment Variables
 *
 * Required:
 *   YOURLS_DB_USER, YOURLS_DB_PASS, YOURLS_DB_NAME, YOURLS_DB_HOST
 *
 * Optional:
 *   YOURLS_SITE - Your site URL (default: https://your-project.vercel.app)
 *   YOURLS_DB_PREFIX - Table prefix (default: yourls_)
 *   YOURLS_COOKIEKEY - Cookie encryption key (generate random string)
 *   YOURLS_ADMIN_USER, YOURLS_ADMIN_PASS - Admin login credentials
 */

$getenv = function ($key, $default = '') {
    return getenv($key) ?: (isset($_ENV[$key]) ? $_ENV[$key] : $default);
};

// Database - REQUIRED: Set these in Vercel dashboard
if (empty($getenv('YOURLS_DB_HOST', ''))) {
    die('YOURLS no Vercel: Configure as variáveis YOURLS_DB_HOST, YOURLS_DB_USER, YOURLS_DB_PASS e YOURLS_DB_NAME em Project Settings > Environment Variables.');
}

define('YOURLS_DB_USER', $getenv('YOURLS_DB_USER', ''));
define('YOURLS_DB_PASS', $getenv('YOURLS_DB_PASS', ''));
define('YOURLS_DB_NAME', $getenv('YOURLS_DB_NAME', 'yourls'));
define('YOURLS_DB_HOST', $getenv('YOURLS_DB_HOST', ''));
define('YOURLS_DB_PREFIX', $getenv('YOURLS_DB_PREFIX', 'yourls_'));

// Site URL - Use your Vercel deployment URL
$vercel_url = $getenv('VERCEL_URL', '');
$site = $getenv('YOURLS_SITE', '');
if (empty($site) && $vercel_url) {
    $site = 'https://' . $vercel_url;
}
define('YOURLS_SITE', $site ?: 'https://your-project.vercel.app');

define('YOURLS_LANG', $getenv('YOURLS_LANG', ''));
define('YOURLS_UNIQUE_URLS', $getenv('YOURLS_UNIQUE_URLS', 'true') !== 'false');
define('YOURLS_PRIVATE', $getenv('YOURLS_PRIVATE', 'true') !== 'false');

// Cookie key - Generate at https://yourls.org/cookie
define('YOURLS_COOKIEKEY', $getenv('YOURLS_COOKIEKEY', 'change-this-to-a-random-string-' . bin2hex(random_bytes(16))));

// Admin user(s) - Format: username => password (plain text, YOURLS will hash)
$admin_user = $getenv('YOURLS_ADMIN_USER', 'admin');
$admin_pass = $getenv('YOURLS_ADMIN_PASS', 'changeme');
$yourls_user_passwords = [$admin_user => $admin_pass];

define('YOURLS_URL_CONVERT', (int) $getenv('YOURLS_URL_CONVERT', 36));
define('YOURLS_DEBUG', $getenv('YOURLS_DEBUG', 'false') === 'true');

$yourls_reserved_URL = ['admin', 'api', 'css', 'js', 'images', 'user', 'includes', 'porn', 'faggot', 'sex', 'nigger', 'fuck', 'cunt', 'dick'];
