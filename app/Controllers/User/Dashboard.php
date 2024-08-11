<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        // WAJIB //
        $id_user = session()->get('id_user'); // Dapatkan id_user dari sesi
        $tb_user = $this->m_user->getAll();
        // END WAJIB //

        $data = [
            'title' => 'User | Dashboard',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
            // WAJIB //
            'tb_user' => $tb_user,
            'id_user' => $id_user // Kirim id_user ke view untuk digunakan di script
            // END WAJIB //
        ];

        // Jika pengguna tidak login dan mencoba mengakses halaman user dashboard, arahkan kembali dan beri pesan
        if (!$this->session->has('islogin') || session()->get('id_jabatan') != 2) {
            return redirect()->to('authentication/login')->with('gagal', 'Anda belum login');
        } else {
            return view('user/dashboard/index', $data); // Tampilkan dashboard jika pengguna sudah login
        }
    }
}
