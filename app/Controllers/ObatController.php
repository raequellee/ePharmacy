<?php

namespace App\Controllers;

class ObatController extends BaseController
{
    private function fetchFromFda($keyword = '')
    {
        $term = !empty($keyword) ? $keyword : 'amoxicillin';

        $url = 'https://api.fda.gov/drug/label.json?search=openfda.brand_name:"'
            . urlencode($term) . '"&limit=9';

        $response = call_api($url, 'GET', null, 6);

        if ($response === null || !isset($response['results'])) {
            return null;
        }

        return $response['results'];
    }

    public function index()
    {
        $results = $this->fetchFromFda();

        return view('obat/index', [
            'obat_list' => $results ?? [],
            'keyword'   => '',
            'error'     => $results === null
                ? 'Gagal mengambil data obat dari server. Coba lagi sebentar.'
                : null,
        ]);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $results = $this->fetchFromFda($keyword);

        return view('obat/index', [
            'obat_list' => $results ?? [],
            'keyword'   => $keyword,
            'error'     => $results === null
                ? 'Gagal mengambil data obat dari server. Coba lagi sebentar.'
                : null,
        ]);
    }
}