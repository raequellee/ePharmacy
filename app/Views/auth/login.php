<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ePharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-capsule-pill text-primary" style="font-size: 2.5rem;"></i>
                <h4 class="fw-bold mt-2">ePharmacy</h4>
                <p class="text-muted small">Sistem Apotek Online</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>