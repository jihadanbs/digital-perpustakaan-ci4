<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class KategoriBukuController extends BaseController
{
    public function index()
    {
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        $id_user = session()->get('id_user');

        if (session()->get('id_user') != $id_user) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $tb_kategori_buku = $this->m_kategori_buku->getAllDataByUser($id_user);

        // Debugging untuk memastikan tipe data
        if (!is_array($tb_kategori_buku) && !is_object($tb_kategori_buku)) {
            throw new \Exception('Data tb_kategori_buku tidak dapat diiterasi.');
        }

        $tb_user = $this->m_user->getAll();

        $data = [
            'title' => 'User | Halaman Kategori Buku',
            'tb_kategori_buku' => $tb_kategori_buku,
            'tb_user' => $tb_user,
        ];

        return view('user/kategori/index', $data);
    }

    public function save()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Cek apakah request datang dari AJAX
        if ($this->request->isAJAX()) {
            // Ambil data dari request AJAX
            $nama_kategori = $this->request->getPost('nama_kategori');
            $id_user = session()->get('id_user'); // Ambil id_user dari session

            // Validasi data
            if (empty($nama_kategori)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Nama Kategori harus diisi!']);
            }

            // Cek apakah nama_kategori sudah ada dalam database
            $existing_data = $this->m_kategori_buku->where('nama_kategori', $nama_kategori)->where('id_user', $id_user)->first();

            if ($existing_data) {
                // Jika nama_kategori sudah ada dalam database, kirim pesan error
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Nama Kategori sudah ada dalam database!']);
            }

            // Simpan data ke dalam database dengan id_user
            $this->m_kategori_buku->save([
                'nama_kategori' => $nama_kategori,
                'id_user' => $id_user,
            ]);

            // Berikan respons jika berhasil
            return $this->response->setJSON(['success' => 'Data berhasil disimpan.']);
        }

        // Jika berhasil disimpan, kembalikan ke halaman yang diinginkan dengan pesan sukses
        return redirect()->to('user/kategori/index')->with('success', 'Data berhasil disimpan.');
    }

    public function simpan_perubahan()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_user = session()->get('id_user');
        $dataToSave = $this->request->getPost('dataToSave');

        // Looping untuk validasi dan penyimpanan data
        foreach ($dataToSave as $data) {
            $id_kategori_buku = $data['id_kategori_buku'];
            $nama_kategori = $data['nama_kategori'];

            // Ambil data kategori buku yang akan diubah
            $kategori_buku = $this->m_kategori_buku->find($id_kategori_buku);

            // Cek apakah kategori buku tersebut milik user yang sedang login
            if (!$kategori_buku || $kategori_buku['id_user'] != $id_user) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah data ini atau data tidak ditemukan.']);
            }

            // Cek apakah nama kategori sudah ada dalam database kecuali dirinya sendiri
            $existingData = $this->m_kategori_buku
                ->where('nama_kategori', $nama_kategori)
                ->where('id_kategori_buku !=', $id_kategori_buku) // memeriksa nama_kategori di database kecuali kategori dengan id_kategori_buku yang sedang diperbarui
                ->first();

            if ($existingData) {
                // Jika nama_kategori sudah ada di database, kirimkan pesan kesalahan
                return $this->response->setJSON(['success' => false, 'message' => 'Nama kategori sudah ada dalam database!']);
            }

            // Simpan perubahan ke database
            $this->m_kategori_buku->update($id_kategori_buku, [
                'nama_kategori' => $nama_kategori
            ]);
        }

        // Kirimkan respons ke client jika berhasil
        return $this->response->setJSON(['success' => true]);
    }

    public function delete()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_user = session()->get('id_user'); // Ambil id_user dari session
        $id_kategori_buku = $this->request->getPost('id_kategori_buku');

        // Cek apakah kategori buku tersebut milik user yang sedang login
        $kategori_buku = $this->m_kategori_buku->find($id_kategori_buku);

        if (!$kategori_buku || $kategori_buku['id_user'] != $id_user) {
            return $this->response->setJSON(['error' => 'Anda tidak memiliki akses untuk menghapus data ini atau data tidak ditemukan.']);
        }

        if ($this->m_kategori_buku->delete($id_kategori_buku)) {
            return $this->response->setJSON(['success' => 'Data berhasil dihapus.']);
        } else {
            return $this->response->setJSON(['error' => 'Gagal menghapus data.']);
        }
    }
}
