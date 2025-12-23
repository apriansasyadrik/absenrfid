<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('bk/dashboard') ?>">Dashboard BK</a></li>
                    <li class="breadcrumb-item active">Monitoring</li>
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
                <h3 class="card-title"><i class="fas fa-filter"></i> Filter</h3>
            </div>
            <form method="get">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kriteria</label>
                                <select name="filter" class="form-control">
                                    <option value="">-- Semua --</option>
                                    <option value="alpha" <?= isset($_GET['filter']) && $_GET['filter'] == 'alpha' ? 'selected' : '' ?>>Alpha ≥3x</option>
                                    <option value="terlambat" <?= isset($_GET['filter']) && $_GET['filter'] == 'terlambat' ? 'selected' : '' ?>>Terlambat ≥5x</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control">
                                    <?php for($i = 1; $i <= 12; $i++): ?>
                                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= (isset($_GET['bulan']) ? $_GET['bulan'] : date('m')) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select name="tahun" class="form-control">
                                    <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                                        <option value="<?= $i ?>" <?= (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y')) == $i ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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

        <!-- Data Table Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Siswa Bermasalah</h3>
                <div class="card-tools">
                    <button onclick="window.print()" class="btn btn-default btn-sm">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="tableMonitoring" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Alpha</th>
                            <th>Total Terlambat</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($student_list as $student): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $student->nis ?></td>
                            <td><?= $student->nama_lengkap ?></td>
                            <td><?= $student->kelas ?></td>
                            <td>
                                <?php if($student->total_alpha >= 3): ?>
                                    <span class="badge badge-danger"><?= $student->total_alpha ?>x</span>
                                <?php else: ?>
                                    <?= $student->total_alpha ?>x
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($student->total_terlambat >= 5): ?>
                                    <span class="badge badge-warning"><?= $student->total_terlambat ?>x</span>
                                <?php else: ?>
                                    <?= $student->total_terlambat ?>x
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($student->total_alpha >= 3 || $student->total_terlambat >= 5): ?>
                                    <span class="badge badge-danger">Perlu Tindakan</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Normal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('bk/monitoring/detail/'.$student->siswa_id) ?>" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('bk/surat/form/'.$student->siswa_id) ?>" class="btn btn-sm btn-success" title="Cetak Surat">
                                    <i class="fas fa-print"></i>
                                </a>
                                <button onclick="addNote(<?= $student->siswa_id ?>, '<?= $student->nama_lengkap ?>')" class="btn btn-sm btn-warning" title="Tambah Catatan">
                                    <i class="fas fa-sticky-note"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- Add Note Modal -->
<div class="modal fade" id="noteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Catatan BK</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= base_url('bk/monitoring/add_note') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="siswa_id" id="siswa_id">
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" class="form-control" id="nama_siswa" readonly>
                    </div>
                    <div class="form-group">
                        <label>Catatan <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Follow Up</label>
                        <input type="date" name="tanggal_followup" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableMonitoring').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[4, 'desc'], [5, 'desc']]
    });
});

function addNote(siswa_id, nama) {
    $('#siswa_id').val(siswa_id);
    $('#nama_siswa').val(nama);
    $('#noteModal').modal('show');
}
</script>

<style>
@media print {
    .content-header, .card-tools, .main-sidebar, .main-header, .main-footer {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .btn {
        display: none !important;
    }
}
</style>
