<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UsersController extends BaseController
{
    public function index()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda Tidak Memiliki Akses Ke Halaman Ini');
        }

        $id_user = session()->get('id_user');

        // Pastikan hanya pengguna dengan id_user yang sesuai yang dapat mengakses halaman
        if (session()->get('id_user') != $id_user) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = [
            'title' => 'Admin | Halaman Users',
        ];

        return view('admin/users/index', $data);
    }

    // SERVER SIDE PROCESSING
    public function get_data()
    {
        if (!$this->session->has('islogin')) {
            echo json_encode([
                "draw" => $_POST['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
            return;
        }

        $draw = intval($this->request->getPost('draw'));
        $start = intval($this->request->getPost('start'));
        $length = intval($this->request->getPost('length'));
        $searchValue = $this->request->getPost('search')['value'];

        $totalRecords = $this->m_user->countAllUsers();
        $totalFiltered = $this->m_user->countFilteredUsers($searchValue);
        $users = $this->m_user->getFilteredUsers($start, $length, $searchValue);

        $data = [];
        foreach ($users as $row) {
            // Jika file_profil tidak ada atau null, gunakan gambar placeholder
            $profileImg = $row['file_profil'] ? esc(site_url($row['file_profil']), 'attr') : esc(site_url('path/to/placeholder/image.png'), 'attr');

            $data[] = [
                esc($start + 1, 'html'),
                '<img src="' . $profileImg . '" alt="Profil User" style="width: 60px; height: 60px;">',
                esc($row['nama_lengkap'], 'html'),
                esc($row['username'], 'html'),
                esc($row['email'], 'html'),
                '<a href="' . esc(site_url('admin/users/data/' . urlencode($row['id_user'])), 'attr') . '" class="btn btn-info btn-sm view"><i class="fa fa-eye"></i> Cek</a>'
            ];
            $start++;
        }

        $response = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        ];

        return $this->response->setJSON($response);
    }

    // AMBIL DATA BERDASARKAN ID USER
    public function data($id_user = null)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        // Pastikan hanya admin yang dapat mengakses halaman
        if ($this->session->get('id_jabatan') !== '1') {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil nama lengkap pengguna
        $namaLengkap = $this->m_user->getLengkapById($id_user);

        // Jika nama lengkap tidak ditemukan, tampilkan pesan error atau lakukan penanganan yang sesuai
        if ($namaLengkap === 'Nama Tidak Ditemukan') {
            return redirect()->back()->with('gagal', 'Nama pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Admin | Halaman Data Buku User',
            'id_user' => $id_user, // Kirim id_user ke view
            'nama_lengkap' => $namaLengkap

        ];

        return view('admin/users/data', $data);
    }

    public function ambilData()
    {
        if (!$this->session->has('islogin')) {
            echo json_encode([
                "draw" => $_POST['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
            return;
        }

        $id_user = $this->request->getPost('id_user'); // Ambil id_user dari request
        $kategori_id = $this->request->getPost('kategori'); // Ambil filter kategori dari request

        // Jika id_user tidak diberikan, gunakan id_user dari session
        $id_user = $id_user ?? session()->get('id_user');

        $list = $this->m_buku->getAllDataByUserAdmin($id_user, $kategori_id); // Pass id_user dan kategori_id ke model
        $start = $_POST['start'] + 1;

        $data = [];
        foreach ($list as $row) {
            $data[] = [
                esc($start++, 'html'),
                '<img src="' . esc(site_url($row['file_cover_buku'])) . '" alt="Cover Buku" style="width: 60px; height: 60px;">',
                esc($row['judul_buku'], 'html'),
                esc($row['nama_kategori'], 'html'),
                esc(truncateText($row['deskripsi'], 20), 'html'),
                '<a href="' . esc(site_url('admin/users/cek/' . urlencode($row['slug'])), 'attr') . '" class="btn btn-info btn-sm view"><i class="fa fa-eye"></i> Cek</a> <button type="button" class="btn btn-danger btn-sm waves-effect waves-light sa-warning" data-id="' . esc($row['id_buku'], 'attr') . '"><i class="fas fa-trash-alt"></i> Delete</button>'
            ];
        }

        echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "data" => $data
        ]);
    }

    public function getKategori()
    {
        // Ambil id_user dari request GET atau session
        $id_user = $this->request->getGet('id_user') ?? session()->get('id_user');

        // Pastikan id_user telah terisi
        if (!$id_user) {
            echo json_encode([]);
            return;
        }

        // Panggil model untuk mengambil data kategori berdasarkan id_user
        $kategoriList = $this->m_kategori_buku->getAllDataByUser($id_user);

        // Format data ke dalam array objek
        $formattedData = [];
        foreach ($kategoriList as $kategori) {
            $formattedData[] = [
                'id_kategori_buku' => $kategori['id_kategori_buku'],
                'nama_kategori' => $kategori['nama_kategori']
            ];
        }

        // Kembalikan data dalam format JSON
        echo json_encode($formattedData);
    }
    // END SERVER SIDE PROCESSING

    // Controller
    public function tambah($id_user = null)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        // Ambil id_user dari URL atau session
        $id_user = $id_user ?? $this->request->getGet('id_user') ?? session()->get('id_user');


        if ($this->session->get('id_jabatan') !== '1') {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil nama lengkap pengguna
        $namaLengkap = $this->m_user->getLengkapById($id_user);

        // Jika nama lengkap tidak ditemukan, tampilkan pesan error atau lakukan penanganan yang sesuai
        if ($namaLengkap === 'Nama Tidak Ditemukan') {
            return redirect()->back()->with('gagal', 'Nama pengguna tidak ditemukan.');
        }

        // Ambil data pengguna, buku, dan kategori buku sesuai id_user
        $tb_user = $this->m_user->getAll();
        $tb_buku = $this->m_buku->getAllDataByUser($id_user);
        $tb_kategori_buku = $this->m_kategori_buku->getAllDataByUser($id_user);

        $data = [
            'title' => 'Admin | Halaman Tambah Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'tb_user' => $tb_user,
            'tb_buku' => $tb_buku,
            'tb_kategori_buku' => $tb_kategori_buku,
            'id_user' => $id_user, // Tambahkan id_user ke array data
            'nama_lengkap' => $namaLengkap
        ];

        return view('admin/users/tambah', $data);
    }

    public function save($id_user = null)
    {
        // Cek sesi
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        // Pastikan hanya admin yang bisa menambah buku
        if ($this->session->get('id_jabatan') !== '1') {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $id_user = $id_user ?? $this->request->getGet('id_user') ?? session()->get('id_user');

        // Ambil data dari request
        $judul_buku = $this->request->getVar('judul_buku');
        $deskripsi = $this->request->getVar('deskripsi');
        $jumlah = $this->request->getVar('jumlah');
        $id_kategori_buku = $this->request->getVar('id_kategori_buku');

        // Validasi input
        if (!$this->validate([
            'id_kategori_buku' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan Pilih Nama Kategori Buku !'
                ]
            ],
            'judul_buku' => [
                'rules' => "required|is_unique_judul[tb_buku,id_kategori_buku]|trim|min_length[5]|max_length[100]",
                'errors' => [
                    'required' => 'Kolom Judul Tidak Boleh Kosong !',
                    'is_unique_judul' => 'Judul Sudah Terdaftar Untuk Nama Kategori Informasi Yang Sama, Silahkan Ganti Nama Judul Lainnya !',
                    'min_length' => 'Judul Tidak Boleh Kurang Dari 5 Karakter !',
                    'max_length' => 'Judul Tidak Boleh Melebihi 100 Karakter !',
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|trim|min_length[5]',
                'errors' => [
                    'required' => 'Kolom Isi Deskripsi Tidak Boleh Kosong',
                    'min_length' => 'Isi Deskripsi tidak boleh kurang dari 5 karakter !',
                ]
            ],
            'jumlah' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Silahkan Masukkan Kolom Jumlah !',
                    'integer' => 'Kolom Jumlah harus berupa angka !',
                    'greater_than' => 'Inputan Jumlah harus lebih besar dari 0 !',
                ]
            ],
        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->back()->withInput();
        }

        // Upload cover buku dan file buku
        $coverBuku = uploadFile('file_cover_buku', 'dokumen/cover-buku/');
        $fileBuku = uploadFilePDF('file_buku', 'dokumen/file-buku/');

        // Generate slug dari judul buku
        $slug = url_title($judul_buku, '-', true);

        // Simpan data buku
        $this->m_buku->save([
            'id_kategori_buku' => $id_kategori_buku,
            'judul_buku' => $judul_buku,
            'deskripsi' => $deskripsi,
            'jumlah' => $jumlah,
            'file_cover_buku' => $coverBuku,
            'file_buku' => $fileBuku,
            'id_user' => $id_user,
            'slug' => $slug
        ]);

        // Set pesan sukses
        session()->setFlashdata('pesan', 'Data Berhasil Ditambahkan &#129395;');

        // Redirect ke halaman pengguna
        return redirect()->to('/admin/users/data/' . $id_user);
    }

    public function deleteData($id_user = null)
    {
        $id_user = $id_user ?? $this->request->getGet('id_user') ?? session()->get('id_user');
        $id_buku = $this->request->getPost('id_buku');

        $this->db->transStart();

        try {
            $dataFiles = $this->m_buku->getFilesByIdAndUser($id_buku, $id_user);

            if (empty($dataFiles)) {
                throw new \Exception('Tidak ada file yang ditemukan untuk buku.');
            }

            foreach ($dataFiles[0] as $fileColumn => $filePath) {
                if (!empty($filePath)) {
                    $fullFilePath = ROOTPATH . 'public/' . $filePath;
                    if (is_file($fullFilePath)) {
                        if (!unlink($fullFilePath)) {
                            throw new \Exception('Gagal menghapus file: ' . $fullFilePath);
                        }
                    }
                }
            }

            $this->m_buku->deleteByIdAndUser($id_buku, $id_user);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus file dan data']);
            }

            $this->db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Semua file dan data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus file dan data', 'error' => $e->getMessage()]);
        }
    }

    public function deleteData2($id_user = null)
    {
        $id_user = $id_user ?? $this->request->getGet('id_user') ?? session()->get('id_user');
        $id_buku = $this->request->getPost('id_buku');

        $this->db->transStart();

        try {
            $dataFiles = $this->m_buku->getFilesByIdAndUser($id_buku, $id_user);

            if (empty($dataFiles)) {
                throw new \Exception('Tidak ada file yang ditemukan untuk buku.');
            }

            foreach ($dataFiles[0] as $fileColumn => $filePath) {
                if (!empty($filePath)) {
                    $fullFilePath = ROOTPATH . 'public/' . $filePath;
                    if (is_file($fullFilePath)) {
                        if (!unlink($fullFilePath)) {
                            throw new \Exception('Gagal menghapus file: ' . $fullFilePath);
                        }
                    }
                }
            }

            $this->m_buku->deleteByIdAndUser($id_buku, $id_user);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus file dan data']);
            }

            $this->db->transCommit();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Semua file dan data berhasil dihapus']);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus file dan data', 'error' => $e->getMessage()]);
        }
    }

    public function cek($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        // Ambil id_user dari session
        $id_user_session = $this->request->getGet('id_user') ?? session()->get('id_user');

        // Ambil buku berdasarkan id_buku
        $buku = $this->m_buku->getBuku($id_buku);

        // Cek apakah buku ada
        if (!$buku) {
            return redirect()->back()->with('gagal', 'Data buku tidak ditemukan');
        }

        // Jika bukan admin dan id_user buku tidak sama dengan id_user session
        if (session()->get('id_jabatan') != 1 && $buku->id_user != $id_user_session) {
            return redirect()->back()->with('gagal', 'Anda tidak memiliki akses ke buku ini');
        }

        //WAJIB//
        $tb_user = $this->m_user->getAll();
        //END WAJIB//
        $tb_kategori_buku = $this->m_kategori_buku->getAllData();

        $data = [
            'title' => 'Admin | Halaman Cek Data',
            'tb_buku' => [$buku], // Pastikan dikirim sebagai array
            'dokumen' => $buku->additional_data,
            //WAJIB//
            'tb_user' => $tb_user,
            //END WAJIB//
            'tb_kategori_buku' => $tb_kategori_buku,
            'id_user' => $id_user_session,
        ];

        return view('admin/users/cek', $data);
    }

    public function edit($id_buku)
    {
        // Cek apakah sesi login aktif
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        // Ambil id_user dari request GET atau dari sesi
        $id_user = $this->request->getGet('id_user') ?? session()->get('id_user');

        // Debugging untuk memeriksa nilai id_user
        var_dump($id_user);

        // Ambil data buku menggunakan model
        $tb_buku = $this->m_buku->getBukuEdit($id_buku);

        // Debugging untuk memeriksa data buku
        // var_dump($tb_buku);

        // Pastikan data buku ditemukan
        if (!$tb_buku) {
            return redirect()->back()->with('gagal', 'Data buku tidak ditemukan');
        }

        // Cek apakah pengguna memiliki akses ke buku ini
        if (session()->get('id_jabatan') != 1 && $tb_buku->id_user != $id_user) {
            return redirect()->back()->with('gagal', 'Anda tidak memiliki akses ke buku ini');
        }

        // Ambil semua data user
        $tb_user = $this->m_user->getAll();

        // Ambil kategori buku untuk user
        $tb_kategori_buku = $this->m_kategori_buku->getAllDataByUser2($id_user);

        // Debugging untuk memeriksa data kategori
        // var_dump($tb_kategori_buku);

        // Persiapkan data untuk view
        $data = [
            'title' => 'Admin | Halaman Edit Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'tb_buku' => $tb_buku,
            'tb_kategori_buku' => $tb_kategori_buku,
            'tb_user' => $tb_user,
            'id_user' => $id_user,
        ];

        // Render view dengan data yang disiapkan
        return view('admin/users/edit', $data);
    }

    public function update($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 1) {
            return redirect()->to('authentication/login');
        }

        // Ambil data dari request
        $judul_buku = $this->request->getVar('judul_buku');
        $deskripsi = $this->request->getVar('deskripsi');
        $jumlah = $this->request->getVar('jumlah');
        $id_kategori_buku = $this->request->getVar('id_kategori_buku');

        //validasi input 
        if (!$this->validate([
            'id_kategori_buku' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan Pilih Nama Kategori Buku !'
                ]
            ],
            'judul_buku' => [
                'rules' => "required|trim|min_length[5]|max_length[100]",
                'errors' => [
                    'required' => 'Kolom Judul Tidak Boleh Kosong !',
                    'min_length' => 'Judul Tidak Boleh Kurang Dari 5 Karakter !',
                    'max_length' => 'Judul Tidak Boleh Melebihi 100 Karakter !',
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|trim|min_length[5]',
                'errors' => [
                    'required' => 'Kolom Isi Deskripsi Tidak Boleh Kosong',
                    'min_length' => 'Isi Deskripsi tidak boleh kurang dari 5 karakter !',
                ]
            ],
            'jumlah' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Silahkan Masukkan Kolom Jumlah !',
                    'integer' => 'Kolom Jumlah harus berupa angka !',
                    'greater_than' => 'Inputan Jumlah harus lebih besar dari 0 !',
                ]
            ],
            // 'file_cover_buku' => [
            //     'rules' => 'uploaded[file_cover_buku]|mime_in[file_cover_buku,image/jpeg,image/jpg,image/png]|ext_in[file_cover_buku,jpeg,jpg,png]',
            //     'errors' => [
            //         'uploaded' => 'Silahkan unggah file cover buku!',
            //         'mime_in' => 'Format file cover buku harus berupa jpeg, jpg, atau png!',
            //         'ext_in' => 'Ekstensi file cover buku harus jpeg, jpg, atau png!',
            //     ]
            // ],
            // 'file_buku' => [
            //     'rules' => 'uploaded[file_buku]|mime_in[file_buku,application/pdf]|ext_in[file_buku,pdf]',
            //     'errors' => [
            //         'uploaded' => 'Silahkan unggah file buku!',
            //         'mime_in' => 'Format file buku harus berupa PDF!',
            //         'ext_in' => 'Ekstensi file buku harus PDF!',
            //     ]
            // ],
        ])) {
            session()->setFlashdata('validation', \Config\Services::validation());
            return redirect()->back()->withInput();
        }

        // Handle file upload
        $coverBukuLama = $this->request->getVar('current_file_cover_buku'); // Nama file lama dari input hidden
        $coverBukuBaru = updateFile('file_cover_buku', 'dokumen/cover-buku/', $coverBukuLama);

        $fileBukuLama = $this->request->getVar('current_file_buku'); // Nama file lama dari input hidden
        $fileBukuBaru = updateFilePDF('file_buku', 'dokumen/file-buku/', $fileBukuLama);

        // Ambil data user dari buku yang sedang diubah
        $buku = $this->m_buku->getBukuEdit($id_buku);
        $id_user = $buku->id_user;

        $slug = url_title($this->request->getVar('judul_buku'), '-', true);

        $this->m_buku->save([
            'id_buku' => $id_buku,
            'id_kategori_buku' => $id_kategori_buku,
            'judul_buku' => $judul_buku,
            'deskripsi' => $deskripsi,
            'jumlah' => $jumlah,
            'file_cover_buku' => $coverBukuBaru,
            'file_buku' => $fileBukuBaru,
            'id_user' => $id_user,
            'slug' => $slug
        ]);

        // Set flash message untuk sukses
        session()->setFlashdata('pesan', 'Data Berhasil Diubah &#129395;');

        return redirect()->to('/admin/users/data/' . $id_user);
    }

    public function exportExcel($id_user = null)
    {
        try {
            $bukuData = $this->m_buku->getExcelUser($id_user);

            if (empty($bukuData)) {
                throw new \Exception('Tidak ada data yang tersedia untuk user yang ditentukan.');
            }

            $sheet = $this->spreadsheet->getActiveSheet();

            // Atur header
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Cover Buku');
            $sheet->setCellValue('C1', 'Judul Buku');
            $sheet->setCellValue('D1', 'Kategori');
            $sheet->setCellValue('E1', 'Deskripsi');

            $idNumber = 1;
            $row = 2;
            foreach ($bukuData as $buku) {
                // Pastikan $buku adalah objek
                $coverPath = FCPATH . $buku->file_cover_buku;

                // Menulis nomor urut ke kolom A
                $sheet->setCellValue('A' . $row, $idNumber);

                $this->createDrawingAdmin(
                    'Cover',
                    'Cover Buku',
                    $coverPath,
                    60,
                    'B' . $row,
                    $sheet
                );

                $sheet->setCellValue('C' . $row, $buku->judul_buku);
                $sheet->setCellValue('D' . $row, $buku->nama_kategori);
                $sheet->setCellValue('E' . $row, $buku->deskripsi);

                $idNumber++;
                $row++;
            }

            $fileName = 'daftar_buku_users.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $this->writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
