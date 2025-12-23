<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profile Saya</h1>

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

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="<?= base_url('assets/uploads/foto_guru/'.($guru->foto ?: 'default.png')) ?>" 
                         class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;"
                         onerror="this.src='<?= base_url('assets/uploads/foto_guru/default.png') ?>'">
                    <h5 class="mb-1"><?= $guru->nama_lengkap ?></h5>
                    <p class="text-muted mb-1"><?= $guru->nip ?></p>
                    <p class="text-muted mb-3"><?= $guru->jabatan ?: 'Guru' ?></p>
                    
                    <?php if($guru->rfid_uid): ?>
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> RFID Terdaftar
                        </span>
                    <?php else: ?>
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-circle"></i> RFID Belum Terdaftar
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">No HP</small>
                        <p class="mb-0"><?= $guru->no_hp ?: '-' ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <p class="mb-0"><?= $guru->email ?: '-' ?></p>
                    </div>
                    <div>
                        <small class="text-muted">Alamat</small>
                        <p class="mb-0"><?= $guru->alamat ?: '-' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-lg-8">
            <!-- Edit Profile -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/profile/update') ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" class="form-control" 
                                           value="<?= $guru->nama_lengkap ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" class="form-control" value="<?= $guru->nip ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="no_hp" class="form-control" 
                                           value="<?= $guru->no_hp ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="<?= $guru->email ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?= $guru->alamat ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Foto Profile</label>
                            <input type="file" name="foto" class="form-control-file" accept="image/*">
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('guru/profile/change_password') ?>" method="post">
                        <div class="form-group">
                            <label>Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                            <small class="form-text text-muted">Minimal 6 karakter</small>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="6">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
