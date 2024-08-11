<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriBukuModel extends Model
{
    protected $table = 'tb_kategori_buku';
    protected $primaryKey = 'id_kategori_buku';
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
    public function getAll($id_user)
    {
        return $this->where('id_user', $id_user)->orderBy('id_kategori_buku', 'DESC')->findAll();
    }

    public function getAllDataByUser2($id_user)
    {
        // Create a query using the query builder
        $queryBuilder = $this->db->table('tb_kategori_buku')
            ->where('id_user', $id_user)
            ->orderBy('id_kategori_buku', 'DESC');

        // Debugging: Display the SQL query
        echo $queryBuilder->getCompiledSelect();

        // Execute the query and return the results
        return $queryBuilder->get()->getResult() ?: [];
    }

    public function getAllDataByAdmin($id_user)
    {
        return $this->where('id_user', $id_user)
            ->orderBy('id_kategori_buku', 'DESC')
            ->findAll() ?: [];
    }

    public function getAllDataByUserAdmin($id_user)
    {
        $builder = $this->db->table('tb_kategori_buku');
        $builder->select('*');
        $builder->where('id_user', $id_user); // Asumsikan setiap kategori terkait dengan id_user
        $query = $builder->get();

        return $query->getResultArray(); // Mengembalikan array data
    }

    // Method untuk menghitung jumlah data berdasarkan id tertentu
    public function countById($id_kategori_buku)
    {
        return $this->where('id_kategori_buku', $id_kategori_buku)->countAllResults();
    }
}
