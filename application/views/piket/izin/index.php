<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Izin KBM</h1>
        <a href="<?= base_url('piket/izin/tambah') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Izin
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Izin KBM</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Filter Tanggal:</label>
                    <input type="date" class="form-control" id="filter_tanggal" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-4">
                    <label>Filter Kelas:</label>
                    <select class="form-control" id="filter_kelas">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelas_list as $kelas): ?>
                            <option value="<?= $kelas['id'] ?>"><?= $kelas['nama_kelas'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label><br>
                    <button class="btn btn-primary" onclick="loadData()"><i class="fas fa-search"></i> Filter</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jenis</th>
                            <th>Jam Izin</th>
                            <th>Jam Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var table;
$(document).ready(function() {
    table = $('#dataTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "<?= base_url('piket/izin/get_data') ?>",
            "type": "POST",
            "data": function(d) {
                d.tanggal = $('#filter_tanggal').val();
                d.kelas_id = $('#filter_kelas').val();
            }
        },
        "columns": [
            { "data": 0 }, { "data": 1 }, { "data": 2 }, { "data": 3 },
            { "data": 4 }, { "data": 5 }, { "data": 6 }, { "data": 7 }, { "data": 8 }
        ],
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" }
    });
});

function loadData() {
    table.ajax.reload();
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('piket/izin/hapus/') ?>' + id;
        }
    });
}
</script>
