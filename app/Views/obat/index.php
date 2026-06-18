<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold"><i class="bi bi-search"></i> Cari Obat</h4>
</div>

<form method="GET" action="<?= site_url('obat/search') ?>" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control form-control-lg"
               placeholder="Cari nama obat..." value="<?= esc($keyword ?? '') ?>">
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-search"></i> Cari
        </button>
        <?php if (!empty($keyword)): ?>
            <a href="<?= site_url('obat') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-wifi-off"></i> <?= $error ?></div>
<?php elseif (empty($obat_list)): ?>
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> Tidak ada obat ditemukan.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($obat_list as $obat): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
    <h6 class="card-title fw-bold">
    <?= esc(
        $obat['openfda']['brand_name'][0] 
        ?? $obat['openfda']['generic_name'][0] 
        ?? 'Obat Tanpa Nama'
    ) ?>
</h6>
    <p class="text-muted small mb-1">
        <i class="bi bi-tag"></i> <?= esc($obat['openfda']['product_type'][0] ?? '-') ?>
    </p>
    <p class="small mb-1">
        <i class="bi bi-capsule"></i> <?= esc($obat['openfda']['route'][0] ?? '-') ?>
    </p>
    <p class="small mb-2">
        <i class="bi bi-building"></i> <?= esc($obat['openfda']['manufacturer_name'][0] ?? '-') ?>
    </p>
    <span class="badge bg-success">Tersedia</span>
</div>
<div class="card-footer bg-transparent">
    <form method="POST" action="<?= base_url('obat/pesan') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="obat_id" value="<?= esc($obat['openfda']['product_ndc'][0] ?? uniqid()) ?>">
        <input type="hidden" name="nama_obat" value="<?= esc($obat['openfda']['brand_name'][0] ?? $obat['openfda']['generic_name'][0] ?? 'Unknown') ?>">
        <input type="hidden" name="manufacturer" value="<?= esc($obat['openfda']['manufacturer_name'][0] ?? '-') ?>">
        <input type="hidden" name="route" value="<?= esc($obat['openfda']['route'][0] ?? '-') ?>">
        <button type="submit" class="btn btn-sm btn-outline-primary w-100">
            <i class="bi bi-cart"></i> Pesan
        </button>
    </form>
</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>