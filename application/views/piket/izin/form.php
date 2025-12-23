<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($izin) ? 'Edit' : 'Tambah' ?> Izin KBM</h1>
        <a href="<?= base_url('piket/izin-kbm') ?>" class="btn btn-secondary">
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

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Izin KBM</h6>
        </div>
        <div class="card-body">
            <form action="<?= isset($izin) ? base_url('piket/izin-kbm/update/' . $izin['id']) : base_url('piket/izin-kbm/simpan') ?>" method="post">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="siswa_id">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($siswa_list as $siswa): ?>
                                    <option value="<?= $siswa['id'] ?>" 
                                        <?= (isset($izin) && $izin['siswa_id'] == $siswa['id']) ? 'selected' : '' ?>>
                                        [<?= $siswa['nama_kelas'] ?>] <?= $siswa['nis'] ?> - <?= $siswa['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" 
                                   value="<?= isset($izin) ? $izin['tanggal'] : date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis">Jenis Izin <span class="text-danger">*</span></label>
                            <select name="jenis" id="jenis" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="masuk_terlambat" <?= (isset($izin) && $izin['jenis'] == 'masuk_terlambat') ? 'selected' : '' ?>>Masuk Terlambat</option>
                                <option value="keluar_awal" <?= (isset($izin) && $izin['jenis'] == 'keluar_awal') ? 'selected' : '' ?>>Keluar Awal</option>
                                <option value="tidak_masuk" <?= (isset($izin) && $izin['jenis'] == 'tidak_masuk') ? 'selected' : '' ?>>Tidak Masuk</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jam_izin">Jam Izin <span class="text-danger">*</span></label>
                            <input type="time" name="jam_izin" id="jam_izin" class="form-control" 
                                   value="<?= isset($izin) ? $izin['jam_izin'] : date('H:i') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jam_kembali">Jam Kembali</label>
                            <input type="time" name="jam_kembali" id="jam_kembali" class="form-control" 
                                   value="<?= isset($izin) ? $izin['jam_kembali'] : '' ?>">
                            <small class="form-text text-muted">Opsional</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alasan">Alasan <span class="text-danger">*</span></label>
                    <textarea name="alasan" id="alasan" class="form-control" rows="4" required><?= isset($izin) ? $izin['alasan'] : '' ?></textarea>
                    <small class="form-text text-muted">
                        Contoh: Sakit kepala, Keperluan keluarga, Terlambat kendaraan, dll.
                    </small>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('piket/izin-kbm') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
$(document).ready(function() {
    $('#siswa_id').select2({
        placeholder: '-- Pilih Siswa --',
        allowClear: true,
        width: '100%'
    });
});
</script>
