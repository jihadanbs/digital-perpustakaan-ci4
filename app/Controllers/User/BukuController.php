<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class BukuController extends BaseController
{
    public function index()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        $id_user = session()->get('id_user');

        // Pastikan hanya pengguna dengan id_user yang sesuai yang dapat mengakses halaman
        if (session()->get('id_user') != $id_user) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = [
            'title' => 'User | Halaman Buku'
        ];

        return view('user/buku/index', $data);
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

        $id_user = session()->get('id_user');
        $kategori_id = $this->request->getPost('kategori'); // Ambil filter kategori dari request
        $list = $this->m_buku->getAllDataUserKategori($id_user, $kategori_id); // Pass filter kategori ke model
        $start = $_POST['start'] + 1;

        $data = [];
        foreach ($list as $row) {
            $data[] = [
                esc($start++, 'html'),
                '<img src="' . esc(site_url($row['file_cover_buku'])) . '" alt="Cover Buku" style="width: 60px; height: 60px;">',
                esc($row['judul_buku'], 'html'),
                esc($row['nama_kategori'], 'html'),
                esc(truncateText($row['deskripsi'], 20), 'html'),
                '<a href="' . esc(site_url('user/buku/cek_data/' . urlencode($row['slug'])), 'attr') . '" class="btn btn-info btn-sm view"><i class="fa fa-eye"></i> Cek</a> <button type="button" class="btn btn-danger btn-sm waves-effect waves-light sa-warning" data-id="' . esc($row['id_buku'], 'attr') . '"><i class="fas fa-trash-alt"></i> Delete</button>'
            ];
        }

        echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "data" => $data
        ]);
    }

    public function get_kategori()
    {
        $id_user = session()->get('id_user');
        $kategoriList = $this->m_kategori_buku->getAllDataByUser($id_user);

        // Format data dengan array objek
        $formattedData = [];
        foreach ($kategoriList as $kategori) {
            $formattedData[] = [
                'id_kategori_buku' => $kategori['id_kategori_buku'],
                'nama_kategori' => $kategori['nama_kategori']
            ];
        }

        echo json_encode($formattedData);
    }
    // END SERVER SIDE PROCESSING

    public function tambah($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login');
        }

        // Ambil id_user dari session
        $id_user = session()->get('id_user');

        // Pastikan data buku milik user yang login
        $tb_buku = $this->m_buku->where('id_user', $id_user)->find($id_buku);
        if (!$tb_buku) {
            return redirect()->back()->with('gagal', 'Data buku tidak ditemukan atau Anda tidak memiliki akses');
        }

        //WAJIB//
        $tb_user = $this->m_user->getAll();
        //END WAJIB//

        $tb_kategori_buku = $this->m_kategori_buku->getAllDataByUser($id_user);

        $data = [
            'title' => 'User | Halaman Edit Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'tb_buku' => $tb_buku,
            'tb_kategori_buku' => $tb_kategori_buku,
            //WAJIB//
            'tb_user' => $tb_user,
            //END WAJIB//
        ];

        return view('user/buku/edit', $data);
    }

    public function save()
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
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

        // Upload cover buku dan file buku
        $coverBuku = uploadFile('file_cover_buku', 'dokumen/cover-buku/');
        $fileBuku = uploadFilePDF('file_buku', 'dokumen/file-buku/');

        // Ambil id_user dari session
        $id_user = session()->get('id_user');
        $slug = url_title($this->request->getVar('judul_buku'), '-', true);

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

        session()->setFlashdata('pesan', 'Data Berhasil Di Tambahkan &#129395;');

        return redirect()->to('/user/buku');
    }

    public function delete()
    {
        $id_buku = $this->request->getPost('id_buku');

        $this->db->transStart();

        try {
            $dataFiles = $this->m_buku->getFilesById($id_buku);

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

            $this->m_buku->deleteById($id_buku);

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

    public function delete2()
    {
        $id_buku = $this->request->getPost('id_buku');

        $this->db->transStart();

        try {
            $dataFiles = $this->m_buku->getFilesById($id_buku);

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

            $this->m_buku->deleteById($id_buku);

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

    public function cek_data($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login');
        }

        // Ambil id_user dari session
        $id_user_session = session()->get('id_user');

        // Ambil buku berdasarkan id_buku
        $buku = $this->m_buku->getBuku($id_buku);

        // Cek apakah buku ada dan id_user yang login sama dengan id_user dari buku
        if (!$buku || $buku->id_user != $id_user_session) {
            return redirect()->back()->with('gagal', 'Data buku tidak ditemukan atau Anda tidak memiliki akses');
        }

        //WAJIB//
        $tb_user = $this->m_user->getAll();
        //END WAJIB//
        $tb_kategori_buku = $this->m_kategori_buku->getAllData();

        $data = [
            'title' => 'User | Halaman Cek Data',
            'tb_buku' => [$buku], // Pastikan dikirim sebagai array
            'dokumen' => $buku->additional_data,
            //WAJIB//
            'tb_user' => $tb_user,
            //END WAJIB//
            'tb_kategori_buku' => $tb_kategori_buku
        ];

        return view('user/buku/cek_data', $data);
    }

    public function edit($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login');
        }

        // Ambil id_user dari session
        $id_user = session()->get('id_user');

        // Pastikan data buku milik user yang login
        $tb_buku = $this->m_buku->where('id_user', $id_user)->find($id_buku);
        if (!$tb_buku) {
            return redirect()->back()->with('gagal', 'Data buku tidak ditemukan atau Anda tidak memiliki akses');
        }

        //WAJIB//
        $tb_user = $this->m_user->getAll();
        //END WAJIB//

        $tb_kategori_buku = $this->m_kategori_buku->getAllDataByUser($id_user);

        $data = [
            'title' => 'User | Halaman Edit Buku',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            'tb_buku' => $tb_buku,
            'tb_kategori_buku' => $tb_kategori_buku,
            //WAJIB//
            'tb_user' => $tb_user,
            //END WAJIB//
        ];

        return view('user/buku/edit', $data);
    }
    public function update($id_buku)
    {
        // Cek session
        if (!$this->session->has('islogin')) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        }

        if (session()->get('id_jabatan') != 2) {
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

        // Ambil id_user dari session
        $id_user = session()->get('id_user');
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

        return redirect()->to('/user/buku');
    }

    public function totalData($id_user)
    {
        $totalData = $this->m_buku->getTotalBuku($id_user);
        // Keluarkan total data sebagai JSON response
        return $this->response->setJSON(['total' => $totalData]);
    }
    public function exportExcel()
    {
        // Ambil id_user dari session jika diperlukan
        $id_user = session()->get('id_user');

        // Ambil data buku dari database
        $bukuData = $this->m_buku->getAllDataByUser($id_user);

        // Inisialisasi spreadsheet
        $sheet = $this->spreadsheet->getActiveSheet();

        // Atur header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Cover Buku');
        $sheet->setCellValue('C1', 'Judul Buku');
        $sheet->setCellValue('D1', 'Kategori');
        $sheet->setCellValue('E1', 'Deskripsi');

        // Mulai nomor ID dari 1
        $idNumber = 1;
        $row = 2;
        foreach ($bukuData as $buku) {
            // Tetapkan nomor urut ke kolom A
            $sheet->setCellValue('A' . $row, $idNumber);

            // Gunakan createDrawing() untuk setiap gambar yang ingin ditambahkan
            $this->createDrawing(
                'Cover',
                'Cover Buku',
                FCPATH . $buku['file_cover_buku'],
                60,
                'B' . $row,
                $sheet
            );

            $sheet->setCellValue('C' . $row, $buku['judul_buku']);
            $sheet->setCellValue('D' . $row, $buku['nama_kategori']);
            $sheet->setCellValue('E' . $row, $buku['deskripsi']);

            // Tingkatkan nomor ID dan baris
            $idNumber++;
            $row++;
        }

        // Siapkan untuk diunduh
        $fileName = 'daftar_buku.xlsx';

        // Set header untuk file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $this->writer->save('php://output');
        exit;
    }
}
