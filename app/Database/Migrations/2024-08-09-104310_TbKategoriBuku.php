<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbKategoriBuku extends Migration
{
    public function up()
    {
        // membuat database tb_kategori_buku
        $this->forge->addField([
            'id_kategori_buku' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'nama_kategori' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id_kategori_buku', TRUE);
        $this->forge->addForeignKey('id_user', 'tb_user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_kategori_buku');
    }

    public function down()
    {
        // untuk menghapus (drop) tabel
        $this->forge->dropTable('tb_kategori_buku');
    }
}
