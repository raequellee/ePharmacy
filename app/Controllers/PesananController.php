<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\ApiConfig;

class PesananController extends BaseController
{
    protected $apiConfig;

    public function __construct()
    {
        $this->apiConfig = new ApiConfig();
    }

    public function index()
    {
        // Data dari session (diset saat user pilih obat / resep approved)
        $data['keranjang'] = session()->get('keranjang') ?? [];
        $data['resep']     = session()->get('resep_approved') ?? null;
        return view('pesanan/konfirmasi', $data);
    }

    public function bayar()
    {
        $client = \Config\Services::curlrequest([
            'headers' => [
                'X-Tunnel-Skip-AntiPhishing-Page' => 'true'
                ]
            ]);
        $apiConfig = $this->apiConfig;

        $keranjang = session()->get('keranjang') ?? [];
        $resepId   = $this->request->getPost('resep_id');
        $alamat    = $this->request->getPost('alamat');

        // 1. Kurangi stok di SIO
        foreach ($keranjang as $item) {
            $client->post($apiConfig->sioBaseUrl . '/obat/' . $item['id'] . '/kurangi-stok', [
                'json' => ['jumlah' => $item['jumlah']],
            ]);
        }

        // 2. Buat pengiriman di SLP
        $responseSLP = $client->post($apiConfig->slpBaseUrl . '/pengiriman', [
            'json' => [
                'resep_id'  => $resepId,
                'alamat'    => $alamat,
                'items'     => $keranjang,
                'nama'      => session()->get('username'),
            ],
        ]);

        $bodySLP = json_decode($responseSLP->getBody(), true);

        if ($responseSLP->getStatusCode() === 200 || $responseSLP->getStatusCode() === 201) {
            $noResi = $bodySLP['no_resi'] ?? $bodySLP['resi'] ?? null;
            session()->remove('keranjang');
            return redirect()->to('/pengiriman/' . $noResi)
                             ->with('success', 'Pembayaran berhasil! Pesanan sedang diproses.');
        }

        return redirect()->back()->with('error', 'Gagal membuat pesanan pengiriman.');
    }
}