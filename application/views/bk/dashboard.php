<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard BK</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Bulan:</label>
                <select name="bulan" class="form-control mr-2">
                    <?php 
                    $bulan_nama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    for ($i = 1; $i <= 12; $i++): 
                    ?>
                        <option value="<?= $i ?>" <?= ($bulan == $i) ? 'selected' : '' ?>>
                            <?= $bulan_nama[$i-1] ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <label class="mr-2">Tahun:</label>
                <select name="tahun" class="form-control mr-2">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Content Row - Stats -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Siswa Bermasalah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['total_bermasalah'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alpha Tinggi (≥5)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['alpha_tinggi'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Terlambat Tinggi (≥10)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['terlambat_tinggi'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Surat Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['surat_bulan_ini'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Alert -->
    <?php if ($stats['total_bermasalah'] > 0): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>Perhatian!</strong> Ada <?= $stats['total_bermasalah'] ?> siswa yang memerlukan perhatian BK bulan ini.
        <a href="<?= base_url('bk/monitoring') ?>" class="alert-link">Lihat detail monitoring</a>
    </div>
    <?php endif; ?>

    <!-- Top 10 Siswa Bermasalah -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Top 10 Siswa Bermasalah</h6>
            <a href="<?= base_url('bk/monitoring') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-list"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <?php if (count($siswa_bermasalah) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIS</th>
                                <th width="25%">Nama</th>
                                <th width="15%">Kelas</th>
                                <th width="10%">Alpha</th>
                                <th width="10%">Terlambat</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($siswa_bermasalah as $siswa): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $siswa['nis'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>
                                    <td><?= $siswa['nama_kelas'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $siswa['jumlah_alpha'] >= 5 ? 'danger' : 'warning' ?>">
                                            <?= $siswa['jumlah_alpha'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $siswa['jumlah_terlambat'] >= 10 ? 'danger' : 'info' ?>">
                                            <?= $siswa['jumlah_terlambat'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('bk/monitoring/detail/' . $siswa['siswa_id']) ?>" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h5>Tidak Ada Siswa Bermasalah</h5>
                    <p class="text-muted">Semua siswa memiliki kehadiran yang baik bulan ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
