<main>

  <section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="fw-light">Album example</h1>
        <p class="lead text-body-secondary">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
        <p>
          <a href="#" class="btn btn-primary my-2">Contact us</a>
          <form class="d-flex" role="search"> 
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"> 
            <button class="btn btn-outline-success" type="submit">Search</button> 
          </form>
        </p>
      </div>
    </div>
  </section>

  <div class="album py-5 bg-body-tertiary">
    <div class="container">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <div class="col">
        <?php foreach ($rooms as $room): ?>
          <div class="card shadow-sm mb-4">
            <img src="<?= htmlspecialchars($room['road']) ?>" class="card-img-top bd-placeholder-img" style="height: 225px; width: 100%; object-fit: cover;" alt="<?= htmlspecialchars($room['type']) ?>"><div class="card-body">
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
        <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

</main>