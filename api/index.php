<?php
/**
 * Vercel PHP Serverless Entrypoint - Sipaling Coffee
 * Handles HTML static + PHP (koneksi.php) routing
 */

$uri  = $_SERVER['REQUEST_URI'];
$path = ltrim(parse_url($uri, PHP_URL_PATH), '/');
$root = dirname(__DIR__);

// koneksi.php direct call (AJAX from contact form)
if ($path === 'koneksi.php') {
    chdir($root);
    require $root . '/koneksi.php';
    exit;
}

// Default to index.html
if (empty($path) || $path === '/') {
    $path = 'index.html';
}

$fullPath = $root . '/' . $path;

if (is_dir($fullPath)) {
    $fullPath = rtrim($fullPath, '/') . '/index.html';
    $path     = rtrim($path, '/')     . '/index.html';
}

$ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

if (file_exists($fullPath)) {
    if ($ext === 'php') {
        chdir(dirname($fullPath));
        require $fullPath;
    } elseif ($ext === 'html' || $ext === 'htm') {
        $html = file_get_contents($fullPath);

        // Inject back-to-portfolio banner into index.html only
        if (basename($fullPath) === 'index.html') {
            $banner = '<div style="background:#1a1a2e;color:#fff;padding:10px 20px;text-align:center;font-size:13px;line-height:1.6;z-index:9999;position:relative;">'
                . '<a href="https://wahyuidaya.github.io" style="color:#f5c518;text-decoration:none;font-weight:600;">← Kembali ke Portofolio Wahyu Idaya</a>'
                . '</div>';
            $html = str_replace('<body>', '<body>' . $banner, $html);
        }

        header('Content-Type: text/html; charset=utf-8');
        echo $html;
    } else {
        $mime = [
            'css'  => 'text/css', 'js' => 'application/javascript',
            'png'  => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif', 'svg' => 'image/svg+xml', 'ico' => 'image/x-icon',
            'woff' => 'font/woff', 'woff2' => 'font/woff2', 'ttf' => 'font/ttf',
            'webp' => 'image/webp',
        ];
        if (isset($mime[$ext])) header('Content-Type: ' . $mime[$ext]);
        readfile($fullPath);
    }
} else {
    // Fallback to index.html
    $html = file_get_contents($root . '/index.html');
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
}
