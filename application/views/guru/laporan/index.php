<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Laporan Kinerja Mengajar</h1>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Bulan:</label>
                <select name="bulan" class="form-control mr-3">
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?= $i ?>" <?= (isset($_GET['bulan']) && $_GET['bulan'] == $i) ? 'selected' : (date('n') == $i ? 'selected' : '') ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <label class="mr-2">Tahun:</label>
                <select name="tahun" class="form-control mr-3">
                    <?php for($y=date('Y')-2; $y<=date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $y) ? 'selected' : (date('Y') == $y ? 'selected' : '') ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Jurnal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= isset($stats['total_jurnal']) ? $stats['total_jurnal'] : 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jam Mengajar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= isset($stats['total_jam']) ? $stats['total_jam'] : 0 ?> Jam
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kelas Diampu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= isset($stats['total_kelas']) ? $stats['total_kelas'] : 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Journal List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Jurnal Mengajar</h6>
            <a href="<?= base_url('guru/laporan/export?bulan='.(isset($_GET['bulan'])?$_GET['bulan']:date('n')).'&tahun='.(isset($_GET['tahun'])?$_GET['tahun']:date('Y'))) ?>" 
               class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam</th>
                            <th>Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($journals) && count($journals) > 0): ?>
                            <?php $no = 1; foreach($journals as $journal): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($journal->tanggal)) ?></td>
                                    <td><?= $journal->nama_mapel ?></td>
                                    <td><?= $journal->tingkat ?> <?= $journal->nama_kelas ?></td>
                                    <td><?= $journal->jam_mulai ?> - <?= $journal->jam_selesai ?></td>
                                    <td><?= substr($journal->materi, 0, 100) ?>...</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
