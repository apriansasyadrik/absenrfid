<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Siswa Bermasalah</h1>
        <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Data Siswa</h6></div>
                <div class="card-body">
                    <p><strong>NIS:</strong> <?= $siswa['nis'] ?></p>
                    <p><strong>Nama:</strong> <?= $siswa['nama_lengkap'] ?></p>
                    <p><strong>Kelas:</strong> <?= $siswa['nama_kelas'] ?></p>
                    <p><strong>Total Alpha:</strong> <span class="badge badge-danger"><?= count($riwayat_alpha) ?></span></p>
                    <p><strong>Total Terlambat:</strong> <span class="badge badge-warning"><?= count($riwayat_terlambat) ?></span></p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Catatan BK</h6></div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('bk/monitoring/catatan/'.$siswa['id']) ?>">
                        <div class="form-group">
                            <textarea class="form-control" name="catatan" rows="5"><?= $catatan ? $catatan->catatan : '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Simpan Catatan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Riwayat Alpha</h6></div>
                <div class="card-body">
                    <?php if (count($riwayat_alpha) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Tanggal</th><th>Mapel</th><th>Jam</th></tr></thead>
                            <tbody>
                                <?php foreach ($riwayat_alpha as $alpha): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($alpha->tanggal)) ?></td>
                                    <td><?= $alpha->nama_mapel ?></td>
                                    <td><?= date('H:i', strtotime($alpha->jam_mulai)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center">Tidak ada riwayat alpha</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Riwayat Terlambat</h6></div>
                <div class="card-body">
                    <?php if (count($riwayat_terlambat) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Tanggal</th><th>Jam Masuk</th><th>Keterlambatan</th></tr></thead>
                            <tbody>
                                <?php foreach ($riwayat_terlambat as $terlambat): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($terlambat->tanggal)) ?></td>
                                    <td><?= date('H:i', strtotime($terlambat->jam_masuk)) ?></td>
                                    <td><?= $terlambat->menit_keterlambatan ?> menit</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center">Tidak ada riwayat terlambat</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
