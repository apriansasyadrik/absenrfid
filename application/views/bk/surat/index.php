<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Surat Panggilan Orang Tua</h1>
        <a href="<?= base_url('bk/surat/tambah') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Surat</a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success') ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Surat</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr><th>No</th><th>No. Surat</th><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Perihal</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($surat_list as $surat): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $surat->nomor_surat ?></td>
                            <td><?= date('d/m/Y', strtotime($surat->tanggal_surat)) ?></td>
                            <td><?= $surat->nama_lengkap ?></td>
                            <td><?= $surat->nama_kelas ?></td>
                            <td><?= substr($surat->perihal, 0, 50) ?>...</td>
                            <td><span class="badge badge-secondary"><?= $surat->status ?></span></td>
                            <td>
                                <a href="<?= base_url('bk/surat/cetak/'.$surat->id) ?>" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-print"></i></a>
                                <a href="<?= base_url('bk/surat/edit/'.$surat->id) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <button onclick="confirmDelete(<?= $surat->id ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Surat?', text: "Data tidak dapat dikembalikan!", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = '<?= base_url('bk/surat/hapus/') ?>' + id;
    });
}
</script>
