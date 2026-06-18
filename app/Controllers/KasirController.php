<?php
namespace App\Controllers;

class KasirController extends BaseController
{
    public function index()
    {
        $obat = session()->get('obat_dipilih');

        if (!$obat) {
            return redirect()->to(base_url('obat'))->with('error', 'Pilih obat terlebih dahulu.');
        }

        $data = [
            'obat'        => $obat,
            'ongkir_list' => session()->get('ongkir_list') ?? [],
            'error'       => null,
        ];

        return view('pesanan/konfirmasi', $data);
    }

    public function searchKota()
    {
        $keyword = $this->request->getGet('keyword');
        $client  = \Config\Services::curlrequest();
        $apiKey  = getenv('KOMERCE_SHIPPING_KEY');

        try {
            $response = $client->request('GET', 'https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                'headers'     => ['x-api-key' => $apiKey],
                'query'       => ['keyword' => $keyword],
                'timeout'     => 10,
                'http_errors' => false,
            ]);
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setJSON(['data' => []]);
        }
    }

    public function cekOngkir()
    {
        $origin      = $this->request->getPost('origin');
        $destination = $this->request->getPost('destination');
        $weight      = $this->request->getPost('weight') ?? 300;

        $client  = \Config\Services::curlrequest();
        $apiKey  = getenv('KOMERCE_SHIPPING_KEY');

        try {
            $response = $client->request('POST', 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'headers' => [
                    'key' => $apiKey,
                ],
                'form_params' => [
                    'origin'      => $origin,
                    'destination' => $destination,
                    'weight'      => (int)$weight,
                    'courier'     => 'jne:j&t:sicepat',
                    'price'       => 'lowest',
                ],
                'timeout'     => 15,
                'http_errors' => false,
            ]);

            $body = json_decode($response->getBody(), true);

            if ($response->getStatusCode() == 200) {
                session()->set('ongkir_list', $body['data'] ?? []);
                session()->set('alamat', [
                    'origin'      => $origin,
                    'destination' => $destination,
                    'weight'      => $weight,
                ]);
            } else {
                session()->setFlashdata('error', 'Gagal cek ongkir: ' . ($body['meta']['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal cek ongkir: ' . $e->getMessage());
        }

        return redirect()->to(base_url('pesanan/konfirmasi'));
    }

    public function bayar()
    {
        $obat          = session()->get('obat_dipilih');
        $kurir         = $this->request->getPost('kurir');
        $ongkir        = $this->request->getPost('ongkir');
        $nama          = $this->request->getPost('nama');
        $hp            = $this->request->getPost('no_hp');
        $alamatLengkap = $this->request->getPost('alamat_lengkap');

        $serverKey = getenv('MIDTRANS_SERVER_KEY');
        $orderId   = 'EPH-' . time();
        $total     = ($obat['harga'] ?? 50000) + (int)$ongkir;

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => [
                [
                    'id'       => $obat['id'] ?? 'OBT-001',
                    'price'    => $obat['harga'] ?? 50000,
                    'quantity' => 1,
                    'name'     => substr($obat['nama'] ?? 'Obat', 0, 50),
                ],
                [
                    'id'       => 'ONGKIR',
                    'price'    => (int)$ongkir,
                    'quantity' => 1,
                    'name'     => 'Ongkos Kirim - ' . $kurir,
                ],
            ],
            'customer_details' => [
                'first_name' => $nama,
                'phone'      => $hp,
                'shipping_address' => [
                    'first_name' => $nama,
                    'phone'      => $hp,
                    'address'    => $alamatLengkap,
                ],
            ],
        ];

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('POST', 'https://app.sandbox.midtrans.com/snap/v1/transactions', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
                    'Content-Type'  => 'application/json',
                ],
                'json'        => $payload,
                'timeout'     => 15,
                'http_errors' => false,
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['redirect_url'])) {
                session()->set('order_id', $orderId);
                session()->set('kurir', $kurir);
                session()->set('nama_penerima', $nama);
                session()->set('alamat_lengkap', $alamatLengkap);
                return redirect()->to($body['redirect_url']);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat transaksi: ' . ($body['error_messages'][0] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Koneksi ke Midtrans gagal: ' . $e->getMessage());
        }
    }
}