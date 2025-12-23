<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Walikelas</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Kelas Walikelas Info -->
    <?php if ($kelas_walikelas): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Kelas yang Diwalikan
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                <?= $kelas_walikelas->tingkat ?> <?= $kelas_walikelas->nama_kelas ?>
                            </div>
                            <div class="text-muted">Tahun Ajaran: <?= $kelas_walikelas->tahun_ajaran ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Stats -->
    <div class="row">
        <!-- Total Siswa -->
        <div class="col-xl-4 col-md-6 mb-4">
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
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Izin Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_izin_bulan_ini ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jadwal Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($today_schedule) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Jadwal Hari Ini -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jadwal Hari Ini</h6>
                </div>
                <div class="card-body">
                    <?php if (count($today_schedule) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($today_schedule as $schedule): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($schedule->jam_mulai)) ?> - <?= date('H:i', strtotime($schedule->jam_selesai)) ?></td>
                                    <td><?= $schedule->mapel_nama ?></td>
                                    <td><?= $schedule->guru_nama ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Tidak ada jadwal untuk hari ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Data Izin Terbaru -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Data Izin Terbaru</h6>
                    <a href="<?= base_url('walikelas/izin') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($recent_izin) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_izin as $izin): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($izin->tanggal)) ?></td>
                                    <td><?= $izin->nama_lengkap ?></td>
                                    <td>
                                        <?php if ($izin->jenis == 'Sakit'): ?>
                                            <span class="badge badge-warning">Sakit</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">Izin</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada data izin</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                Anda belum memiliki kelas yang diwalikan. Silakan hubungi administrator.
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<!-- End of Main Content -->
