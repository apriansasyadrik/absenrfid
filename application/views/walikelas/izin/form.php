<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($izin) ? 'Edit' : 'Tambah' ?> Izin Siswa</h1>
        <a href="<?= base_url('walikelas/izin') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Izin Siswa</h6>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kelas <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= $kelas_walikelas->tingkat ?> <?= $kelas_walikelas->nama_kelas ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Siswa <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control select2" name="siswa_id" id="siswa_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>" 
                                    <?= (isset($izin) && $izin->siswa_id == $student['id']) ? 'selected' : '' ?>>
                                    <?= $student['nis'] ?> - <?= $student['nama_lengkap'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" name="tanggal" id="tanggal" 
                            value="<?= isset($izin) ? $izin->tanggal : date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jenis Izin <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" name="jenis" id="jenis" required>
                            <option value="">-- Pilih Jenis Izin --</option>
                            <option value="Sakit" <?= (isset($izin) && $izin->jenis == 'Sakit') ? 'selected' : '' ?>>Sakit (S)</option>
                            <option value="Izin" <?= (isset($izin) && $izin->jenis == 'Izin') ? 'selected' : '' ?>>Izin (I)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Keterangan <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="4" required><?= isset($izin) ? $izin->keterangan : '' ?></textarea>
                        <small class="form-text text-muted">Masukkan alasan izin secara detail</small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('walikelas/izin') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- End of Main Content -->

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
