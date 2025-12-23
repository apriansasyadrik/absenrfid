<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= isset($journal) ? 'Edit Jurnal' : 'Tambah Jurnal' ?> & Absensi</h1>
        <a href="<?= base_url('guru/jurnal') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="<?= base_url('guru/jurnal/'.(isset($journal) ? 'edit/'.$journal->id : 'add')) ?>" method="post">
        <div class="row">
            <!-- Journal Form -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Jurnal Mengajar</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" 
                                   value="<?= isset($journal) ? $journal->tanggal : date('Y-m-d') ?>" 
                                   required id="tanggal" onchange="loadSchedules()">
                        </div>

                        <div class="form-group">
                            <label>Jadwal Mengajar <span class="text-danger">*</span></label>
                            <select name="jadwal_id" id="jadwal_id" class="form-control" required onchange="loadStudents()">
                                <option value="">-- Pilih Jadwal --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Materi Ajar <span class="text-danger">*</span></label>
                            <textarea name="materi" class="form-control" rows="3" required 
                                      placeholder="Tuliskan materi yang diajarkan..."><?= isset($journal) ? $journal->materi : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Kegiatan Pembelajaran <span class="text-danger">*</span></label>
                            <textarea name="kegiatan" class="form-control" rows="3" required 
                                      placeholder="Deskripsikan kegiatan pembelajaran..."><?= isset($journal) ? $journal->kegiatan : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Hambatan/Kendala</label>
                            <textarea name="hambatan" class="form-control" rows="2" 
                                      placeholder="Tuliskan hambatan jika ada..."><?= isset($journal) ? $journal->hambatan : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Input -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Absensi Siswa (H/S/I/A)</h6>
                    </div>
                    <div class="card-body">
                        <div id="students-list">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Pilih jadwal terlebih dahulu untuk menampilkan daftar siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Simpan Jurnal & Absensi
            </button>
        </div>
    </form>

</div>
<!-- /.container-fluid -->

<script>
$(document).ready(function() {
    <?php if(isset($_GET['jadwal']) && isset($_GET['tanggal'])): ?>
        $('#tanggal').val('<?= $_GET['tanggal'] ?>');
        loadSchedules(<?= $_GET['jadwal'] ?>);
    <?php endif; ?>
});

function loadSchedules(selectedId = null) {
    const tanggal = $('#tanggal').val();
    if (!tanggal) return;

    $.ajax({
        url: '<?= base_url('guru/jurnal/get_schedules') ?>',
        method: 'POST',
        data: { tanggal: tanggal },
        dataType: 'json',
        success: function(data) {
            let options = '<option value="">-- Pilih Jadwal --</option>';
            data.forEach(function(schedule) {
                const selected = (selectedId && schedule.id == selectedId) ? 'selected' : '';
                options += `<option value="${schedule.id}" ${selected}>
                    ${schedule.jam_mulai} - ${schedule.jam_selesai} | ${schedule.nama_mapel} | 
                    ${schedule.tingkat} ${schedule.nama_kelas}
                </option>`;
            });
            $('#jadwal_id').html(options);
            
            if (selectedId) {
                loadStudents();
            }
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat jadwal', 'error');
        }
    });
}

function loadStudents() {
    const jadwalId = $('#jadwal_id').val();
    if (!jadwalId) {
        $('#students-list').html(`
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <p>Pilih jadwal terlebih dahulu</p>
            </div>
        `);
        return;
    }

    $.ajax({
        url: '<?= base_url('guru/jurnal/get_students') ?>',
        method: 'POST',
        data: { jadwal_id: jadwalId },
        dataType: 'json',
        success: function(data) {
            if (data.length === 0) {
                $('#students-list').html('<p class="text-muted">Tidak ada siswa di kelas ini</p>');
                return;
            }

            let html = '<div style="max-height: 500px; overflow-y: auto;">';
            html += '<table class="table table-sm table-bordered">';
            html += '<thead class="thead-light"><tr>';
            html += '<th width="5%">No</th><th>Nama Siswa</th><th width="40%">Status</th>';
            html += '</tr></thead><tbody>';

            data.forEach(function(student, index) {
                html += `<tr>
                    <td>${index + 1}</td>
                    <td>${student.nama_lengkap}</td>
                    <td>
                        <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-success">
                                <input type="radio" name="status_${student.id}" value="H" required> H
                            </label>
                            <label class="btn btn-outline-warning">
                                <input type="radio" name="status_${student.id}" value="S"> S
                            </label>
                            <label class="btn btn-outline-info">
                                <input type="radio" name="status_${student.id}" value="I"> I
                            </label>
                            <label class="btn btn-outline-danger">
                                <input type="radio" name="status_${student.id}" value="A"> A
                            </label>
                        </div>
                    </td>
                </tr>`;
            });

            html += '</tbody></table>';
            html += '<div class="mt-2"><small class="text-muted">';
            html += '<strong>Keterangan:</strong> H = Hadir, S = Sakit, I = Izin, A = Alpha';
            html += '</small></div>';
            html += '</div>';

            $('#students-list').html(html);
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat daftar siswa', 'error');
        }
    });
}
</script>
