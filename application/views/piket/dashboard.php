<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Guru Piket</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <!-- Content Row - Stats -->
    <div class="row">

        <!-- Total Izin Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Izin Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_izin_hari_ini ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Izin Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Izin Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $izin_bulan_ini ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jadwal Mengajar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $schedules_today ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Stats by Type -->
    <?php if (!empty($stats_today)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Izin Hari Ini</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-warning"><?= isset($stats_today['masuk_terlambat']) ? $stats_today['masuk_terlambat'] : 0 ?></h5>
                                <p class="text-muted">Masuk Terlambat</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-info"><?= isset($stats_today['keluar_awal']) ? $stats_today['keluar_awal'] : 0 ?></h5>
                                <p class="text-muted">Keluar Awal</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5 class="text-danger"><?= isset($stats_today['tidak_masuk']) ? $stats_today['tidak_masuk'] : 0 ?></h5>
                                <p class="text-muted">Tidak Masuk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Izin Hari Ini -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Izin Siswa Hari Ini</h6>
            <a href="<?= base_url('piket/izin-kbm/tambah') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Input Izin
            </a>
        </div>
        <div class="card-body">
            <?php if (count($izin_hari_ini) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Jam</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($izin_hari_ini as $izin): ?>
                                <tr>
                                    <td><?= $izin['jam_izin'] ?><?= $izin['jam_kembali'] ? ' - ' . $izin['jam_kembali'] : '' ?></td>
                                    <td><?= $izin['nis'] ?></td>
                                    <td><?= $izin['nama_siswa'] ?></td>
                                    <td><?= $izin['nama_kelas'] ?></td>
                                    <td>
                                        <?php if ($izin['jenis'] == 'masuk_terlambat'): ?>
                                            <span class="badge badge-warning">Masuk Terlambat</span>
                                        <?php elseif ($izin['jenis'] == 'keluar_awal'): ?>
                                            <span class="badge badge-info">Keluar Awal</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Masuk</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $izin['alasan'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-3">Belum ada izin hari ini</p>
            <?php endif; ?>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
