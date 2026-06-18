<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="text-center py-5">
    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
    <h3 class="fw-bold mt-3">Pembayaran Berhasil!</h3>
    <p class="text-muted">Order ID: <?= esc($order_id ?? '-') ?></p>
    <p class="text-muted">Status: <?= esc($status ?? '-') ?></p>
    <a href="<?= site_url('pengiriman') ?>" class="btn btn-primary mt-3">
        <i class="bi bi-truck"></i> Lacak Pengiriman
    </a>
</div>

<?= $this->endSection() ?>