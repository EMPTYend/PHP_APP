<div class="container mt-4">
    <!-- Flash сообщения -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2>Редактирование пользователя #<?= htmlspecialchars($data['user']['id_user']) ?></h2>
    
    <form method="POST" action="/admin/users/update?id=<?= $user['id_user'] ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($data['user']['name']) ?>" 
                   required minlength="2" maxlength="50">
            <div class="invalid-feedback">Имя должно содержать от 2 до 50 символов</div>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($data['user']['email']) ?>" 
                   required>
            <div class="invalid-feedback">Введите корректный email</div>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   value="<?= htmlspecialchars($data['user']['phone']) ?>"
                   pattern="\+?[\d\s\-\(\)]{7,20}">
            <div class="invalid-feedback">Введите корректный номер телефона</div>
        </div>
        
        <div class="mb-3">
            <label for="role" class="form-label">Роль</label>
            <select id="role" name="role" class="form-select" required>
                <option value="">-- Выберите роль --</option>
                <option value="user" <?= $data['user']['role'] === 'user' ? 'selected' : '' ?>>Пользователь</option>
                <option value="admin" <?= $data['user']['role'] === 'admin' ? 'selected' : '' ?>>Администратор</option>
            </select>
            <div class="invalid-feedback">Выберите роль пользователя</div>
        </div>
        
        <div class="d-grid gap-2 d-md-flex">
            <button type="submit" class="btn btn-primary me-md-2">
                <i class="fas fa-save"></i> Сохранить
            </button>
            <a href="/admin/users" class="btn btn-secondary">
                <i class="fas fa-times"></i> Отмена
            </a>
        </div>
    </form>
</div>

<!-- Валидация формы на клиенте -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
});
</script>