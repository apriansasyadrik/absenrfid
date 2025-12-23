<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($surat) ? 'Edit' : 'Buat' ?> Surat Panggilan</h1>
        <a href="<?= base_url('bk/surat') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Surat Panggilan</h6>
        </div>
        <div class="card-body">
            <form action="<?= isset($surat) ? base_url('bk/surat/update/' . $surat['id']) : base_url('bk/surat/simpan') ?>" method="post">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control" 
                                   value="<?= isset($surat) ? $surat['nomor_surat'] : $nomor_surat ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($siswa_list as $siswa): ?>
                                    <option value="<?= $siswa['id'] ?>" 
                                        <?= (isset($surat) && $surat['siswa_id'] == $siswa['id']) ? 'selected' : '' ?>>
                                        [<?= $siswa['nama_kelas'] ?>] <?= $siswa['nis'] ?> - <?= $siswa['nama'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Hari <span class="text-danger">*</span></label>
                            <select name="hari" class="form-control" required>
                                <option value="">-- Pilih Hari --</option>
                                <?php 
                                $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                foreach ($hari_list as $hari):
                                ?>
                                    <option value="<?= $hari ?>" <?= (isset($surat) && $surat['hari'] == $hari) ? 'selected' : '' ?>>
                                        <?= $hari ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" 
                                   value="<?= isset($surat) ? $surat['tanggal'] : date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Waktu <span class="text-danger">*</span></label>
                            <input type="time" name="waktu" class="form-control" 
                                   value="<?= isset($surat) ? $surat['waktu'] : '08:00' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Perihal <span class="text-danger">*</span></label>
                    <textarea name="perihal" class="form-control" rows="5" required><?= isset($surat) ? $surat['perihal'] : 'Panggilan orang tua/wali siswa untuk membahas masalah kehadiran siswa.' ?></textarea>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('bk/surat') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    $('#siswa_id').select2({
        placeholder: '-- Pilih Siswa --',
        allowClear: true,
        width: '100%'
    });
});
</script>
