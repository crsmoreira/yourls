<?php
/**
 * YOURLS - Vercel PHP Entry Point
 * 
 * Routes all requests to the appropriate YOURLS handler.
 * Static files (css, js, images) are served by Vercel before reaching this file.
 */

// Get the original request path (Vercel may pass this in different ways)
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Ensure REQUEST_URI is set for YOURLS (some serverless envs strip query string from path)
if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {
    $request_uri = $path . '?' . $_SERVER['QUERY_STRING'];
}
$_SERVER['REQUEST_URI'] = '/' . $path . (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');

$yourls_root = dirname(__DIR__);
chdir($yourls_root);

// Route to admin area
if ($path === 'admin' || $path === 'admin/' || strpos($path, 'admin/') === 0) {
    $admin_path = preg_replace('#^admin/?#', '', $path);
    $admin_file = $admin_path ?: 'index.php';
    if (!preg_match('#\.php$#', $admin_file)) {
        $admin_file = $admin_file . '.php';
    }
    $admin_files = ['index.php', 'install.php', 'upgrade.php', 'plugins.php', 'tools.php', 'admin-ajax.php'];
    if (in_array($admin_file, $admin_files) && file_exists($yourls_root . '/admin/' . $admin_file)) {
        require $yourls_root . '/admin/' . $admin_file;
        return;
    }
    // Fallback to index for admin/ with no specific file
    require $yourls_root . '/admin/index.php';
    return;
}

// Route to API
if ($path === 'yourls-api.php' || strpos($path, 'yourls-api') === 0) {
    require $yourls_root . '/yourls-api.php';
    return;
}

// Default: main loader (short URLs, front page, etc.)
require $yourls_root . '/yourls-loader.php';
