<?php
namespace App\Controllers;

class PengirimanController extends BaseController
{
    public function index()
    {
        $data = [
            'order_id'      => session()->get('order_id'),
            'kurir'         => session()->get('kurir'),
            'nama_penerima' => session()->get('nama_penerima'),
            'alamat'        => session()->get('alamat_lengkap'),
        ];

        return view('pengiriman/lacak', $data);
    }

    public function lacak($noResi = null)
    {
        $data = [
            'order_id'      => session()->get('order_id'),
            'kurir'         => session()->get('kurir'),
            'nama_penerima' => session()->get('nama_penerima'),
            'alamat'        => session()->get('alamat_lengkap'),
            'no_resi'       => $noResi,
        ];

        return view('pengiriman/lacak', $data);
    }
}