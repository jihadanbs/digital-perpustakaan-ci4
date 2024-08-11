<?= $this->include('admin/layouts/script') ?>

<style>
    .separator {
        border-right: 1px solid #ccc;
        height: auto;
    }

    .form-check {
        margin-bottom: 10px;
    }
</style>

<!-- saya nonaktifkan agar agar side bar tidak dapat di klik sembarangan -->
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
                        <h4 class="mb-sm-0 font-size-18">Formulir Tambah Data Buku - <?= esc($nama_lengkap) ?> | Id User : <?= esc($id_user) ?></h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tambah Buku</a></li>
                                <li class="breadcrumb-item active">Formulir Tambah Data Buku</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row justify-content-center">

                <div class="col-10">
                    <div class="card border border-secondary rounded p-4">
                        <div class="card-body">
                            <h2 class="text-center mb-4">Formulir Tambah Data Buku</h2>

                            <form action="<?= esc(site_url('admin/users/save/' . $id_user), 'attr') ?>" method="POST" enctype="multipart/form-data" id="validationForm" novalidate>
                                <?= csrf_field(); ?>
                                <?php if (is_object($validation)) : ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 separator">
                                            <label for="judul_buku" class="col-form-label">Judul Buku <span style="color: red;">*</span></label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control <?= ($validation->hasError('judul_buku')) ? 'is-invalid' : ''; ?>" id="judul_buku" name="judul_buku" placeholder="Masukkan Judul Buku" style="background-color: white;" value="<?= esc(old('judul_buku'), 'attr') ?>" required>
                                                <small class="form-text text-muted">Cek Kembali Judul Anda</small>
                                                <div class="invalid-feedback">
                                                    <?= esc($validation->getError('judul_buku'), 'html') ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- View -->
                                        <div class="col-md-6 mb-3">
                                            <label for="id_kategori_buku" class="col-form-label">Nama Kategori Buku <span style="color: red;">*</span></label>
                                            <select class="form-select custom-border <?= ($validation->hasError('id_kategori_buku')) ? 'is-invalid' : ''; ?>" id="id_kategori_buku" name="id_kategori_buku" aria-label="Default select example" style="background-color: white;" required>
                                                <option value="" selected disabled>~ Silahkan Pilih Nama Kategori Informasi ~</option>
                                                <?php if (!empty($tb_kategori_buku)) : ?>
                                                    <?php foreach ($tb_kategori_buku as $value) : ?>
                                                        <option value="<?= esc($value['id_kategori_buku'], 'attr') ?>" <?= old('id_kategori_buku') == $value['id_kategori_buku'] ? 'selected' : ''; ?>>
                                                            <?= esc($value['nama_kategori'], 'html') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <option value="">Tidak ada kategori yang tersedia</option>
                                                <?php endif; ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                <?= esc($validation->getError('id_kategori_buku'), 'html') ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label for="jumlah" class="col-form-label">Jumlah <span style="color: red;">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control <?= ($validation->hasError('jumlah')) ? 'is-invalid' : ''; ?>" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah Angka" style="background-color: white;" value="<?= esc(old('jumlah'), 'attr') ?>" required>
                                            <div class="invalid-feedback">
                                                <?= esc($validation->getError('jumlah'), 'html') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="col-form-label">Deskripsi <span style="color: red;">*</span></label>
                                        <textarea class="form-control custom-border <?= ($validation->hasError('deskripsi')) ? 'is-invalid' : ''; ?>" required name="deskripsi" placeholder="Masukkan Deskripsi Buku" id="deskripsi" cols="30" rows="5" style="background-color: white;"><?php echo esc(old('deskripsi'), 'html'); ?></textarea>
                                        <div class="invalid-feedback">
                                            <?= esc($validation->getError('deskripsi'), 'html') ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="file_cover_buku" class="col-form-label">Cover Buku <span style="color: red;">*</span></label>
                                        <input type="file" accept="image/*" class="form-control <?= ($validation->hasError('file_cover_buku')) ? 'is-invalid' : ''; ?>" id="file_cover_buku" name="file_cover_buku" style="background-color: white;" <?= (old('file_cover_buku')) ? 'disabled' : 'required'; ?> onchange="previewImage(event)">
                                        <small class="form-text text-muted">Pastikan Cover Buku yang diunggah tidak lebih dari 5MB</small>
                                        <br>
                                        <img id="preview" src="#" alt="Pratinjau cover buku" style="display: none; max-width: 200px; max-height: 200px; margin-top: 10px;">
                                        <div class="invalid-feedback">
                                            <?= esc($validation->getError('file_cover_buku'), 'html') ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="file_buku" class="col-form-label">File Buku <span style="color: red;">*</span></label>
                                        <input type="file" accept="application/pdf" class="form-control custom-border" id="file_buku" name="file_buku" style="background-color: white;">
                                        <small class="form-text text-muted">Pastikan File Buku yang diunggah tidak lebih dari 50MB</small>
                                        <div class="invalid">
                                            <?= esc($validation->getError('file_buku'), 'html') ?>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="<?= esc(site_url('/admin/users/data/' . $id_user), 'attr') ?>" class="btn btn-secondary btn-md ml-3">
                                            <i class="fas fa-arrow-left"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary" style="background-color: #28527A; color:white; margin-left: 10px;">Tambah</button>
                                    </div>
                                <?php endif; ?>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->include('admin/layouts/footer') ?>
<!-- end main content-->

<?= $this->include('admin/layouts/script2') ?>

<!-- script menampilkan gambar setelah di inputkan -->
<script>
    function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function() {
            var dataURL = reader.result;
            var preview = document.getElementById('preview');
            preview.src = dataURL;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>
<!-- end script -->