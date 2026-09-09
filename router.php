<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve requested resource as-is if it exists (images, css, js, etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false;
}

// Strip leading slash
$path = ltrim($uri, '/');

if ($path === '' || $path === 'index.php') {
    require __DIR__ . '/index.php';
    exit;
}

// Category routing: /category/exomind -> category__exomind.php
if (preg_match('#^category/(.+)$#', $path, $m)) {
    $file = __DIR__ . '/category__' . $m[1] . '.php';
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// Tag routing: /tag/xxx -> tag__xxx.php
if (preg_match('#^tag/(.+)$#', $path, $m)) {
    $file = __DIR__ . '/tag__' . $m[1] . '.php';
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// Author routing: /author/xxx -> author__adminbraintune.php
if (preg_match('#^author(?:/.*)?$#', $path)) {
    $file = __DIR__ . '/author__adminbraintune.php';
    if (file_exists($file)) {
        require $file;
        exit;
    }
}

// Extensionless PHP file rewrite: e.g. /blog -> blog.php, /does-exomind-therapy-really-work -> does-exomind-therapy-really-work.php
if (file_exists(__DIR__ . '/' . $path . '.php')) {
    require __DIR__ . '/' . $path . '.php';
    exit;
}

return false;
