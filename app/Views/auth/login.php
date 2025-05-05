<?php /** @var array $data */ ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($data['error'])): ?>
                        <div class="alert alert-danger"><?= $data['error'] ?></div>
                    <?php endif; ?>
                    
                    <form action="/login" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="/register">Don't have an account? Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>