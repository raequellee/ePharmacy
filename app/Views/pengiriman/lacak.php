<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h4 class="fw-bold mb-4"><i class="bi bi-truck"></i> Lacak Pengiriman</h4>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <?php if (!empty($order_id)): ?>
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-2 fw-bold">Pembayaran Berhasil!</h5>
                        <p class="text-muted">Pesanan sedang diproses</p>
                    </div>

                    <table class="table table-borderless">
                        <tr><th>Order ID</th><td><span class="badge bg-primary"><?= esc($order_id) ?></span></td></tr>
                        <tr><th>Kurir</th><td><?= esc($kurir ?? '-') ?></td></tr>
                        <tr><th>Penerima</th><td><?= esc($nama_penerima ?? '-') ?></td></tr>
                        <tr><th>Alamat</th><td><?= esc($alamat ?? '-') ?></td></tr>
                        <tr>
                            <th>Status</th>
                            <td><span class="badge bg-warning">Menunggu Pengambilan</span></td>
                        </tr>
                    </table>

                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        No. resi akan dikirim via WhatsApp/SMS setelah paket diambil kurir.
                    </div>

                <?php else: ?>
                    <form method="GET" action="<?= base_url('pengiriman/cari') ?>">
                        <label class="form-label fw-semibold">Cek Status via Order ID</label>
                        <div class="input-group">
                            <input type="text" name="order_id" class="form-control form-control-lg"
                                   placeholder="Masukkan Order ID (cth: EPH-1234567890)" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Cek
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <a href="<?= base_url('obat') ?>" class="btn btn-outline-secondary mt-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Cari Obat
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>