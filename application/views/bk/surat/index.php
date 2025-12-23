<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Surat Panggilan</h1>
        <a href="<?= base_url('bk/surat/tambah') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Surat
        </a>
    </div>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Bulan:</label>
                <select name="bulan" class="form-control mr-2">
                    <option value="">Semua</option>
                    <?php 
                    $bulan_nama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    for ($i = 1; $i <= 12; $i++): 
                    ?>
                        <option value="<?= $i ?>" <?= ($this->input->get('bulan') == $i) ? 'selected' : '' ?>>
                            <?= $bulan_nama[$i-1] ?>
                        </option>
                    <?php endfor; ?>
                </select>
                
                <label class="mr-2">Tahun:</label>
                <select name="tahun" class="form-control mr-2">
                    <option value="">Semua</option>
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= ($this->input->get('tahun') == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('bk/surat') ?>" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Panggilan</h6>
        </div>
        <div class="card-body">
            <?php if (count($list_surat) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor Surat</th>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Perihal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($list_surat as $surat): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $surat['nomor_surat'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($surat['tanggal'])) ?></td>
                                    <td><?= $surat['nama_siswa'] ?></td>
                                    <td><?= $surat['nama_kelas'] ?></td>
                                    <td><?= substr($surat['perihal'], 0, 50) ?>...</td>
                                    <td>
                                        <a href="<?= base_url('bk/surat/cetak/' . $surat['id']) ?>" 
                                           target="_blank" class="btn btn-sm btn-primary" title="Cetak">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="<?= base_url('bk/surat/edit/' . $surat['id']) ?>" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" 
                                           data-id="<?= $surat['id'] ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-3">Belum ada surat panggilan</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Hapus Surat?',
            text: 'Yakin ingin menghapus surat ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('bk/surat/hapus/') ?>' + id;
            }
        });
    });
});
</script>
