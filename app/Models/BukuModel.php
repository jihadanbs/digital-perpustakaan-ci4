<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'tb_buku';
    protected $primaryKey = 'id_buku';
    protected $returnType = 'object';
    protected $allowedFields = ['id_user', 'id_kategori_buku', 'judul_buku', 'deskripsi', 'jumlah', 'file_cover_buku', 'file_buku', 'slug'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = false;

    public function getExcelUser($id_user = false)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');

        if ($id_user !== false) {
            $builder->where('tb_buku.id_user', $id_user); // Periksa apakah kolom ini benar
        }

        $builder->orderBy('tb_buku.id_buku', 'DESC');
        $query = $builder->get();
        $results = $query->getResult();

        return $results;
    }


    public function getAllDataUserKategori($id_user, $kategori_id = null)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, GROUP_CONCAT(tb_kategori_buku.nama_kategori SEPARATOR ", ") as nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_buku.id_kategori_buku = tb_kategori_buku.id_kategori_buku');
        $builder->where('tb_buku.id_user', $id_user);

        if ($kategori_id) {
            $builder->where('tb_buku.id_kategori_buku', $kategori_id);
        }

        $builder->groupBy('tb_buku.id_buku');
        $builder->orderBy('tb_buku.id_buku', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getAllDataByUserAdmin($id_user, $kategori_id = null)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, GROUP_CONCAT(tb_kategori_buku.nama_kategori SEPARATOR ", ") as nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_buku.id_kategori_buku = tb_kategori_buku.id_kategori_buku');

        // Filter berdasarkan id_user jika admin memilih pengguna tertentu
        if ($id_user) {
            $builder->where('tb_buku.id_user', $id_user);
        }

        if ($kategori_id) {
            $builder->where('tb_buku.id_kategori_buku', $kategori_id);
        }

        $builder->groupBy('tb_buku.id_buku');
        $builder->orderBy('tb_buku.id_buku', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getKategoriByUserAdmin($id_user)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_kategori_buku.id_kategori_buku, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_buku.id_kategori_buku = tb_kategori_buku.id_kategori_buku');
        $builder->where('tb_buku.id_user', $id_user);
        $builder->groupBy('tb_kategori_buku.id_kategori_buku'); // Group by id_kategori_buku to avoid duplicates
        $builder->orderBy('tb_kategori_buku.nama_kategori', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getAllDataByUser($id_user)
    {
        return $this->db->table('tb_buku')
            ->select('tb_buku.*, GROUP_CONCAT(tb_kategori_buku.nama_kategori SEPARATOR ", ") as nama_kategori')
            ->join('tb_kategori_buku', 'tb_buku.id_kategori_buku = tb_kategori_buku.id_kategori_buku')
            ->where('tb_buku.id_user', $id_user)
            ->groupBy('tb_buku.id_buku')
            ->orderBy('tb_buku.id_buku', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAllDataByUserEdit($id_user)
    {
        $builder = $this->db->table('tb_kategori_buku');
        $builder->select('*');
        $builder->where('id_user', $id_user);
        $query = $builder->get();
        return $query->getResultArray(); // Pastikan menggunakan getResultArray atau getResult
    }

    public function getFilesById($id_buku)
    {
        // Ambil hanya kolom yang dibutuhkan
        return $this->select('file_cover_buku, file_buku')->where('id_buku', $id_buku)->findAll();
    }

    public function deleteById($id_buku)
    {
        // Menghapus entri di tabel berdasarkan id_buku
        return $this->where('id_buku', $id_buku)->delete();
    }


    public function getFilesByIdAndUser($id_buku, $id_user)
    {
        // Ambil hanya kolom yang dibutuhkan dan sesuaikan dengan id_buku dan id_user
        return $this->select('file_cover_buku, file_buku')
            ->where('id_buku', $id_buku)
            ->where('id_user', $id_user)
            ->findAll();
    }

    public function deleteByIdAndUser($id_buku, $id_user)
    {
        // Menghapus entri di tabel berdasarkan id_buku dan id_user
        return $this->where('id_buku', $id_buku)
            ->where('id_user', $id_user)
            ->delete();
    }

    public function getBukuByUser($id_buku = false)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');

        if ($id_buku !== false) {
            $builder->where('tb_buku.id_buku', $id_buku);
        }

        $builder->orderBy('tb_buku.id_buku', 'DESC');
        $query = $builder->get();
        $results = $query->getResult();

        // Ambil data tambahan berdasarkan id_buku
        foreach ($results as $result) {
            $id_buku = $result->id_buku;
            $additional_data = $this->getDokumenById($id_buku);
            $result->additional_data = $additional_data;
        }

        // Jika $id_buku diberikan, kembalikan satu baris hasil
        if ($id_buku !== false) {
            return $results ? $results[0] : null;
        }

        return $results;
    }

    public function getDokumenById($id_buku)
    {
        $builder = $this->db->table('tb_buku');
        $result = $builder->where('id_buku', $id_buku)->get()->getResult();
        return $result;
    }

    public function getBuku($slug = false)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');

        if ($slug !== false) {
            $builder->where('tb_buku.slug', $slug);
        }

        $builder->orderBy('tb_buku.id_buku', 'DESC');
        $query = $builder->get();
        $results = $query->getResult();

        // Ambil data tambahan berdasarkan id_buku
        foreach ($results as $result) {
            $id_buku = $result->id_buku;
            $additional_data = $this->getDokumenById($id_buku);
            $result->additional_data = $additional_data;
        }

        // Jika $id_buku diberikan, kembalikan satu baris hasil
        if ($id_buku !== false) {
            return $results ? $results[0] : null;
        }

        return $results;
    }

    public function getBukuEdit($id_buku = false)
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');

        if ($id_buku !== false) {
            $builder->where('tb_buku.id_buku', $id_buku);
        }

        $builder->orderBy('tb_buku.id_buku', 'DESC');
        $query = $builder->get();
        $results = $query->getResult();

        foreach ($results as $result) {
            $additional_data = $this->getDokumenById($result->id_buku);
            $result->additional_data = $additional_data;
        }

        if ($id_buku !== false) {
            return $results ? $results[0] : null;
        }

        return $results;
    }

    public function getTotalBuku($id_user)
    {
        $query = $this->db->query('SELECT COUNT(*) as total FROM ' . $this->table . ' WHERE id_user = ?', [$id_user]);
        $result = $query->getRow();
        return $result ? $result->total : 0;
    }

    public function getDataForDataTables($id_user)
    {
        return $this->db->table('tb_buku')
            ->select('tb_buku.id_buku, tb_buku.file_cover_buku, tb_buku.judul_buku, tb_buku.deskripsi, GROUP_CONCAT(tb_kategori_buku.nama_kategori SEPARATOR ", ") as nama_kategori')
            ->join('tb_kategori_buku', 'tb_buku.id_kategori_buku = tb_kategori_buku.id_kategori_buku')
            ->where('tb_buku.id_user', $id_user)
            ->groupBy('tb_buku.id_buku')
            ->orderBy('tb_buku.id_buku', 'DESC')
            ->get();
    }

    public function getAllDataByUserAndCategory($id_user, $kategori_id = null)
    {
        $builder = $this->db->table('tb_buku');
        $builder->where('id_user', $id_user);

        if ($kategori_id) {
            $builder->where('id_kategori', $kategori_id);
        }

        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getAllData($id_buku = null)
    {
        $builder = $this->db->table('tb_buku');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');

        if ($id_buku !== null) {
            $builder->where('id_buku', $id_buku);
        }

        return $builder->get()->getRow();
    }

    public function getAllSorted()
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.*, tb_kategori_buku.nama_kategori');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');
        $builder->orderBy('tb_buku.id_buku', 'DESC');
        $query = $builder->get();
        // return $query->getResult();
        $results = $query->getResult();

        // Ambil data tambahan berdasarkan id_buku
        foreach ($results as $result) {
            $id_buku = $result->id_buku;
            $additional_data = $this->getDokumenById($id_buku);
            $result->additional_data = $additional_data;
        }

        return $results;
    }

    public function getAll($id_buku = null)
    {
        $builder = $this->db->table('id_buku');

        if ($id_buku !== null) {
            $builder->where('id_buku', $id_buku);
        }

        return $builder->get()->getRow();
    }

    public function getData()
    {
        return $this->orderBy('id_buku', 'DESC')->findAll();
    }

    public function getCategoriesWithCount()
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_kategori_buku.nama_kategori, COUNT(tb_buku.id_buku) as count');
        $builder->join('tb_kategori_buku', 'tb_kategori_buku.id_kategori_buku = tb_buku.id_kategori_buku');
        $builder->groupBy('tb_kategori_buku.nama_kategori');
        $builder->orderBy('count', 'DESC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getRecentPosts()
    {
        $builder = $this->db->table('tb_buku');
        $builder->select('tb_buku.judul, tb_buku.tanggal_diterbitkan, tb_buku.gambar');
        $builder->orderBy('tb_buku.tanggal_diterbitkan', 'DESC'); // Order by date descending
        $builder->limit(5); // Limit to 5 most recent posts
        $query = $builder->get();
        return $query->getResult();
    }
}
