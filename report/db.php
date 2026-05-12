<?php
// ── Database credentials ──────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecosphere_db');

// ── PDO singleton ─────────────────────────────────────────
function db(): PDO
{
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    return $pdo;
}

// ── Output-safe string ────────────────────────────────────
function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// ── Upload helper (returns filename or null) ──────────────
function uploadImage(array $file, string $dir = 'uploads/'): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowed)) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null;   // 5 MB max
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = uniqid('img_', true) . '.' . $ext;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    move_uploaded_file($file['tmp_name'], $dir . $name);
    return $name;
}

/*
 ┌─────────────────────────────────────────────────────────┐
 │  SQL — run once in phpMyAdmin → SQL tab                 │
 ├─────────────────────────────────────────────────────────┤
 │                                                         │
 │  CREATE DATABASE IF NOT EXISTS ecosphere_waste          │
 │    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;    │
 │  USE ecosphere_waste;                                   │
 │                                                         │
 │  CREATE TABLE reports (                                 │
 │    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, │
 │    reporter    VARCHAR(120) DEFAULT 'Anonymous',        │
 │    location    VARCHAR(300) NOT NULL,                   │
 │    description TEXT         NOT NULL,                   │
 │    image       VARCHAR(255) DEFAULT NULL,               │
 │    status ENUM('pending','approved','solved','rejected') │
 │           NOT NULL DEFAULT 'pending',                   │
 │    solution_image VARCHAR(255) DEFAULT NULL,            │
 │    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP      │
 │  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;               │
 │                                                         │
 └─────────────────────────────────────────────────────────┘
*/
