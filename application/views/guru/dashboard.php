<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <!-- Content Row - Stats -->
    <div class="row">

        <!-- Total Kelas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Kelas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= isset($total_classes) ? $total_classes : 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= isset($today_schedules) ? $today_schedules : 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jurnal Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Jurnal Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= isset($journals_count) ? $journals_count : 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelas Selanjutnya -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Kelas Selanjutnya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php if(isset($next_class) && $next_class): ?>
                                    <?= $next_class->jam_mulai ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row - Schedule Today -->
    <div class="row">

        <!-- Today's Schedule -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jadwal Hari Ini</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($schedules_today) && count($schedules_today) > 0): ?>
                        <?php foreach($schedules_today as $schedule): ?>
                            <div class="mb-3 p-3 border-left border-primary" style="border-left-width: 4px !important;">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong><?= $schedule->jam_mulai ?> - <?= $schedule->jam_selesai ?></strong>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="font-weight-bold"><?= $schedule->nama_mapel ?></div>
                                        <div class="text-muted small">Kelas: <?= $schedule->tingkat ?> <?= $schedule->nama_kelas ?></div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <span class="badge badge-info"><?= $schedule->ruangan ?: 'Belum ada ruangan' ?></span>
                                        <a href="<?= base_url('guru/jurnal/add?jadwal='.$schedule->id.'&tanggal='.date('Y-m-d')) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Isi Jurnal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p>Tidak ada jadwal mengajar hari ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Journals -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jurnal Terbaru</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($recent_journals) && count($recent_journals) > 0): ?>
                        <?php foreach($recent_journals as $journal): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="small text-muted"><?= date('d M Y', strtotime($journal->tanggal)) ?></div>
                                <div class="font-weight-bold"><?= $journal->nama_mapel ?></div>
                                <div class="small text-muted"><?= $journal->tingkat ?> <?= $journal->nama_kelas ?></div>
                            </div>
                        <?php endforeach; ?>
                        <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-sm btn-primary btn-block">
                            Lihat Semua Jurnal
                        </a>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <p class="small">Belum ada jurnal</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->
