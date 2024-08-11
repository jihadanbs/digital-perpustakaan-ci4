<?= $this->include('user/layouts/script') ?>

<style>
    .separator {
        border-right: 1px solid #ccc;
        height: auto;
    }
</style>

<!-- saya nonaktifkan agar side bar tidak dapat di klik sembarangan -->
<div style="pointer-events: none;">
    <?= $this->include('user/layouts/navbar') ?>
    <?= $this->include('user/layouts/sidebar') ?>
</div>
<?= $this->include('user/layouts/rightsidebar') ?>

<?= $this->section('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Formulir Ubah Data</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Cek Data</a></li>
                                <li class="breadcrumb-item active">Formulir Ubah Data</li>
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
                            <h2 class="text-center mb-4">Formulir Ubah Data Buku</h2>
                            <?php if (is_string($tb_buku) || is_object($tb_buku)) : ?>
                                <form action="<?= esc(site_url('/user/buku/update/' . $tb_buku->id_buku), 'attr') ?>" method="post" enctype="multipart/form-data" id="validationForm" novalidate>
                                    <input type="hidden" name="_method" value="PUT">
                                    <?= csrf_field(); ?>
                                    <?php if (is_object($validation)) : ?>
                                        <div class="row">
                                            <div class="col-md-6 mb-3 separator">
                                                <label for="judul_buku" class="col-form-label">Judul Buku <span style="color: red;">*</span></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control <?= ($validation->hasError('judul_buku')) ? 'is-invalid' : ''; ?>" id="judul_buku" autofocus name="judul_buku" placeholder="Masukkan Judul Buku" style="background-color: white;" value="<?= esc(old('judul_buku', $tb_buku->judul_buku), 'attr') ?>">
                                                    <div class="invalid-feedback">
                                                        <?= esc($validation->getError('judul_buku'), 'html') ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="id_kategori_buku" class="col-form-label">Nama Kategori Buku <span style="color: red;">*</span></label>
                                                <select class="form-select custom-border <?= ($validation->hasError('id_kategori_buku')) ? 'is-invalid' : ''; ?>" id="id_kategori_buku" name="id_kategori_buku" aria-label="Default select example" style="background-color: white;" required>
                                                    <option value="" selected disabled>~ Silahkan Pilih Nama Kategori Buku ~</option>
                                                    <?php if (is_array($tb_kategori_buku) || is_object($tb_kategori_buku)) : ?>
                                                        <?php foreach ($tb_kategori_buku as $value) : ?>
                                                            <?php $selected = ($value['id_kategori_buku'] == old('id_kategori_buku', $tb_buku->id_kategori_buku)) ? 'selected' : ''; ?>
                                                            <option value="<?= esc($value['id_kategori_buku'], 'attr') ?>" <?= esc($selected, 'attr') ?>><?= esc($value['nama_kategori'], 'html') ?></option>
                                                        <?php endforeach; ?>
                                                    <?php else : ?>
                                                        <tr>
                                                            <td colspan="3">Tidak ada data untuk ditampilkan.</td>
                                                        </tr>
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
                                                <input type="number" class="form-control <?= ($validation->hasError('jumlah')) ? 'is-invalid' : ''; ?>" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah Angka" style="background-color: white;" value="<?= esc(old('jumlah', $tb_buku->jumlah), 'attr') ?>" required>
                                                <div class="invalid-feedback">
                                                    <?= esc($validation->getError('jumlah'), 'html') ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="deskripsi" class="col-form-label">Deskripsi <span style="color: red;">*</span></label>
                                            <textarea class="form-control custom-border <?= ($validation->hasError('deskripsi')) ? 'is-invalid' : ''; ?>" name="deskripsi" placeholder="Masukkan Isi Deskripsi Buku" id="deskripsi" cols="30" rows="5" style="background-color: white;"><?= esc(old('deskripsi', $tb_buku->deskripsi), 'html') ?></textarea>
                                            <div class="invalid-feedback">
                                                <?= esc($validation->getError('deskripsi'), 'html') ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="file_cover_buku" class="col-form-label">Cover Buku <span style="color: red;">*</span></label>
                                            <input type="file" accept="image/*" class="form-control <?= ($validation->hasError('file_cover_buku')) ? 'is-invalid' : ''; ?>" id="file_cover_buku" name="file_cover_buku" style="background-color: white;" onchange="previewNewImage(this);">
                                            <input type="hidden" name="current_file_cover_buku" value="<?= esc(old('current_file_cover_buku', $tb_buku->file_cover_buku), 'attr') ?>">
                                            <small class="form-text text-muted">Pastikan Cover Buku yang diunggah tidak lebih dari 5MB</small>

                                            <div class="mt-2 d-flex align-items-center">
                                                <?php if (old('current_file_cover_buku', $tb_buku->file_cover_buku)) : ?>
                                                    <div class="me-2">
                                                        <img src="<?= esc(base_url($tb_buku->file_cover_buku), 'attr') ?>" alt="cover Buku Sekarang" style="max-width: 150px; max-height: 150px;">
                                                        <p class="text-center">cover buku saat ini</p>
                                                    </div>
                                                <?php endif; ?>

                                                <div id="newImagePreview" class="me-2">
                                                    <!-- Pratinjau gambar baru akan ditampilkan di sini -->
                                                </div>
                                            </div>

                                            <div class="invalid-feedback">
                                                <?= esc($validation->getError('file_cover_buku'), 'html') ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="file_buku" class="col-form-label">File Buku <span style="color: red;">*</span></label>
                                            <input type="file" accept="application/pdf" class="form-control <?= ($validation->hasError('file_buku')) ? 'is-invalid' : ''; ?>" id="file_buku" name="file_buku" style="background-color: white;">
                                            <input type="hidden" name="current_file_buku" value="<?= esc(old('current_file_buku', $tb_buku->file_buku), 'attr') ?>">
                                            <small class="form-text text-muted">Pastikan File Buku yang diunggah tidak lebih dari 50MB</small>
                                            <?php if (old('current_file_buku', $tb_buku->file_buku)) : ?>
                                                <div class="mt-2">
                                                    <p>file buku saat ini:
                                                        <a href="<?= esc(base_url($tb_buku->file_buku), 'attr') ?>" target="_blank">
                                                            <?= esc(old('current_file_buku', $tb_buku->file_buku), 'html') ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="invalid-feedback">
                                                <?= esc($validation->getError('file_buku'), 'html') ?>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4 mt-4">
                                            <div class="d-grid gap-2 d-md-flex justify-content-end">
                                                <a href="<?= esc(site_url('/user/buku/cek_data/' . $tb_buku->slug), 'attr') ?>" class="btn btn-secondary btn-md ml-3">
                                                    <i class="fas fa-arrow-left"></i> Kembali
                                                </a>
                                                <button type="submit" class="btn btn-primary ">Ubah Data</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </form>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-danger">Note : Bila Tidak Ingin Mengubah Cover Buku dan File Buku, Silahkan Klik Tombol "Ubah Data", Data Cover dan File Tetap Sama Dengan Sebelumnya &#x1F609;</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->include('user/layouts/footer') ?>
<!-- end main content-->
<?= $this->include('user/layouts/script2') ?>

<!-- autofocus input edit langsung kebelakang kata -->
<script>
    window.addEventListener('DOMContentLoaded', function() {
        var inputJudul = document.getElementById('judul_buku');

        // Fungsi untuk mengatur fokus ke posisi akhir input
        function setFocusToEnd(element) {
            element.focus();
            var val = element.value;
            element.value = ''; // kosongkan nilai input
            element.value = val; // isi kembali nilai input untuk memindahkan fokus ke posisi akhir
        }

        // Panggil fungsi setFocusToEnd setelah DOM selesai dimuat
        setFocusToEnd(inputJudul);
    });
</script>
<!-- end autofocus input edit langsung kebelakang kata -->

<script>
    function previewNewImage(input) {
        const previewContainer = document.getElementById('newImagePreview');
        previewContainer.innerHTML = ''; // Clear any previous content

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                img.alt = 'Pratinjau Cover Buku Baru';
                previewContainer.appendChild(img);

                const caption = document.createElement('p');
                caption.textContent = 'cover buku baru';
                caption.className = 'text-center';
                previewContainer.appendChild(caption);
            }

            reader.readAsDataURL(file);
        }
    }
</script>