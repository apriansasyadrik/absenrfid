<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= isset($izin) ? 'Edit' : 'Tambah' ?> Izin KBM</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('guru/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('piket/izinkbm') ?>">Izin KBM</a></li>
                    <li class="breadcrumb-item active"><?= isset($izin) ? 'Edit' : 'Tambah' ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Izin KBM</h3>
            </div>
            <form action="<?= isset($izin) ? base_url('piket/izinkbm/edit/'.$izin->id) : base_url('piket/izinkbm/add') ?>" method="post">
                <div class="card-body">
                    
                    <div class="form-group">
                        <label>Kelas <span class="text-danger">*</span></label>
                        <select name="kelas_id" id="kelas_id" class="form-control select2" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach($kelas_list as $kelas): ?>
                                <option value="<?= $kelas->id ?>"><?= $kelas->tingkat ?> <?= $kelas->nama_kelas ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-control select2" required disabled>
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        </select>
                        <small class="text-muted">Pilih siswa yang akan diizinkan</small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="<?= isset($izin) ? $izin->tanggal : date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Izin <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis" id="masuk_terlambat" value="masuk_terlambat" <?= isset($izin) && $izin->jenis == 'masuk_terlambat' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="masuk_terlambat">
                                    <span class="badge badge-warning">Masuk Terlambat</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis" id="keluar_awal" value="keluar_awal" <?= isset($izin) && $izin->jenis == 'keluar_awal' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="keluar_awal">
                                    <span class="badge badge-info">Keluar Awal</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis" id="tidak_masuk" value="tidak_masuk" <?= isset($izin) && $izin->jenis == 'tidak_masuk' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="tidak_masuk">
                                    <span class="badge badge-danger">Tidak Masuk</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="waktuWrapper">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Izin <span class="text-danger">*</span></label>
                                <input type="time" name="jam_izin" class="form-control" value="<?= isset($izin) ? $izin->jam_izin : '' ?>" required>
                                <small class="text-muted">Waktu siswa izin/terlambat</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jam Kembali</label>
                                <input type="time" name="jam_kembali" class="form-control" value="<?= isset($izin) ? $izin->jam_kembali : '' ?>">
                                <small class="text-muted">Kosongkan jika tidak masuk</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alasan <span class="text-danger">*</span></label>
                        <textarea name="alasan" class="form-control" rows="4" required><?= isset($izin) ? $izin->alasan : '' ?></textarea>
                        <small class="text-muted">Jelaskan alasan siswa terlambat/keluar awal/tidak masuk</small>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('piket/izinkbm') ?>" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>

    </div>
</section>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Load students when class is selected
    $('#kelas_id').change(function() {
        var kelas_id = $(this).val();
        if (kelas_id) {
            $.ajax({
                url: '<?= base_url("piket/izinkbm/get_siswa") ?>',
                type: 'POST',
                data: { kelas_id: kelas_id },
                dataType: 'json',
                success: function(data) {
                    var options = '<option value="">-- Pilih Siswa --</option>';
                    $.each(data, function(i, siswa) {
                        options += '<option value="' + siswa.id + '">' + siswa.nis + ' - ' + siswa.nama_lengkap + '</option>';
                    });
                    $('#siswa_id').html(options).prop('disabled', false);
                }
            });
        } else {
            $('#siswa_id').html('<option value="">-- Pilih Kelas Terlebih Dahulu --</option>').prop('disabled', true);
        }
    });

    // Hide jam_kembali if tidak_masuk is selected
    $('input[name="jenis"]').change(function() {
        if ($(this).val() == 'tidak_masuk') {
            $('input[name="jam_kembali"]').prop('required', false).closest('.col-md-6').hide();
        } else {
            $('input[name="jam_kembali"]').closest('.col-md-6').show();
        }
    });
});
</script>
