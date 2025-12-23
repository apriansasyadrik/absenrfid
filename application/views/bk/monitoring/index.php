<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Monitoring Siswa</h1>

    <!-- Filter -->
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

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Monitoring Siswa</h6>
        </div>
        <div class="card-body">
            <?php if (count($list_monitoring) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Alpha</th>
                                <th>Terlambat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($list_monitoring as $m): ?>
                                <tr class="<?= $m['is_flagged'] ? 'table-warning' : '' ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= $m['nis'] ?></td>
                                    <td><?= $m['nama'] ?></td>
                                    <td><?= $m['nama_kelas'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $m['jumlah_alpha'] >= 5 ? 'danger' : ($m['jumlah_alpha'] >= 3 ? 'warning' : 'secondary') ?>">
                                            <?= $m['jumlah_alpha'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $m['jumlah_terlambat'] >= 10 ? 'danger' : ($m['jumlah_terlambat'] >= 5 ? 'warning' : 'secondary') ?>">
                                            <?= $m['jumlah_terlambat'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($m['is_flagged']): ?>
                                            <span class="badge badge-danger">Perlu Perhatian</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Normal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('bk/monitoring/detail/' . $m['siswa_id']) ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-3">Belum ada data monitoring</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[4, "desc"], [5, "desc"]]
    });
});
</script>
