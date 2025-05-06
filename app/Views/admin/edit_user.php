<div class="container mt-5">
    <h2>Edit User</h2>
    <form action="/admin/users/update/<?= urlencode($data['user']['email']) ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['user']['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['user']['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['user']['phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="user" <?= $data['user']['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $data['user']['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>