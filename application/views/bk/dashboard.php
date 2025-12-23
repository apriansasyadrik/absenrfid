<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard BK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Info boxes -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $total_alpha ?></h3>
                        <p>Siswa Alpha ≥3x</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="<?= base_url('bk/monitoring?filter=alpha') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total_terlambat ?></h3>
                        <p>Siswa Terlambat ≥5x</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="<?= base_url('bk/monitoring?filter=terlambat') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $total_monitoring ?></h3>
                        <p>Total Monitoring</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <a href="<?= base_url('bk/monitoring') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $total_surat ?></h3>
                        <p>Surat Terkirim</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <a href="<?= base_url('bk/surat') ?>" class="small-box-footer">
                        Cetak Surat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Problem Students -->
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title"><i class="fas fa-users"></i> Siswa Bermasalah Terbaru</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Masalah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recent_students)): ?>
                                <?php foreach($recent_students as $student): ?>
                                <tr>
                                    <td><?= $student->nis ?></td>
                                    <td><?= $student->nama_lengkap ?></td>
                                    <td><?= $student->kelas ?></td>
                                    <td>
                                        <?php if($student->total_alpha >= 3): ?>
                                            <span class="badge badge-danger">Alpha: <?= $student->total_alpha ?>x</span>
                                        <?php endif; ?>
                                        <?php if($student->total_terlambat >= 5): ?>
                                            <span class="badge badge-warning">Terlambat: <?= $student->total_terlambat ?>x</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('bk/monitoring/detail/'.$student->id) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <a href="<?= base_url('bk/surat/form/'.$student->id) ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-print"></i> Surat
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-check-circle"></i> Tidak ada siswa bermasalah bulan ini
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-sm btn-primary float-right">
                    <i class="fas fa-list"></i> Lihat Semua
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-block btn-outline-info btn-lg">
                            <i class="fas fa-eye"></i><br>
                            Monitoring Siswa
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('bk/surat') ?>" class="btn btn-block btn-outline-success btn-lg">
                            <i class="fas fa-print"></i><br>
                            Cetak Surat
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= base_url('admin/laporan-siswa') ?>" class="btn btn-block btn-outline-primary btn-lg">
                            <i class="fas fa-chart-bar"></i><br>
                            Laporan Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
