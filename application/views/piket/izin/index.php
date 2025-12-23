<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Izin KBM Siswa</h1>
        <a href="<?= base_url('piket/izin-kbm/tambah') ?>" class="btn btn-primary">
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

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Tanggal:</label>
                <input type="date" name="tanggal" class="form-control mr-2" value="<?= $tanggal ?>">
                
                <label class="mr-2 ml-3">Kelas:</label>
                <select name="kelas_id" class="form-control mr-2">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas_list as $kelas): ?>
                        <option value="<?= $kelas['id'] ?>" <?= ($this->input->get('kelas_id') == $kelas['id']) ? 'selected' : '' ?>>
                            <?= $kelas['tingkat'] ?> <?= $kelas['nama_kelas'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="<?= base_url('piket/izin-kbm') ?>" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Izin KBM</h6>
        </div>
        <div class="card-body">
            <?php if (count($list_izin) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="8%">NIS</th>
                                <th width="17%">Nama Siswa</th>
                                <th width="10%">Kelas</th>
                                <th width="12%">Jenis</th>
                                <th width="10%">Jam</th>
                                <th width="18%">Alasan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($list_izin as $izin): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($izin['tanggal'])) ?></td>
                                    <td><?= $izin['nis'] ?></td>
                                    <td><?= $izin['nama_siswa'] ?></td>
                                    <td><?= $izin['nama_kelas'] ?></td>
                                    <td>
                                        <?php if ($izin['jenis'] == 'masuk_terlambat'): ?>
                                            <span class="badge badge-warning">Masuk Terlambat</span>
                                        <?php elseif ($izin['jenis'] == 'keluar_awal'): ?>
                                            <span class="badge badge-info">Keluar Awal</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Masuk</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $izin['jam_izin'] ?>
                                        <?= $izin['jam_kembali'] ? '<br><small>Kembali: ' . $izin['jam_kembali'] . '</small>' : '' ?>
                                    </td>
                                    <td><?= $izin['alasan'] ?></td>
                                    <td>
                                        <a href="<?= base_url('piket/izin-kbm/edit/' . $izin['id']) ?>" 
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

</div>
<!-- /.container-fluid -->

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
                window.location.href = '<?= base_url('piket/izin-kbm/hapus/') ?>' + id;
            }
        });
    });
});
</script>
