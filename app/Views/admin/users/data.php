<?= $this->include('admin/layouts/script') ?>
<style>
    /* Cetak PDF */
    @media print {

        /* Sembunyikan elemen lain */
        body * {
            visibility: hidden;
        }

        /* Hanya tampilkan tabel dengan ID 'example1' */
        #example1,
        #example1 * {
            visibility: visible;
        }

        /* Atur posisi tabel yang dicetak */
        #example1 {
            position: relative;
            width: 100%;
            top: -210px;
            /* Sesuaikan jika perlu */
            border-collapse: collapse;
        }

        /* Atur kolom tabel agar tidak terpotong */
        #example1 th,
        #example1 td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        /* Sembunyikan kolom 'Aksi' */
        #example1 th:last-child,
        #example1 td:last-child {
            display: none;
        }

        /* Footer cetak */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        /* Atur margin halaman cetak */
        @page {
            size: auto;
            margin: 10mm;
        }
    }

    /* Tombol Filter */
    .filter-container {
        display: flex;
        /* Menggunakan Flexbox */
        align-items: center;
        /* Vertikal rata tengah */
    }

    .filter-container select {
        width: auto;
        /* Panjang dropdown otomatis menyesuaikan isinya */
        min-width: 150px;
        /* Atur panjang minimum sesuai kebutuhan */
        margin-left: 10px;
        /* Jarak antara tombol dan dropdown */
    }

    .filter-container label {
        margin: 0 10px;
        /* Mengatur jarak label */
    }
</style>


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
                        <h4 class="mb-sm-0 font-size-18">Data Buku</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Buku</a></li>
                                <li class="breadcrumb-item active">Data Buku</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="filter-container mb-3 d-flex align-items-center">
                <label for="kategoriFilter" class="ms-3 me-2 mb-0">Kategori:</label>
                <select id="kategoriFilter" class="form-control" style="width: auto;">
                    <!-- Opsi kategori akan diisi oleh JavaScript -->
                </select>
                <button id="filterButton" class="btn btn-primary ms-3">Filter</button>
            </div>

            <div class="row">

                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <?php if (session()->getFlashdata('pesan')) : ?>
                                <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-all me-3 align-middle"></i><strong>Sukses</strong> -
                                    <?= session()->getFlashdata('pesan') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('gagal')) : ?>
                                <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-block-helper me-3 align-middle"></i><strong>Gagal</strong> -
                                    <?= session()->getFlashdata('gagal') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-3 mb-3">
                                <a href="<?= esc(site_url('admin/users/tambah/' . $id_user), 'attr') ?>" class="btn waves-effect waves-light" style="background-color: #28527A; color:white;">
                                    <i class="fas fa-plus font-size-16 align-middle me-2"></i> Tambah
                                </a>
                            </div>

                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1 mb-3">
                                <i class="fa fa-print"></i> Print
                            </a>

                            <a href="<?= site_url('/admin/users/exportExcel') ?>" class="btn btn-success waves-effect waves-light me-1 mb-3">
                                <i class="fa fa-file-excel"></i> Export to Excel
                            </a>
                            <input type="hidden" id="userId" value="<?= esc($id_user, 'html') ?>">
                            <table id="example1" class="table table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr class="highlight text-center" style="background-color: #28527A; color: white;">
                                        <th>Nomor</th>
                                        <th>Cover</th>
                                        <th>Judul Buku</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi tabel ini melalui Ajax -->
                                </tbody>
                            </table>
                            <a href="<?= esc(site_url('/admin/users'), 'attr') ?>" class="btn btn-secondary me-3">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <small class="form-text text-danger">Note : Bila Tidak Bisa Melakukan Delete Dihalaman Ini, Anda Bisa Melakukannya Pada Halaman CEK DATA BUKU</small>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <?= $this->include('admin/layouts/footer') ?>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <?= $this->include('admin/layouts/script2') ?>

    <!-- INISIALISASI MEMANGGIL DATATABLE -->
    <script>
        $(document).ready(function() {
            var table = $("#example1").DataTable({
                "paging": true,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= site_url('admin/users/ambilData') ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.csrf_token_name = $('meta[name="csrf-token"]').attr('content');
                        d.kategori = $('#kategoriFilter').val(); // Kirim filter kategori
                        d.id_user = $('#userId').val(); // Kirim id_user
                    }
                },
            });

            $.ajax({
                url: '<?= site_url('admin/users/getKategori') ?>',
                type: 'GET',
                data: {
                    kategori: $('#kategoriFilter').val(),
                    id_user: $('#userId').val() // Kirim id_user ke server
                },
                success: function(data) {
                    var kategoriSelect = $('#kategoriFilter');
                    kategoriSelect.empty(); // Hapus pilihan yang ada
                    kategoriSelect.append('<option value="">~ Semua Kategori ~</option>');
                    $.each(JSON.parse(data), function(index, kategori) {
                        kategoriSelect.append('<option value="' + kategori.id_kategori_buku + '">' + kategori.nama_kategori + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                }
            });

            $('#filterButton').on('click', function() {
                $("#example1").DataTable().ajax.reload(); // Muat ulang data tabel saat filter berubah
            });
        });
    </script>
    <!-- END MEMANGGIL DATATABLE -->

    <!-- HAPUS -->
    <script>
        $(document).ready(function() {
            $('#example1').on('click', '.sa-warning', function(e) {
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
                            url: "<?= site_url('/admin/users/deleteData/' . $id_user) ?>",
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