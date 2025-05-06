<div class="container mt-5">
    <!-- Добавим сообщения об успехе/ошибке -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2>User Management</h2>
    
    <div class="table-responsive">  <!-- Для горизонтального скролла на мобильных -->
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id_user']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td class="text-nowrap">  <!-- Предотвращает перенос строк -->
                        <!-- Иконки вместо текста для компактности -->
                        <a href="/admin/users/edit?id=<?= $user['id_user'] ?>" 
                           class="btn btn-sm btn-primary" 
                           title="Редактировать">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        
                        <form action="/admin/users/delete" method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= $user['id_user'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <button type="submit" 
                                    class="btn btn-sm btn-danger" 
                                    title="Удалить"
                                    onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                <i class="fas fa-trash-alt"></i> Анигилировать
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>