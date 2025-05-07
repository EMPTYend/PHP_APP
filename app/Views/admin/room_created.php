<?php require 'layout/header.php'; ?>

<div class="container my-5">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4><?= htmlspecialchars($success) ?></h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Room Details</h5>
            <p class="card-text">
                <strong>ID:</strong> <?= htmlspecialchars($roomId) ?><br>
                <strong>Type:</strong> <?= htmlspecialchars($roomData['type']) ?><br>
                <strong>Price:</strong> $<?= htmlspecialchars($roomData['price']) ?><br>
                <strong>Description:</strong> <?= htmlspecialchars($roomData['description']) ?>
            </p>

            <?php if (!empty($uploadedImages)): ?>
                <h5 class="mt-4">Uploaded Images</h5>
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($uploadedImages as $image): ?>
                        <img src="/<?= htmlspecialchars($image) ?>" alt="Room image" class="img-thumbnail" style="max-height: 150px;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="/admin/rooms/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create Another Room
            </a>
            <a href="/admin/rooms" class="btn btn-outline-primary">
                <i class="bi bi-list"></i> View All Rooms
            </a>
        </div>
    </div>
</div>

<?php require 'layout/footer.php'; ?>