<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbBuku extends Migration
{
    public function up()
    {
        // membuat database tb_buku
        $this->forge->addField([
            'id_buku' => [
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
            'id_kategori_buku' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ],
            'judul_buku' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'jumlah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_cover_buku' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'file_buku' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id_buku', TRUE);
        // Menambahkan foreign key
        $this->forge->addForeignKey('id_kategori_buku', 'tb_kategori_buku', 'id_kategori_buku', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'tb_user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_buku');
    }

    public function down()
    {
        // untuk menghapus (drop) tabel
        $this->forge->dropTable('tb_buku');
    }
}
