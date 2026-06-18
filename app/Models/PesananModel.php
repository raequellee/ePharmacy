<?php
namespace App\Models;

use CodeIgniter\Model;

class PesananModel extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id', 'nama_obat', 'harga_obat',
        'nama_penerima', 'no_hp', 'alamat_lengkap',
        'kurir', 'ongkir', 'total', 'status'
    ];
}