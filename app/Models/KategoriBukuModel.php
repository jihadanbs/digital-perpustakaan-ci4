<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriBukuModel extends Model
{
    protected $table = 'tb_kategori_buku';
    protected $primaryKey = 'id_kategori_buku';
    protected $returnType = 'object';
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

    public function getAllDataByAdmin($id_user)
    {
        return $this->where('id_user', $id_user)
            ->orderBy('id_kategori_buku', 'DESC')
            ->findAll() ?: [];
    }

    public function getAllDataByUserAdmin($id_user)
    {
        $query = $this->db->table('tb_kategori_buku')
            ->where('id_user', $id_user)
            ->get();

        // Debugging output
        // var_dump($query->getResultArray()); die;

        return $query->getResultArray();
    }


    // Method untuk menghitung jumlah data berdasarkan id tertentu
    public function countById($id_kategori_buku)
    {
        return $this->where('id_kategori_buku', $id_kategori_buku)->countAllResults();
    }
}
