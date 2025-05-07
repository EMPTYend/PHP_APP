<?php if (isset($_SESSION['user'])): ?>
 <div class="container mt-4">
 <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['booking_errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php foreach ($_SESSION['booking_errors'] as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['booking_errors']); ?>
    <?php endif; ?>

    <h2>Форма бронирования</h2>
    
    <form method="POST" action="/booking" class="needs-validation" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <?php if (isset($_SESSION['user'])): ?><input type="hidden" name="id_user" value="<?= $_SESSION['user']['id_user'] ?? null ?>"><?php endif; ?>
        <div class="mb-3">
            <label for="name" class="form-label">ФИО</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($_SESSION['booking_old_data']['name'] ?? $_SESSION['user']['name'] ?? '') ?>" 
                   required minlength="3" maxlength="100">
            <div class="invalid-feedback">Введите ваше полное имя (3-100 символов)</div>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($_SESSION['booking_old_data']['email'] ?? $_SESSION['user']['email'] ?? '') ?>" 
                   required>
            <div class="invalid-feedback">Введите корректный email</div>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   value="<?= htmlspecialchars($_SESSION['booking_old_data']['phone'] ?? $_SESSION['user']['phone'] ?? '') ?>"
                   pattern="\+?[\d\s\-\(\)]{7,20}" required>
            <div class="invalid-feedback">Введите корректный номер телефона</div>
        </div>
        
        <div class="mb-3">
            <label for="type" class="form-label">Тип номера</label>
            <select id="type" name="type" class="form-select" required>
                <option value="">-- Выберите тип --</option>
                <option value="Стандарт" <?= ($_SESSION['booking_old_data']['type'] ?? '') === 'Стандарт' ? 'selected' : '' ?>>Стандарт</option>
                <option value="Комфорт" <?= ($_SESSION['booking_old_data']['type'] ?? '') === 'Комфорт' ? 'selected' : '' ?>>Комфорт</option>
                <option value="Люкс" <?= ($_SESSION['booking_old_data']['type'] ?? '') === 'Люкс' ? 'selected' : '' ?>>Люкс</option>
            </select>
            <div class="invalid-feedback">Выберите тип номера</div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="peoples" class="form-label">Количество человек</label>
                <input type="number" id="peoples" name="peoples" class="form-control" 
                       value="<?= htmlspecialchars($_SESSION['booking_old_data']['peoples'] ?? 1) ?>"
                       min="1" max="10" required>
                <div class="invalid-feedback">Укажите количество гостей (1-10)</div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="check_in" class="form-label">Дата заезда</label>
                <input type="date" id="check_in" name="check_in" class="form-control" 
                       value="<?= htmlspecialchars($_SESSION['booking_old_data']['check_in'] ?? '') ?>"
                       required>
                <div class="invalid-feedback">Укажите дату заезда</div>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="check_out" class="form-label">Дата выезда</label>
                <input type="date" id="check_out" name="check_out" class="form-control" 
                       value="<?= htmlspecialchars($_SESSION['booking_old_data']['check_out'] ?? '') ?>"
                       required>
                <div class="invalid-feedback">Укажите дату выезда</div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="comments" class="form-label">Дополнительные пожелания</label>
            <textarea id="comments" name="comments" class="form-control" rows="3"><?= 
                htmlspecialchars($_SESSION['booking_old_data']['comments'] ?? '') 
            ?></textarea>
        </div>
        
        <div class="d-grid gap-2 d-md-flex">
            <button type="submit" class="btn btn-primary me-md-2">
                <i class="fas fa-calendar-check"></i> Забронировать
            </button>
            <a href="/" class="btn btn-secondary">
                <i class="fas fa-times"></i> Отмена
            </a>
        </div>
    </form>
</div>

<!-- Валидация формы на клиенте -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    
    // Установка минимальной даты заезда (сегодня)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('check_in').min = today;
    
    // Обновление минимальной даты выезда при изменении даты заезда
    document.getElementById('check_in').addEventListener('change', function() {
        document.getElementById('check_out').min = this.value;
    });

    // Проверка что дата выезда после даты заезда
document.getElementById('check_out').addEventListener('change', function() {
    const checkIn = document.getElementById('check_in').value;
    if (this.value && checkIn && this.value <= checkIn) {
        this.setCustomValidity('Дата выезда должна быть после даты заезда');
    } else {
        this.setCustomValidity('');
    }
    });
});
</script>

<?php else: ?>
<div class="alert alert-warning">
    <h4>Для бронирования необходимо авторизоваться</h4>
    <p>Пожалуйста, <a href="/login">войдите</a> или <a href="/register">зарегистрируйтесь</a>.</p>
</div>
<?php endif; ?>