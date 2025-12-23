<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($izin) ? 'Edit' : 'Tambah' ?> Izin Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('walikelas/izinsiswa') ?>">Izin Siswa</a></li>
                    <li class="breadcrumb-item active"><?= isset($izin) ? 'Edit' : 'Tambah' ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Izin Siswa</h3>
            </div>
            <form action="<?= isset($izin) ? base_url('walikelas/izinsiswa/edit/'.$izin->id) : base_url('walikelas/izinsiswa/add') ?>" method="post">
                <div class="card-body">
                    
                    <div class="form-group">
                        <label>Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-control select2" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach($students as $student): ?>
                                <option value="<?= $student->id ?>" <?= isset($izin) && $izin->siswa_id == $student->id ? 'selected' : '' ?>>
                                    <?= $student->nis ?> - <?= $student->nama_lengkap ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Pilih siswa yang akan diizinkan</small>
                    </div>

                    <div class="form-group">
                        <label>Jenis Izin <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis" id="sakit" value="sakit" <?= isset($izin) && $izin->jenis == 'sakit' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="sakit">
                                    <span class="badge badge-warning">Sakit</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis" id="izin" value="izin" <?= isset($izin) && $izin->jenis == 'izin' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="izin">
                                    <span class="badge badge-info">Izin</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="<?= isset($izin) ? $izin->tanggal_mulai : date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="<?= isset($izin) ? $izin->tanggal_selesai : date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alasan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="4" required><?= isset($izin) ? $izin->alasan : '' ?></textarea>
                        <small class="text-muted">Jelaskan alasan siswa tidak masuk</small>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="3"><?= isset($izin) ? $izin->keterangan : '' ?></textarea>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('walikelas/izinsiswa') ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</section>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: '-- Pilih Siswa --'
    });
});
</script>
