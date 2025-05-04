<?php
/** @var array $user */
/** @var array $bookings */
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет | <?= htmlspecialchars($user['name']) ?></title>
    <link href="/assets/css/account.css" rel="stylesheet">
</head>
<body>
    <div class="account-container">
        <aside class="account-sidebar">
            <div class="user-info">
                <h2><?= htmlspecialchars($user['name']) ?></h2>
                <p><?= htmlspecialchars($user['email']) ?></p>
                <p>Зарегистрирован: <?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
            </div>
            
            <nav class="account-menu">
                <a href="/account" class="active">Главная</a>
                <a href="/account/profile">Профиль</a>
                <a href="/account/bookings">Мои бронирования</a>
                <a href="/account/security">Безопасность</a>
                <a href="/logout">Выйти</a>
            </nav>
        </aside>

        <main class="account-content">
            <h1>Добро пожаловать в личный кабинет</h1>
            
            <section class="quick-info">
                <div class="info-card">
                    <h3>Активные бронирования</h3>
                    <p><?= count($bookings) ?></p>
                </div>
                
                <div class="info-card">
                    <h3>Статус аккаунта</h3>
                    <p><?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></p>
                </div>
            </section>
            
            <section class="recent-bookings">
                <h2>Последние бронирования</h2>
                <?php if (!empty($bookings)): ?>
                    <ul class="booking-list">
                        <?php foreach ($bookings as $booking): ?>
                            <li>
                                <span><?= htmlspecialchars($booking['type']) ?></span>
                                <span><?= date('d.m.Y', strtotime($booking['check_in'])) ?> - <?= date('d.m.Y', strtotime($booking['check_out'])) ?></span>
                                <span>Статус: <?= $getBookingStatus($booking['status']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>У вас пока нет бронирований</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>