<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title><?= $title ?? 'EmptySun' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .auth-items {
            margin-right: 50px;
            margin-bottom: 0px;
        }
        .dl, ol, ul {
        margin-top: 0;
        margin-bottom: 0rem;
        }
    </style>
</head>
<body>
    <header data-bs-theme="dark">
        <div class="text-bg-dark collapse" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4>About</h4>
                        <p class="text-body-secondary">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4>Contact</h4>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Follow on X</a></li>
                            <li><a href="#" class="text-white">Like on Facebook</a></li>
                            <li><a href="#" class="text-white">Email me</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="/" class="navbar-brand d-flex align-items-center">
                    <strong>EmptySun</strong>
                </a>
                
                <div class="d-flex align-items-center">
                    <ul class="navbar-nav auth-items">
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <?= htmlspecialchars($_SESSION['user']['name']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="/account">Личный кабинет</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/logout">Выход</a></li>
                                    
                                    
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="nav-link" href="/admin/users">| Управление аккаунтами |</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/login">Вход</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/register">Регистрация</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user'): ?>
                    <ul>
                        <li class="nav-item auth-items" >
                                <a class="btn btn-primary" href="/booking">Забронировать номер</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <ul>
                        <li class="nav-item auth-items" >
                                <<a href="/admin/rooms/create" class="btn btn-primary">Загрузить номер</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <main>
        <?= $content ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>