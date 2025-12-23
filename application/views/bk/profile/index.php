<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profile</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="<?= base_url('assets/uploads/foto_guru/'.($guru['foto'] ?: 'default.png')) ?>" 
                         class="img-fluid rounded-circle mb-3" style="max-width: 200px;"
                         onerror="this.src='<?= base_url('assets/uploads/foto_guru/default.png') ?>'">
                    <h4><?= $guru['nama'] ?></h4>
                    <p class="text-muted">NIP: <?= $guru['nip'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Profile</h6></div>
                <div class="card-body">
                    <table class="table">
                        <tr><td width="30%"><strong>Nama</strong></td><td><?= $guru['nama'] ?></td></tr>
                        <tr><td><strong>NIP</strong></td><td><?= $guru['nip'] ?></td></tr>
                        <tr><td><strong>Email</strong></td><td><?= $guru['email'] ?></td></tr>
                        <tr><td><strong>No. HP</strong></td><td><?= $guru['no_hp'] ?></td></tr>
                        <tr><td><strong>Alamat</strong></td><td><?= $guru['alamat'] ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
