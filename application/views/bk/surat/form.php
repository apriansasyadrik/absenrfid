<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($surat) ? 'Edit' : 'Buat' ?> Surat Panggilan</h1>
        <a href="<?= base_url('bk/surat') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Surat</h6></div>
        <div class="card-body">
            <form method="post">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Siswa <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control select2" name="siswa_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($siswa_list as $siswa): ?>
                                <option value="<?= $siswa->id ?>" <?= (isset($surat) && $surat->siswa_id == $siswa->id) ? 'selected' : '' ?>>
                                    <?= $siswa->nis ?> - <?= $siswa->nama_lengkap ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nomor Surat <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nomor_surat" value="<?= isset($surat) ? $surat->nomor_surat : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tanggal Surat <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" name="tanggal_surat" value="<?= isset($surat) ? $surat->tanggal_surat : date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Waktu Panggilan <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="datetime-local" class="form-control" name="waktu_panggilan" value="<?= isset($surat) ? date('Y-m-d\TH:i', strtotime($surat->waktu_panggilan)) : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Perihal <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="perihal" rows="4" required><?= isset($surat) ? $surat->perihal : '' ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Keterangan</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="keterangan" rows="3"><?= isset($surat) ? $surat->keterangan : '' ?></textarea>
                    </div>
                </div>
                <?php if (isset($surat)): ?>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="status">
                            <option value="Belum Dipanggil" <?= $surat->status == 'Belum Dipanggil' ? 'selected' : '' ?>>Belum Dipanggil</option>
                            <option value="Sudah Dipanggil" <?= $surat->status == 'Sudah Dipanggil' ? 'selected' : '' ?>>Sudah Dipanggil</option>
                            <option value="Hadir" <?= $surat->status == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                            <option value="Tidak Hadir" <?= $surat->status == 'Tidak Hadir' ? 'selected' : '' ?>>Tidak Hadir</option>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <a href="<?= base_url('bk/surat') ?>" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>$('.select2').select2({theme: 'bootstrap4', width: '100%'});</script>
