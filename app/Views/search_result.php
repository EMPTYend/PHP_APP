<h1>Результаты поиска</h1>

<div class="album py-5 bg-body-tertiary">
    <div class="container">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
      <?php foreach ($rooms as $room): ?>
      <div class="col">
        <div class="card shadow-sm mb-4">
        <img src="<?= htmlspecialchars($room['road']) ?>" class="card-img-top bd-placeholder-img" style="height: 225px; width: 100%; object-fit: cover;" alt="<?= htmlspecialchars($room['type']) ?>">
        <div class="card-body">
          <p class="card-text"><?= htmlspecialchars($room['type']) ?></p>
          <p class="card-text"><?= htmlspecialchars($room['description']) ?></p>
          <p class="card-text"><?= htmlspecialchars($room['price']) ?> USD</p>
          <div class="d-flex justify-content-between align-items-center">
          <div class="btn-group">
            <a href="/room?id=<?= htmlspecialchars($room['id_room']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
            <button type="button" class="btn btn-sm btn-outline-secondary">Contact</button>
          </div>
          <small class="text-body-secondary"><?= date('H:i', strtotime($room['created_at'])) ?></small>
          </div>
        </div>
        </div>
      </div>
      <?php endforeach; ?>
      </div>
    </div>
</div>