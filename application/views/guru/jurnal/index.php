<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Jurnal Mengajar & Absensi</h1>
        <a href="<?= base_url('guru/jurnal/add') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jurnal
        </a>
    </div>

    <!-- Flash Message -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Tanggal</th>
                            <th width="15%">Mata Pelajaran</th>
                            <th width="15%">Kelas</th>
                            <th width="35%">Materi</th>
                            <th width="10%">Jam</th>
                            <th width="10%">Aksi</th>
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
                                    <td>
                                        <div style="max-height: 50px; overflow: hidden;">
                                            <?= $journal->materi ?>
                                        </div>
                                    </td>
                                    <td><?= $journal->jam_mulai ?> - <?= $journal->jam_selesai ?></td>
                                    <td>
                                        <a href="<?= base_url('guru/jurnal/edit/'.$journal->id) ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteJurnal(<?= $journal->id ?>)" 
                                                class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
        },
        "order": [[1, "desc"]]
    });
});

function deleteJurnal(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data jurnal akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('guru/jurnal/delete/') ?>' + id;
        }
    });
}
</script>
