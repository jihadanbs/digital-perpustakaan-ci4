<?= $this->include('admin/layouts/script') ?>
<style>
    /* CSS untuk readmore */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }


    .read-more-link {
        color: blue;
        text-decoration: underline;
        cursor: pointer;
    }

    /* CSS untuk print */
    @media print {
        .no-print {
            display: none;
        }

        .card-body {
            border: 1px solid #000;
            /* Border untuk bagian dalam */
            padding: 5px;
            /* Tambahkan padding untuk estetika */
        }

        /* Contoh tambahan untuk tabel */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            /* Tambahkan border untuk sel tabel */
            padding: 5px;
        }
    }
</style>
<div class="col-md-12">

    <!-- saya nonaktifkan agar side bar tidak dapat di klik sembarangan -->
    <div style="pointer-events: none;">
        <?= $this->include('admin/layouts/navbar') ?>
        <?= $this->include('admin/layouts/sidebar') ?>
    </div>
    <?= $this->include('admin/layouts/rightsidebar') ?>

    <?= $this->section('content'); ?>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Formulir Cek Data</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Data Buku</a></li>
                                    <li class="breadcrumb-item active">Formulir Cek Data</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="card card-outline card-primary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">PERPUS DIGITAL</h3>
                        <div>
                            <a href="javascript:window.print()" class="btn btn-success btn-md waves-effect waves-light ml-3 no-print">
                                <i class="fa fa-print"></i> Print
                            </a>
                            <a href="<?= site_url('/admin/users/exportExcel') ?>" class="btn btn-success waves-effect waves-light ml-3 no-print">
                                <i class="fa fa-file-excel"></i> Export to Excel
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <h4 class="text-center mb-3"><b>Formulir Cek Data Buku</b></h4>
                            <?php if (!empty($tb_buku)) : ?>
                                <tr>
                                    <td rowspan="17" width="250px" class="text-center">
                                        <?php if ($tb_buku[0]->file_cover_buku ?? '') : ?>
                                            <a href="<?= esc(site_url($tb_buku[0]->file_cover_buku), 'attr') ?>" title="Lihat gambar" target="_blank">
                                                <img src="<?= esc(site_url($tb_buku[0]->file_cover_buku), 'attr') ?>" width="150px" height="200px" alt="Gambar Penulis" id="gambar_load" style="border: 2px solid #ccc; border-radius: 10px; box-shadow: 2px 2px 8px rgba(0,0,0,0.0);">
                                            </a>
                                        <?php else : ?>
                                            <a href="#" title="File tidak tersedia">
                                                <img src="<?= site_url('path/to/default/image.jpg') ?>" width="250px" height="200px" alt="Gambar tidak tersedia" id="gambar_load" style="border: 2px solid #ccc; border-radius: 5px; box-shadow: 2px 2px 8px rgba(0,0,0,0.0);">
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <th width="170px">Judul</th>
                                    <th width="30px" class="text-center">:</th>
                                    <td><?= esc($tb_buku[0]->judul_buku ?? '', 'html') ?> </td>
                                </tr>
                                <tr>
                                    <th>Kategori Buku</th>
                                    <th class="text-center">:</th>
                                    <td><?= esc($tb_buku[0]->nama_kategori ?? '', 'html') ?></td>
                                </tr>
                                <tr>
                                    <th width="150px">Jumlah</th>
                                    <th width="30px" class="text-center">:</th>
                                    <td><?= esc($tb_buku[0]->jumlah ?? '', 'html') ?></td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-center">:</th>
                                    <td class="readmore">
                                        <strong><?= esc(truncateText($tb_buku[0]->deskripsi, 255) ?? 'Belum ada deskripsi lebih lanjut', 'html') ?>
                                            <?php if (strlen(strip_tags($tb_buku[0]->deskripsi)) > 255) : ?>
                                                <a href="#" class="read-more-link" data-text="<?= esc(strip_tags($tb_buku[0]->deskripsi), 'attr') ?>">Read more..</a>
                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                </tr>

                                <!-- Modal Structure -->
                                <div id="readMoreModal" class="modal">
                                    <div class="modal-content">
                                        <span class="close">&times;</span>
                                        <p id="modal-text"></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </table>

                        <table class="table table-bordered table-sm">
                            <thead class="text-center">
                                <tr>
                                    <th width="50px">NO</th>
                                    <th>Dokumen Data Buku</th>
                                    <th width="100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php if (is_array($dokumen) || is_object($dokumen)) : ?>
                                    <?php foreach ($dokumen as $key => $value) : ?>
                                        <tr>
                                            <td class="text-center"><?= esc($no++, 'html') ?>.</td>
                                            <td><?= esc($value->judul_buku, 'html') ?></td>
                                            <td class="text-center">
                                                <?php if ($value->file_buku) : ?>
                                                    <a href="<?= esc(base_url($value->file_buku), 'attr') ?>" class="btn btn-info btn-sm view" target="_blank">
                                                        <i class="fas fa-eye"></i> View File
                                                    </a>
                                                <?php else : ?>
                                                    <a href="#" class="btn btn-info btn-sm view disabled" title="File tidak tersedia">
                                                        <i class="fas fa-eye"></i> View File
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3">Tidak ada data untuk ditampilkan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="form-group mb-4 mt-4">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?= esc(site_url('admin/users'), 'attr') ?>" class="btn btn-secondary btn-md ml-3 no-print">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <a href="<?= esc(site_url('admin/users/edit/' . $value->id_buku), 'attr') ?>" class="btn btn-warning btn-md edit no-print">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-md ml-3 waves-effect waves-light sa-warning no-print" data-id="<?= $value->id_buku ?>">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?= $this->include('admin/layouts/footer') ?>
</div>

<?= $this->include('admin/layouts/script2') ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("readMoreModal");
        const modalText = document.getElementById("modal-text");
        const closeBtn = document.querySelector(".modal .close");

        document.querySelectorAll(".read-more-link").forEach(link => {
            link.addEventListener("click", function(event) {
                event.preventDefault();
                const fullText = this.getAttribute("data-text");
                modalText.innerText = fullText;
                modal.style.display = "block";
            });
        });

        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    });
</script>

<!-- HAPUS -->
<script>
    $(document).ready(function() {
        $('.sa-warning').click(function(e) {
            e.preventDefault();
            var id_buku = $(this).data('id');

            Swal.fire({
                title: "Anda Yakin Ingin Menghapus?",
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28527A",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('/admin/users/deleteData2/' . $id_user) ?>",
                        data: {
                            id_buku: id_buku,
                            _method: 'DELETE'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    // Redirect ke halaman /user/buku setelah sukses menghapus
                                    window.location.href = '<?= site_url('/admin/users/data/' . $id_user) ?>';
                                });
                            } else if (response.status === 'error') {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Error",
                                text: "Terjadi kesalahan. Silakan coba lagi.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<!-- HAPUS -->
</body>

</html>