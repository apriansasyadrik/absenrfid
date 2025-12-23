<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rekap Izin KBM</h1>
        <a href="<?= base_url('piket/rekap/export?' . http_build_query($_GET)) ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Dari Tanggal:</label>
                <input type="date" name="start_date" class="form-control mr-2" value="<?= $start_date ?>">
                
                <label class="mr-2">Sampai Tanggal:</label>
                <input type="date" name="end_date" class="form-control mr-2" value="<?= $end_date ?>">
                
                <label class="mr-2 ml-3">Kelas:</label>
                <select name="kelas_id" class="form-control mr-2">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <option value="<?= $kelas['id'] ?>" <?= ($kelas_id == $kelas['id']) ? 'selected' : '' ?>>
                            <?= $kelas['tingkat'] ?> <?= $kelas['nama_kelas'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('piket/rekap') ?>" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <?php if (count($stats) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Periode <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $stats_arr = array();
                        foreach ($stats as $stat) {
                            $stats_arr[$stat['jenis']] = $stat['jumlah'];
                        }
                        ?>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-warning"><?= isset($stats_arr['masuk_terlambat']) ? $stats_arr['masuk_terlambat'] : 0 ?></h4>
                                <p class="text-muted">Masuk Terlambat</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-info"><?= isset($stats_arr['keluar_awal']) ? $stats_arr['keluar_awal'] : 0 ?></h4>
                                <p class="text-muted">Keluar Awal</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-danger"><?= isset($stats_arr['tidak_masuk']) ? $stats_arr['tidak_masuk'] : 0 ?></h4>
                                <p class="text-muted">Tidak Masuk</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Izin KBM</h6>
        </div>
        <div class="card-body">
            <?php if (count($list_izin) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="8%">NIS</th>
                                <th width="17%">Nama Siswa</th>
                                <th width="10%">Kelas</th>
                                <th width="12%">Jenis</th>
                                <th width="10%">Jam</th>
                                <th width="18%">Alasan</th>
                                <th width="10%">Guru Piket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($list_izin as $izin): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($izin['tanggal'])) ?></td>
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
                                    <td>
                                        <?= $izin['jam_izin'] ?>
                                        <?= $izin['jam_kembali'] ? '<br><small>Kembali: ' . $izin['jam_kembali'] . '</small>' : '' ?>
                                    </td>
                                    <td><?= $izin['alasan'] ?></td>
                                    <td><small><?= $izin['nama_guru'] ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-3">Belum ada data izin pada periode ini</p>
            <?php endif; ?>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, "desc"]]
    });
});
</script>
