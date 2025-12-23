<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Sakit/Izin Siswa</h1>
        <a href="<?= base_url('walikelas/izin-siswa/tambah') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Izin
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($kelas_wali): ?>
        <!-- Kelas Info -->
        <div class="alert alert-info">
            <i class="fas fa-users"></i> 
            <strong>Kelas:</strong> <?= $kelas_wali['tingkat'] ?> <?= $kelas_wali['nama_kelas'] ?>
        </div>

        <!-- Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="get" class="form-inline">
                    <label class="mr-2">Filter Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control mr-2" value="<?= $this->input->get('tanggal') ?>">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="<?= base_url('walikelas/izin-siswa') ?>" class="btn btn-secondary">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Izin Siswa</h6>
            </div>
            <div class="card-body">
                <?php if (count($list_izin) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="10%">NIS</th>
                                    <th width="23%">Nama Siswa</th>
                                    <th width="10%">Jenis</th>
                                    <th width="25%">Keterangan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($list_izin as $izin): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d/m/Y', strtotime($izin['tanggal'])) ?></td>
                                        <td><?= $izin['nis'] ?></td>
                                        <td><?= $izin['nama_siswa'] ?></td>
                                        <td>
                                            <?php if ($izin['jenis'] == 'Sakit'): ?>
                                                <span class="badge badge-warning">Sakit</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Izin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $izin['keterangan'] ?></td>
                                        <td>
                                            <a href="<?= base_url('walikelas/izin-siswa/edit/' . $izin['id']) ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" 
                                               class="btn btn-sm btn-danger btn-delete" 
                                               data-id="<?= $izin['id'] ?>"
                                               data-nama="<?= $izin['nama_siswa'] ?>"
                                               title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">Belum ada data izin</p>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <!-- No Kelas Assigned -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5>Anda Belum Ditugaskan Sebagai Wali Kelas</h5>
                <p class="text-muted">Silakan hubungi admin untuk mendapatkan penugasan wali kelas.</p>
            </div>
        </div>
    <?php endif; ?>

</div>
<!-- /.container-fluid -->

<!-- Delete Confirmation Script -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Delete confirmation
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        
        Swal.fire({
            title: 'Hapus Izin?',
            text: 'Yakin ingin menghapus izin siswa ' + nama + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('walikelas/izin-siswa/hapus/') ?>' + id;
            }
        });
    });
});
</script>
