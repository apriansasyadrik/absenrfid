<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($izin) ? 'Edit' : 'Tambah' ?> Izin KBM</h1>
        <a href="<?= base_url('piket/izin') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Izin KBM</h6></div>
        <div class="card-body">
            <form method="post">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kelas <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" id="kelas_id" name="kelas_id" required onchange="loadSiswa()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <option value="<?= $kelas['id'] ?>" <?= (isset($izin) && $izin->kelas_id == $kelas['id']) ? 'selected' : '' ?>>
                                    <?= $kelas['nama_kelas'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Siswa <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control select2" name="siswa_id" id="siswa_id" required>
                            <option value="">-- Pilih Siswa --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" name="tanggal" value="<?= isset($izin) ? $izin->tanggal : date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jenis <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Masuk Terlambat" <?= (isset($izin) && $izin->jenis == 'Masuk Terlambat') ? 'selected' : '' ?>>Masuk Terlambat</option>
                            <option value="Keluar Awal" <?= (isset($izin) && $izin->jenis == 'Keluar Awal') ? 'selected' : '' ?>>Keluar Awal</option>
                            <option value="Tidak Masuk" <?= (isset($izin) && $izin->jenis == 'Tidak Masuk') ? 'selected' : '' ?>>Tidak Masuk</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jam Izin <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="time" class="form-control" name="jam_izin" value="<?= isset($izin) ? $izin->jam_izin : date('H:i') ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jam Kembali</label>
                    <div class="col-sm-9">
                        <input type="time" class="form-control" name="jam_kembali" value="<?= isset($izin) ? $izin->jam_kembali : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Alasan <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="alasan" rows="4" required><?= isset($izin) ? $izin->alasan : '' ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="<?= base_url('piket/izin') ?>" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('.select2').select2({theme: 'bootstrap4', width: '100%'});

function loadSiswa() {
    var kelas_id = $('#kelas_id').val();
    if (kelas_id) {
        $.post('<?= base_url('piket/izin/get_siswa_by_kelas') ?>', {kelas_id: kelas_id}, function(data) {
            var siswa = JSON.parse(data);
            var options = '<option value="">-- Pilih Siswa --</option>';
            siswa.forEach(function(s) {
                var selected = <?= isset($izin) ? $izin->siswa_id : 0 ?> == s.id ? 'selected' : '';
                options += '<option value="'+s.id+'" '+selected+'>'+s.nis+' - '+s.nama_lengkap+'</option>';
            });
            $('#siswa_id').html(options);
        });
    }
}

<?php if (isset($izin)): ?>
$(document).ready(function() { loadSiswa(); });
<?php endif; ?>
</script>
