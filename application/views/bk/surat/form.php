<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('bk/dashboard') ?>">Dashboard BK</a></li>
                    <li class="breadcrumb-item active">Cetak Surat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-4">
                <!-- Selection Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pilih Siswa</h3>
                    </div>
                    <form action="<?= base_url('bk/surat/preview') ?>" method="post" target="_blank">
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label>Siswa <span class="text-danger">*</span></label>
                                <select name="siswa_id" id="siswa_id" class="form-control select2" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php foreach($student_list as $student): ?>
                                        <option value="<?= $student->id ?>" <?= isset($siswa) && $siswa->id == $student->id ? 'selected' : '' ?>>
                                            <?= $student->nis ?> - <?= $student->nama_lengkap ?> (<?= $student->kelas ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Jenis Surat <span class="text-danger">*</span></label>
                                <select name="jenis_surat" class="form-control" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="panggilan">Surat Panggilan Orangtua</option>
                                    <option value="peringatan">Surat Peringatan</option>
                                    <option value="pernyataan">Surat Pernyataan</option>
                                </select>
                            </div>

                            <?php if(isset($siswa)): ?>
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Data Siswa:</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="40%">NIS</td>
                                        <td>: <?= $siswa->nis ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: <?= $siswa->nama_lengkap ?></td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td>: <?= $siswa->kelas ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Alpha</td>
                                        <td>: <span class="badge badge-danger"><?= $siswa->total_alpha ?>x</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Terlambat</td>
                                        <td>: <span class="badge badge-warning"><?= $siswa->total_terlambat ?>x</span></td>
                                    </tr>
                                </table>
                            </div>
                            <?php endif; ?>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-print"></i> Preview & Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Template Surat</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> Pilih siswa untuk melihat preview surat.
                        </div>
                        
                        <div class="p-4 border" id="suratPreview">
                            <!-- Letter Preview Will Appear Here -->
                            <div class="text-center mb-4">
                                <h5><strong>SURAT PANGGILAN ORANGTUA/WALI</strong></h5>
                                <p class="mb-0">Nomor: {nomor_surat}</p>
                            </div>

                            <p>Kepada Yth.<br>
                            Orangtua/Wali Siswa<br>
                            <strong>{nama_siswa}</strong><br>
                            Kelas: {kelas}<br>
                            Di tempat</p>

                            <p>Dengan hormat,</p>

                            <p>Sehubungan dengan tingkat kehadiran dan kedisiplinan putra/putri Bapak/Ibu, 
                            kami mengharapkan kehadiran Bapak/Ibu untuk bertemu dengan Guru BK (Bimbingan Konseling) 
                            di sekolah untuk membahas:</p>

                            <ul>
                                <li>Ketidakhadiran (Alpha): <strong>{total_alpha}x</strong></li>
                                <li>Keterlambatan: <strong>{total_terlambat}x</strong></li>
                            </ul>

                            <p>Pertemuan diharapkan pada:</p>
                            <table class="ml-4">
                                <tr>
                                    <td width="100">Hari/Tanggal</td>
                                    <td>: {tanggal_panggilan}</td>
                                </tr>
                                <tr>
                                    <td>Waktu</td>
                                    <td>: {waktu_panggilan}</td>
                                </tr>
                                <tr>
                                    <td>Tempat</td>
                                    <td>: Ruang BK {nama_sekolah}</td>
                                </tr>
                            </table>

                            <p>Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>

                            <div class="text-right mt-5">
                                <p>Hormat kami,<br>
                                Guru BK</p>
                                <br><br><br>
                                <p><strong>({nama_guru_bk})</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: '-- Pilih Siswa --'
    });

    // Load student data on selection
    $('#siswa_id').change(function() {
        var siswa_id = $(this).val();
        if (siswa_id) {
            window.location.href = '<?= base_url("bk/surat/form/") ?>' + siswa_id;
        }
    });
});
</script>
