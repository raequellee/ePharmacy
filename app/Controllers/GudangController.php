<?php
namespace App\Controllers;

class GudangController extends BaseController
{
    public function index()
    {
        $keyword = $this->request->getGet('q');

        $data = [
            'keyword'   => $keyword,
            'obat_list' => [],
            'error'     => null
        ];

        $client = \Config\Services::curlrequest();

        if (!empty($keyword)) {
            $url_api = 'https://api.fda.gov/drug/label.json?search=openfda.brand_name:"' . urlencode($keyword) . '"&limit=12';
        } else {
            $url_api = 'https://api.fda.gov/drug/label.json?search=_exists_:openfda.brand_name&limit=12';
        }

        try {
            $response = $client->request('GET', $url_api, [
                'timeout'     => 15,
                'http_errors' => false,
            ]);

            if ($response->getStatusCode() == 200) {
                $hasil = json_decode($response->getBody(), true);
                $data['obat_list'] = $hasil['results'] ?? [];
            } else {
                $data['error'] = 'Gagal mengambil data obat. (Error: ' . $response->getStatusCode() . ')';
            }
        } catch (\Exception $e) {
            $data['error'] = 'Gagal terhubung ke API: ' . $e->getMessage();
        }

        return view('obat/index', $data);
    }

    public function pesan()
    {
        // Simpan obat yang dipilih ke session
        $obat = [
            'id'           => $this->request->getPost('obat_id'),
            'nama'         => $this->request->getPost('nama_obat'),
            'manufacturer' => $this->request->getPost('manufacturer'),
            'route'        => $this->request->getPost('route'),
            'harga'        => 50000, // dummy harga karena FDA tidak ada harga
        ];

        session()->set('obat_dipilih', $obat);
        return redirect()->to(base_url('pesanan/konfirmasi'));
    }
}