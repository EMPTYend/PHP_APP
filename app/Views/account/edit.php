<?php /** @var array $data */ ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Боковое меню (как в dashboard.php) -->
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Редактирование профиля</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="/account/update" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group mb-3">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($data['user']['name']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Телефон</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($data['user']['phone']) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>