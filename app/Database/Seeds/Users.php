<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Users extends Seeder
{
    public function run()
    {
        //menambahkan data dalam tabel user
        $data = [
            [
                'nama_lengkap' => 'Admin',
                'username' => 'admin',
                'id_jabatan' => '1',
                'password' => password_hash('1234', PASSWORD_DEFAULT),
                'status' => 'aktif',
                'email' => 'jihadanbs11@gmail.com',
                'no_telepon' => '088215212122',
                'file_profil' => 'admin.jpg'
            ],
            [
                'nama_lengkap' => 'Jihadan Beckhianosyuhada',
                'username' => 'jihadan',
                'id_jabatan' => '2',
                'status' => 'aktif',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'email' => 'jihadanbs11@gmail.com',
                'no_telepon' => '088212342233',
                'file_profil' => 'user.jpg'
            ],
        ];

        $this->db->table('tb_user')->insertBatch($data);
    }
}
