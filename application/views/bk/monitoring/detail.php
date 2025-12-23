<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Monitoring Siswa</h1>
        <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Siswa Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Siswa</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">NIS</th>
                            <td><?= $siswa['nis'] ?></td>
                        </tr>
                        <tr>
                            <th>NISN</th>
                            <td><?= $siswa['nisn'] ?></td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td><strong><?= $siswa['nama'] ?></strong></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td><?= $siswa['nama_kelas'] ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?= $siswa['jenis_kelamin'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoring History -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Monitoring (12 Bulan Terakhir)</h6>
        </div>
        <div class="card-body">
            <?php if (count($siswa['monitoring_history']) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Alpha</th>
                                <th>Terlambat</th>
                                <th>Status</th>
                                <th>Catatan BK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $bulan_nama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            foreach ($siswa['monitoring_history'] as $mon): 
                            ?>
                                <tr class="<?= $mon['is_flagged'] ? 'table-warning' : '' ?>">
                                    <td><?= $bulan_nama[$mon['bulan']] ?></td>
                                    <td><?= $mon['tahun'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $mon['jumlah_alpha'] >= 5 ? 'danger' : ($mon['jumlah_alpha'] >= 3 ? 'warning' : 'success') ?>">
                                            <?= $mon['jumlah_alpha'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $mon['jumlah_terlambat'] >= 10 ? 'danger' : ($mon['jumlah_terlambat'] >= 5 ? 'warning' : 'success') ?>">
                                            <?= $mon['jumlah_terlambat'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($mon['is_flagged']): ?>
                                            <span class="badge badge-danger">Bermasalah</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Normal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $mon['catatan_bk'] ?: '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Belum ada riwayat monitoring</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Surat Panggilan History -->
    <?php if (count($siswa['surat_history']) > 0): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Surat Panggilan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Nomor Surat</th>
                            <th>Tanggal</th>
                            <th>Perihal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa['surat_history'] as $surat): ?>
                            <tr>
                                <td><?= $surat['nomor_surat'] ?></td>
                                <td><?= date('d/m/Y', strtotime($surat['tanggal'])) ?></td>
                                <td><?= $surat['perihal'] ?></td>
                                <td>
                                    <a href="<?= base_url('bk/surat/cetak/' . $surat['id']) ?>" 
                                       target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
