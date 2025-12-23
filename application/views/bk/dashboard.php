<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard BK</h1>
        <div class="text-muted"><i class="fas fa-calendar-alt"></i> <?= date('F Y') ?></div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Siswa Bermasalah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_bermasalah ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Surat Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $statistics['total_surat'] ?></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-envelope fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Siswa Bermasalah Bulan Ini</h6>
                    <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Lihat Detail</a>
                </div>
                <div class="card-body">
                    <?php if (count($siswa_bermasalah) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Alpha</th><th>Terlambat</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($siswa_bermasalah, 0, 10) as $siswa): ?>
                                <tr>
                                    <td><?= $siswa->nis ?></td>
                                    <td><?= $siswa->nama_lengkap ?></td>
                                    <td><?= $siswa->nama_kelas ?></td>
                                    <td><span class="badge badge-danger"><?= $siswa->total_alpha ?></span></td>
                                    <td><span class="badge badge-warning"><?= $siswa->total_terlambat ?></span></td>
                                    <td>
                                        <a href="<?= base_url('bk/monitoring/detail/'.$siswa->id) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Tidak ada siswa bermasalah bulan ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
