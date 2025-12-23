<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Wali Kelas</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <?php if ($kelas_wali): ?>
        <!-- Kelas Info -->
        <div class="alert alert-info">
            <i class="fas fa-users"></i> 
            <strong>Kelas yang Diwalikan:</strong> <?= $kelas_wali['tingkat'] ?> <?= $kelas_wali['nama_kelas'] ?>
        </div>

        <!-- Content Row - Stats -->
        <div class="row">

            <!-- Total Siswa -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Siswa</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_siswa ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Izin Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Izin Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $izin_bulan_ini ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Jadwal Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $schedules_today ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Izin -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Izin Siswa Terbaru</h6>
                <a href="<?= base_url('walikelas/izin-siswa') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-list"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <?php if (count($recent_izin) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_izin as $izin): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($izin['tanggal'])) ?></td>
                                        <td><?= $izin['nama_siswa'] ?></td>
                                        <td>
                                            <?php if ($izin['jenis'] == 'Sakit'): ?>
                                                <span class="badge badge-warning">Sakit</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Izin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $izin['keterangan'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">Belum ada data izin</p>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <!-- No Kelas Assigned -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5>Anda Belum Ditugaskan Sebagai Wali Kelas</h5>
                <p class="text-muted">Silakan hubungi admin untuk mendapatkan penugasan wali kelas.</p>
            </div>
        </div>
    <?php endif; ?>

</div>
<!-- /.container-fluid -->
