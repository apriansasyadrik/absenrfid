<!-- Begin Page Content -->
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Info -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profile</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('bk/profile/update') ?>" method="post">
                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" value="<?= $guru['nip'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $guru['nama'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $guru['email'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" value="<?= $guru['no_hp'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"><?= $guru['alamat'] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('bk/profile/change_password') ?>" method="post">
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                            <small class="form-text text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
