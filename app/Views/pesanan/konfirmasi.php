<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<h4 class="fw-bold mb-4"><i class="bi bi-cart-check"></i> Konfirmasi & Pembayaran</h4>

<div class="row">
    <div class="col-md-8">

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">Obat yang Dipesan</div>
            <div class="card-body">
                <h5 class="fw-bold"><?= esc($obat['nama'] ?? '-') ?></h5>
                <p class="text-muted mb-1"><i class="bi bi-capsule"></i> <?= esc($obat['route'] ?? '-') ?></p>
                <p class="text-muted mb-1"><i class="bi bi-building"></i> <?= esc($obat['manufacturer'] ?? '-') ?></p>
                <p class="fw-bold text-primary mt-2">Rp <?= number_format($obat['harga'] ?? 50000, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">Cek Ongkos Kirim</div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('pesanan/cek-ongkir') ?>" id="form-ongkir">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kota Asal</label>
                            <input type="text" id="origin_search" class="form-control" placeholder="Ketik nama kota..." autocomplete="off">
                            <div id="origin_suggestions" class="list-group mt-1" style="position:absolute;z-index:999;width:100%"></div>
                            <input type="hidden" name="origin" id="origin_id">
                            <small class="text-muted">Ketik min. 2 huruf, lalu klik salah satu kota yang muncul di bawah</small>
                            <br>
                            <small class="text-success" id="origin_label"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota Tujuan</label>
                            <input type="text" id="destination_search" class="form-control" placeholder="Ketik nama kota..." autocomplete="off">
                            <div id="destination_suggestions" class="list-group mt-1" style="position:absolute;z-index:999;width:100%"></div>
                            <input type="hidden" name="destination" id="destination_id">
                            <small class="text-muted">Ketik min. 2 huruf, lalu klik salah satu kota yang muncul di bawah</small>
                            <br>
                            <small class="text-success" id="destination_label"></small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" name="weight" class="form-control" value="300" min="1" required>
                        </div>
                        <div class="col-md-8 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search"></i> Cek Ongkir
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($ongkir_list)): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">Pilih Kurir & Data Pengiriman</div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('pesanan/bayar') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="ongkir" id="ongkir_val" value="0">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Penerima</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No HP</label>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control" rows="2" required></textarea>
                    </div>

                    <label class="form-label fw-semibold">Pilih Kurir</label>
                    <div class="row g-2 mb-3">
                        <?php foreach ($ongkir_list as $i => $kurir): ?>
                        <div class="col-md-6">
                            <div class="form-check border rounded p-3">
                                <input class="form-check-input" type="radio"
                                    name="kurir"
                                    value="<?= esc($kurir['courier_name'] ?? $kurir['name'] ?? '-') ?>"
                                    data-ongkir="<?= $kurir['cost'][0]['value'] ?? $kurir['price'] ?? 0 ?>"
                                    id="kurir_<?= $i ?>" required>
                                <label class="form-check-label w-100" for="kurir_<?= $i ?>">
                                    <strong><?= esc($kurir['courier_name'] ?? $kurir['name'] ?? '-') ?></strong>
                                    <span class="d-block text-muted small"><?= esc($kurir['courier_service_name'] ?? $kurir['service'] ?? '-') ?></span>
                                    <span class="d-block text-primary fw-bold">
                                        Rp <?= number_format($kurir['cost'][0]['value'] ?? $kurir['price'] ?? 0, 0, ',', '.') ?>
                                    </span>
                                    <span class="d-block text-muted small">Estimasi: <?= esc($kurir['cost'][0]['etd'] ?? $kurir['etd'] ?? '-') ?></span>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-credit-card"></i> Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Ringkasan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Harga Obat</span>
                    <span>Rp <?= number_format($obat['harga'] ?? 50000, 0, ',', '.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Ongkos Kirim</span>
                    <span id="total-ongkir">Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span id="grand-total">Rp <?= number_format($obat['harga'] ?? 50000, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const hargaObat = <?= $obat['harga'] ?? 50000 ?>;

// Update total saat pilih kurir
document.querySelectorAll('input[name="kurir"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const ongkir = parseInt(this.dataset.ongkir) || 0;
        document.getElementById('ongkir_val').value = ongkir;
        document.getElementById('total-ongkir').textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
        document.getElementById('grand-total').textContent = 'Rp ' + (hargaObat + ongkir).toLocaleString('id-ID');
    });
});

// Cegah submit "Cek Ongkir" kalau kota belum dipilih dari dropdown
document.getElementById('form-ongkir').addEventListener('submit', function(e) {
    const originId      = document.getElementById('origin_id').value;
    const destinationId = document.getElementById('destination_id').value;

    if (!originId || !destinationId) {
        e.preventDefault();
        alert('Pilih kota dari daftar saran yang muncul di bawah kotak teks, jangan langsung tekan Cek Ongkir.');
    }
});

// Autocomplete kota
function setupAutocomplete(inputId, suggestionsId, hiddenId, labelId) {
    const input       = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    const hidden      = document.getElementById(hiddenId);
    const label       = document.getElementById(labelId);
    let timeout;

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const keyword = this.value;
        if (keyword.length < 2) { suggestions.innerHTML = ''; return; }

        timeout = setTimeout(() => {
            fetch(`<?= base_url('pesanan/search-kota') ?>?keyword=` + encodeURIComponent(keyword))
                .then(r => r.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    const items = data.data ?? [];
                    items.slice(0, 5).forEach(item => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action small';
                        btn.textContent = item.label ?? item.subdistrict_name ?? item.city_name ?? '-';
                        btn.addEventListener('click', () => {
                            input.value  = btn.textContent;
                            hidden.value = item.id ?? item.subdistrict_id;
                            label.textContent = '✓ Terpilih';
                            suggestions.innerHTML = '';
                        });
                        suggestions.appendChild(btn);
                    });
                })
                .catch(() => {});
        }, 400);
    });

    document.addEventListener('click', e => {
        if (!input.contains(e.target)) suggestions.innerHTML = '';
    });
}

setupAutocomplete('origin_search',      'origin_suggestions',      'origin_id',      'origin_label');
setupAutocomplete('destination_search', 'destination_suggestions', 'destination_id', 'destination_label');
</script>

<?= $this->endSection() ?>