<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rekap Izin</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Filter Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i> Filter Rekap</h3>
            </div>
            <form method="get">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="<?= isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : date('Y-m-01') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="<?= isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kelas</label>
                                <select name="kelas_id" class="form-control">
                                    <option value="">-- Semua Kelas --</option>
                                    <?php foreach($kelas_list as $kelas): ?>
                                        <option value="<?= $kelas->id ?>" <?= isset($_GET['kelas_id']) && $_GET['kelas_id'] == $kelas->id ? 'selected' : '' ?>>
                                            <?= $kelas->tingkat ?> <?= $kelas->nama_kelas ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $stats['masuk_terlambat'] ?></h3>
                        <p>Masuk Terlambat</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $stats['keluar_awal'] ?></h3>
                        <p>Keluar Awal</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $stats['tidak_masuk'] ?></h3>
                        <p>Tidak Masuk</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $stats['total'] ?></h3>
                        <p>Total Izin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Rekap Izin</h3>
                <div class="card-tools">
                    <button onclick="window.print()" class="btn btn-default btn-sm">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="<?= base_url('piket/rekap/export?'.http_build_query($_GET)) ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableRekap" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Izin</th>
                            <th>Jam Izin</th>
                            <th>Jam Kembali</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($rekap_list as $izin): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($izin->tanggal)) ?></td>
                            <td><?= $izin->nis ?></td>
                            <td><?= $izin->nama_lengkap ?></td>
                            <td><?= $izin->tingkat ?> <?= $izin->nama_kelas ?></td>
                            <td>
                                <?php if($izin->jenis == 'masuk_terlambat'): ?>
                                    <span class="badge badge-warning">Masuk Terlambat</span>
                                <?php elseif($izin->jenis == 'keluar_awal'): ?>
                                    <span class="badge badge-info">Keluar Awal</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak Masuk</span>
                                <?php endif; ?>
                            </td>
                            <td><?= isset($izin->jam_izin) ? date('H:i', strtotime($izin->jam_izin)) : '-' ?></td>
                            <td><?= isset($izin->jam_kembali) ? date('H:i', strtotime($izin->jam_kembali)) : '-' ?></td>
                            <td><?= $izin->alasan ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<script>
$(document).ready(function() {
    $('#tableRekap').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, 'desc']]
    });
});
</script>

<style>
@media print {
    .content-header, .card-tools, .main-sidebar, .main-header, .main-footer {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>
