<?php
require_once __DIR__ . '/../../../backend/app.php';

// Если запрос не API, отображаем HTML
if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель "Премиум"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Отель "Премиум"</a>
            <div class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="/profile">Профиль</a>
                    <a class="nav-link" href="/logout">Выйти</a>
                <?php else: ?>
                    <a class="nav-link" href="/login">Войти</a>
                    <a class="nav-link" href="/register">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="app">
            <!-- React/Vue компоненты будут здесь -->
            <h1>Добро пожаловать в наш отель!</h1>
            <div class="row">
                <div class="col-md-8">
                    <div id="booking-form"></div>
                    <div id="rooms-list"></div>
                </div>
                <div class="col-md-4">
                    <div id="special-offers"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>
<?php
}
?>