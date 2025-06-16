<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $user_id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $msg = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Post</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 24px #ccc; }
        h2 { text-align: center; color: #222; margin-bottom: 30px; }
        form { display: flex; flex-direction: column; }
        input, textarea { margin-bottom: 18px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1em; }
        textarea { resize: vertical; min-height: 100px; }
        .btn {
            background: #007acc;
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover { background: #005fa3; }
        .msg { color: #b00; text-align: center; margin-bottom: 15px; }
        .back-link { display: block; text-align: center; margin-top: 18px; color: #007acc; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Post</h2>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" placeholder="Write your post here..." required></textarea>
            <button class="btn" type="submit">Publish</button>
        </form>
        <a class="back-link" href="index.php">&larr; Back to Blog</a>
    </div>
</body>
</html>