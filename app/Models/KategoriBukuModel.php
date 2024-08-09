<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriBukuModel extends Model
{
    protected $table = 'tb_kategori_buku';
    protected $primaryKey = 'id_kategori_buku';
    protected $retunType = 'object';
    protected $allowedFields = ['id_user', 'nama_kategori'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = false;

    public function getAllData()
    {
        return $this->orderBy('id_kategori_buku', 'DESC')->findAll();
    }

    public function getAllDataByUser($id_user)
    {
        // Pastikan mengembalikan array, atau jika tidak ada data, kembalikan array kosong
        return $this->where('id_user', $id_user)->orderBy('id_kategori_buku', 'DESC')->findAll() ?: [];
    }

    // Method untuk menghitung jumlah data berdasarkan id tertentu
    public function countById($id_kategori_buku)
    {
        return $this->where('id_kategori_buku', $id_kategori_buku)->countAllResults();
    }
}
