<?php /** @var array $data */ ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Меню</h5>
                </div>
                <div class="card-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/account/edit">Редактировать профиль</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Выход</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Личный кабинет</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <h5>Добро пожаловать, <?= htmlspecialchars($data['user']['name']) ?>!</h5>
                    <p><strong>Email:</strong> <?= htmlspecialchars($data['user']['email']) ?></p>
                    <p><strong>Телефон:</strong> <?= htmlspecialchars($data['user']['phone']) ?></p>
                    <p><strong>Роль:</strong> <?= $data['user']['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>