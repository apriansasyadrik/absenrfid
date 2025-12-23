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
                    <li class="breadcrumb-item active">Izin Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Kelas Info Card -->
        <div class="card">
            <div class="card-body">
                <h5><i class="fas fa-chalkboard"></i> Kelas Bimbingan: <strong><?= $kelas->tingkat ?> <?= $kelas->nama_kelas ?></strong></h5>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Izin Siswa</h3>
                <div class="card-tools">
                    <a href="<?= base_url('walikelas/izinsiswa/add') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Izin
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableIzin" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jenis</th>
                            <th>Alasan</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($izin_list as $izin): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $izin->nis ?></td>
                            <td><?= $izin->nama_lengkap ?></td>
                            <td><?= date('d/m/Y', strtotime($izin->tanggal_mulai)) ?></td>
                            <td><?= date('d/m/Y', strtotime($izin->tanggal_selesai)) ?></td>
                            <td>
                                <?php if($izin->jenis == 'sakit'): ?>
                                    <span class="badge badge-warning">Sakit</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Izin</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $izin->alasan ?></td>
                            <td>
                                <a href="<?= base_url('walikelas/izinsiswa/edit/'.$izin->id) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteIzin(<?= $izin->id ?>)" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
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

<script>
$(document).ready(function() {
    $('#tableIzin').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function deleteIzin(id) {
    Swal.fire({
        title: 'Hapus Izin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("walikelas/izinsiswa/delete/") ?>' + id;
        }
    });
}
</script>
