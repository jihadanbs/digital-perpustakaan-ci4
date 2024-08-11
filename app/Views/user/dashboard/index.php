<?= $this->include('user/layouts/script') ?>
</head>
<style>
    .greeting-card {
        position: relative;
        background-color: #28527a;
        border-radius: 15px;
        padding: 20px;
        color: #f4d160;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: smoothBounce 2s infinite ease-in-out;
    }

    .greeting-title {
        color: #FFF;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 10px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .greeting-message {
        font-size: 18px;
        line-height: 1.5;
        font-weight: bold;
    }

    .greeting-card img {
        max-width: 100px;
        transition: transform 0.3s;
    }

    .greeting-card img:hover {
        transform: scale(1.1);
    }

    @keyframes smoothBounce {

        0% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }

        100% {
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .greeting-title {
            font-size: 24px;
        }

        .greeting-message {
            font-size: 16px;
        }
    }
</style>

<body>
    <?= $this->include('user/layouts/navbar') ?>
    <?= $this->include('user/layouts/sidebar') ?>
    <?= $this->include('user/layouts/rightsidebar') ?>

    <?= $this->section('content'); ?>
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <!-- Greeting Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="greeting-card">
                            <div class="row align-items-center">
                                <div class="col-md-10">
                                    <h2 class="greeting-title mb-2">Selamat Datang Di Halaman User PERPUS DIGITAL</h2>
                                    <p class="greeting-message">"Setiap Langkah Kecil Membawa Kita Lebih Dekat Pada Tujuan Besar! &#128521"</p>
                                </div>
                                <div class="col-md-1 text-end">
                                    <img src="<?= base_url('assets/img/binmas.png') ?>" height="100px" alt="Welcome">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Buku -->
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100" style="background-color: #28527a;">
                            <div style="background-color: #28527a;"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <span class="text-muted mb-3 lh-1 d-block"></span>
                                        <h4 class="mb-3">
                                            <span class="counter-value ms-3" id="totalBukuCounter" data-target="0" style="color: #f4d160; font-size: 32px;">0</span>
                                        </h4>
                                    </div>

                                    <div class="col-6">
                                        <i class="fas fa-book-open fa-4x text-muted" style="color: #f4d160 !important;"></i>
                                    </div>
                                </div>
                                <div class="text-nowrap mt-4">
                                    <span class="ms-0 text-muted d-block text-truncate fw-bold" style="color: #f4d160 !important; font-size: 16px;">Jumlah Data Buku</span>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>
                <!-- end row-->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <?= $this->include('user/layouts/footer') ?>
    </div>
    <!-- end main content-->
    <?= $this->include('user/layouts/script2') ?>

    <!-- Script Total Data Buku -->
    <script>
        $(document).ready(function() {
            updateTotal();

            function updateTotal() {
                var id_user = "<?= $id_user ?>"; // Mendapatkan ID pengguna dari PHP
                getTotalFeedback("/user/buku/totalData/" + id_user, function(responsePemohon) {
                    // Update nilai total pada elemen dengan id "totalBukuCounter"
                    var total = parseInt(responsePemohon.total);
                    $("#totalBukuCounter").attr("data-target", total);
                    $("#totalBukuCounter").text(total);
                });
            }

            function getTotalFeedback(url, callback) {
                $.ajax({
                    url: url, // URL untuk total Buku
                    type: "GET",
                    success: function(response) {
                        callback(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }
        });
    </script>
</body>

</html>