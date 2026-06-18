<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>

<h3 class="mb-3">Cari Obat</h3>

<div class="row">
    <?php foreach ($obat as $item): ?>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $item['drug_name'] ?></h5>
                <p class="card-text">
                    Stok: <?= $item['stock_quantity'] ?> <?= $item['unit'] ?><br>
                    Harga: Rp<?= number_format($item['unit_price'], 0, ',', '.') ?>
                </p>
                <button class="btn btn-primary">Pilih</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>