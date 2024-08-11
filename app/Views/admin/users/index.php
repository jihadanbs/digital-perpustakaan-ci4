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
            top: -100px;
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


<?= $this->include('admin/layouts/navbar') ?>
<?= $this->include('admin/layouts/sidebar') ?>
<?= $this->include('admin/layouts/rightsidebar') ?>

<?= $this->section('content'); ?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Data Users</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                                <li class="breadcrumb-item active">Data Users</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
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

                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1 mb-3">
                                <i class="fa fa-print"></i> Print
                            </a>

                            <table id="example1" class="table table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr class="highlight text-center" style="background-color: #28527A; color: white;">
                                        <th>Nomor</th>
                                        <th>Profil</th>
                                        <th>Nama User</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables akan mengisi tabel ini melalui Ajax -->
                                </tbody>
                            </table>

                            <small class="form-text text-danger">Note : Gunakan Data Sebijak Mungkin !</small>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <?= $this->include('user/layouts/footer') ?>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <?= $this->include('user/layouts/script2') ?>

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
                    "url": "<?= site_url('admin/users/get_data') ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.csrf_token_name = $('meta[name="csrf-token"]').attr('content');
                    },
                    "error": function(xhr, error, thrown) {
                        console.log(xhr.responseText);
                    }
                },
                "columns": [{
                        "data": 0
                    },
                    {
                        "data": 1
                    },
                    {
                        "data": 2
                    },
                    {
                        "data": 3
                    },
                    {
                        "data": 4
                    },
                    {
                        "data": 5
                    }
                ]
            });
        });
    </script>

    <!-- END MEMANGGIL DATATABLE -->