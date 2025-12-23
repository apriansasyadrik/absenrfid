<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Monitoring Siswa Bermasalah</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Filter Data</h6></div>
        <div class="card-body">
            <form method="get">
                <div class="row">
                    <div class="col-md-3">
                        <label>Bulan:</label>
                        <select class="form-control" name="bulan">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$i,1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tahun:</label>
                        <input type="number" class="form-control" name="tahun" value="<?= $tahun ?>" min="2020">
                    </div>
                    <div class="col-md-4">
                        <label>Kelas:</label>
                        <select class="form-control" name="kelas_id">
                            <option value="">Semua Kelas</option>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <option value="<?= $kelas['id'] ?>" <?= $kelas_id == $kelas['id'] ? 'selected' : '' ?>>
                                    <?= $kelas['nama_kelas'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Data Siswa Bermasalah</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Total Alpha</th><th>Total Terlambat</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswa_bermasalah as $siswa): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $siswa->nis ?></td>
                            <td><?= $siswa->nama_lengkap ?></td>
                            <td><?= $siswa->nama_kelas ?></td>
                            <td><span class="badge badge-danger"><?= $siswa->total_alpha ?></span></td>
                            <td><span class="badge badge-warning"><?= $siswa->total_terlambat ?></span></td>
                            <td>
                                <a href="<?= base_url('bk/monitoring/detail/'.$siswa->id.'?bulan='.$bulan.'&tahun='.$tahun) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$('#dataTable').DataTable({"language": {"url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"}});
</script>
