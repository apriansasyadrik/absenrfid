<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rekap Izin KBM</h1>
        <a href="<?= base_url('piket/rekap/export?bulan='.$bulan.'&tahun='.$tahun.($kelas_id ? '&kelas_id='.$kelas_id : '')) ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Filter Rekap</h6></div>
        <div class="card-body">
            <form method="get">
                <div class="row">
                    <div class="col-md-4">
                        <label>Bulan:</label>
                        <select class="form-control" name="bulan">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Tahun:</label>
                        <input type="number" class="form-control" name="tahun" value="<?= $tahun ?>" min="2020" max="2100">
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
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Data Rekap</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th><th>Tanggal</th><th>NIS</th><th>Nama</th>
                            <th>Kelas</th><th>Jenis</th><th>Jam Izin</th><th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($rekap_list as $izin): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($izin->tanggal)) ?></td>
                            <td><?= $izin->nis ?></td>
                            <td><?= $izin->nama_lengkap ?></td>
                            <td><?= $izin->nama_kelas ?></td>
                            <td><?= $izin->jenis ?></td>
                            <td><?= date('H:i', strtotime($izin->jam_izin)) ?></td>
                            <td><?= $izin->alasan ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
