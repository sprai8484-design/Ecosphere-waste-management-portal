<?php
// api/submit_blog.php — Insert a new blog (status = pending)
// POST multipart/form-data: title, content, author_name, email, image(optional)

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonOut(['error' => 'POST only'], 405);

// ── Collect fields ────────────────────────────────────────────────
$title       = trim($_POST['title']       ?? '');
$content     = trim($_POST['content']     ?? '');
$author_name = trim($_POST['author_name'] ?? '');
$email       = trim($_POST['email']       ?? '');

if (!$title)       jsonOut(['error' => 'Title is required.'], 422);
if (!$content)     jsonOut(['error' => 'Blog content is required.'], 422);
if (!$author_name) jsonOut(['error' => 'Author name is required.'], 422);
if (!$email)       jsonOut(['error' => 'Email is required.'], 422);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonOut(['error' => 'Please enter a valid email address.'], 422);
}
if (mb_strlen($title) < 10) {
    jsonOut(['error' => 'Title must be at least 10 characters.'], 422);
}
if (mb_strlen(strip_tags($content)) < 100) {
    jsonOut(['error' => 'Blog content must be at least 100 characters.'], 422);
}

// ── Waste-management relevance check ─────────────────────────────
if (!isWasteRelated($title, $content)) {
    jsonOut([
        'error' => 'Your blog does not appear to be related to waste management, recycling, sustainability, or environmental topics. Please revise your content and ensure it covers these themes.'
    ], 422);
}

// ── Image upload ──────────────────────────────────────────────────
$imagePath = null;
try {
    if (!empty($_FILES['image'])) {
        $imagePath = handleImageUpload($_FILES['image']);
    }
} catch (RuntimeException $e) {
    jsonOut(['error' => $e->getMessage()], 422);
}

// ── Insert ────────────────────────────────────────────────────────
try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        INSERT INTO blogs (title, content, image, author_name, email, status)
        VALUES (:title, :content, :image, :author, :email, 'pending')
    ");
    $stmt->execute([
        ':title'   => clean($title),
        ':content' => $content,        // Allow HTML from textarea (rich content)
        ':image'   => $imagePath,
        ':author'  => clean($author_name),
        ':email'   => clean($email),
    ]);

    $newId = (int)$pdo->lastInsertId();
    jsonOut(['success' => true, 'id' => $newId, 'message' => 'Blog submitted successfully! It will appear publicly after admin review.']);

} catch (Exception $e) {
    jsonOut(['error' => 'Database error: ' . $e->getMessage()], 500);
}
