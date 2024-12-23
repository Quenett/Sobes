<?php
require 'db.php';

global $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'add' && !empty($_POST['username']) && !empty($_POST['comment'])) {
        $username = htmlspecialchars(trim($_POST['username']));
        $comment = htmlspecialchars(trim($_POST['comment']));
        $stmt = $pdo->prepare("INSERT INTO comments (username, comment) VALUES (:username, :comment)");
        $stmt->execute([':username' => $username, ':comment' => $comment]);
        header("Location: Zad3.php");
        exit();
    } elseif ($_POST['action'] == 'edit' && !empty($_POST['comment_id']) && !empty($_POST['comment'])) {
        $comment_id = $_POST['comment_id'];
        $comment = htmlspecialchars(trim($_POST['comment']));
        $stmt = $pdo->prepare("UPDATE comments SET comment = :comment WHERE id = :id");
        $stmt->execute([':comment' => $comment, ':id' => $comment_id]);
        header("Location: Zad3.php");
        exit();
    }
}

if (isset($_GET['delete'])) {
    $comment_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
    $stmt->execute([':id' => $comment_id]);
}

$stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at ASC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комментарии</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Список комментариев</h1>
<div class="div_form">
    <form method="post">
        <div class="div_flex">
            <input class="class_input" type="text" name="username" placeholder="Ваше имя" required>
            <textarea class="class_input" name="comment" placeholder="Ваш комментарий" required></textarea>
            <input type="hidden" name="action" value="add">
            <button class="button" type="submit">Добавить комментарий</button>
        </div>
    </form>
</div>

<h2>Комментарии:</h2>
<ul class="class_list">
    <?php foreach ($comments as $comment): ?>
        <li class="class_list_item">
            <strong class="class_strong"><?php echo htmlspecialchars($comment['username']); ?>:</strong>
            <p class="class_p"><?php echo htmlspecialchars($comment['comment']); ?></p>
            <small class="class_small"><?php echo $comment['created_at']; ?></small>
            <div class="div_flex">
                <form class="class_margin_bottom" method="post" style="display:inline;">
                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                    <input type="text" name="comment" value="<?php echo htmlspecialchars($comment['comment']); ?>" required>
                    <input type="hidden" name="action" value="edit">
                    <button class="button" type="submit">Редактировать</button>
                </form>
                <a class="button class_href" href="?delete=<?php echo $comment['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот комментарий?');">Удалить</a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
