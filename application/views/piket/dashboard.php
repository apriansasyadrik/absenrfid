<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Guru Piket</h1>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_izin_today ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Masuk Terlambat -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Masuk Terlambat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $masuk_terlambat ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keluar Awal -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Keluar Awal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $keluar_awal ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign-out-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tidak Masuk -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Tidak Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tidak_masuk ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Izin Hari Ini -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Izin Hari Ini</h6>
                    <a href="<?= base_url('piket/izin/tambah') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Izin
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($izin_today) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis</th>
                                    <th>Jam Izin</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($izin_today as $izin): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($izin->created_at)) ?></td>
                                    <td><?= $izin->nis ?></td>
                                    <td><?= $izin->nama_lengkap ?></td>
                                    <td><?= $izin->nama_kelas ?></td>
                                    <td>
                                        <?php
                                        $badge = 'badge-secondary';
                                        if ($izin->jenis == 'Masuk Terlambat') $badge = 'badge-warning';
                                        elseif ($izin->jenis == 'Keluar Awal') $badge = 'badge-info';
                                        elseif ($izin->jenis == 'Tidak Masuk') $badge = 'badge-danger';
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= $izin->jenis ?></span>
                                    </td>
                                    <td><?= date('H:i', strtotime($izin->jam_izin)) ?></td>
                                    <td><?= $izin->alasan ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada izin hari ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End of Main Content -->
