<?php
// api/add_idea.php — Insert a new reuse idea
// POST: multipart/form-data with optional image upload

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonOut(['error' => 'POST only'], 405);
}

// ── Collect & validate ───────────────────────────────────────────
$required = ['title', 'category', 'difficulty', 'time_required', 'description', 'materials', 'author'];
$data = [];

foreach ($required as $f) {
    $val = trim($_POST[$f] ?? '');
    if ($val === '') jsonOut(['error' => "Field '$f' is required."], 422);
    $data[$f] = clean($val);
}

// Validate enums
if (!in_array($data['category'], categories(), true)) {
    jsonOut(['error' => 'Invalid category.'], 422);
}
if (!in_array($data['difficulty'], ['Easy', 'Medium', 'Hard'], true)) {
    jsonOut(['error' => 'Invalid difficulty.'], 422);
}

// Steps — sent as steps[] array OR as JSON string
$stepsRaw = $_POST['steps'] ?? '';
if (is_array($stepsRaw)) {
    $steps = array_values(array_filter(array_map('trim', $stepsRaw), fn($s) => $s !== ''));
} else {
    // Try JSON decode, fallback to treating it as a single step
    $decoded = json_decode($stepsRaw, true);
    $steps   = is_array($decoded) ? $decoded : [$stepsRaw];
}

if (empty($steps)) {
    jsonOut(['error' => 'At least one step is required.'], 422);
}

$stepsJson = json_encode($steps, JSON_UNESCAPED_UNICODE);

// ── Image upload ─────────────────────────────────────────────────
$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file     = $_FILES['image'];
    $maxBytes = MAX_UPLOAD_MB * 1024 * 1024;
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if ($file['size'] > $maxBytes) {
        jsonOut(['error' => 'Image must be under ' . MAX_UPLOAD_MB . 'MB.'], 422);
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed, true)) {
        jsonOut(['error' => 'Only JPG, PNG, WEBP, GIF images are allowed.'], 422);
    }

    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }

    $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename  = 'idea_' . uniqid() . '.' . $ext;
    $destPath  = UPLOADS_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        jsonOut(['error' => 'Failed to save image.'], 500);
    }

    $imagePath = UPLOADS_URL . $filename;
}

// ── Insert ───────────────────────────────────────────────────────
try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("
        INSERT INTO reuse_ideas
            (title, category, difficulty, time_required, description,
             materials, steps, image, author, is_approved)
        VALUES
            (:title, :cat, :diff, :time, :desc, :mat, :steps, :img, :author, 0)
    ");
    $stmt->execute([
        ':title'  => $data['title'],
        ':cat'    => $data['category'],
        ':diff'   => $data['difficulty'],
        ':time'   => $data['time_required'],
        ':desc'   => $data['description'],
        ':mat'    => $data['materials'],
        ':steps'  => $stepsJson,
        ':img'    => $imagePath,
        ':author' => $data['author'],
    ]);

    $newId = (int)$pdo->lastInsertId();
    jsonOut(['success' => true, 'id' => $newId, 'message' => 'Idea submitted successfully!']);

} catch (Exception $e) {
    jsonOut(['error' => 'Database error: ' . $e->getMessage()], 500);
}
