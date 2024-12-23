<?php
session_start(); // Начинаем сессию

// Простое имя пользователя и пароль (можно заменить на данные из базы данных)
$valid_username = "admin";
$valid_password = "password"; // В реальных приложениях используйте хэширование паролей

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка на корректность логина и пароля
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true; // Устанавливаем флаг успешного входа
        header("Location: admin.php"); // Перенаправляем на админ панель
        exit;
    } else {
        $error_message = "Неправильное имя пользователя или пароль."; // Сообщение об ошибке
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма Входа</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4">Вход в Админ Панель</h1>
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>
