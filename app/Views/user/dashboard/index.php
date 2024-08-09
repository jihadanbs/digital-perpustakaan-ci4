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


            </div>
            <!-- End Page-content -->

        </div>
        <!-- end main content-->
        <?= $this->include('user/layouts/footer') ?>

    </div>
    <!-- END layout-wrapper -->
    <?= $this->include('user/layouts/script2') ?>

    <!-- Script Total Data Per Fitur -->


</body>

</html>