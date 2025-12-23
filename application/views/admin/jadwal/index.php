<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran</h1>
            <p class="text-gray-600 text-sm mt-1">Kelola jadwal pelajaran mingguan</p>
        </div>
        <div class="flex gap-2">
            <button onclick="showAddModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </button>
            <a href="<?php echo base_url('admin/jadwal/export' . ($semester_id ? '?semester_id='.$semester_id : '') . ($kelas_id ? '&kelas_id='.$kelas_id : '') . ($hari ? '&hari='.$hari : '')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="get" action="<?php echo base_url('admin/jadwal'); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Semua Semester</option>
                    <?php foreach($semesters as $sem): ?>
                        <option value="<?php echo $sem->id; ?>" <?php echo ($semester_id == $sem->id) ? 'selected' : ''; ?>>
                            <?php echo $sem->nama; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Semua Kelas</option>
                    <?php foreach($kelas_list as $kls): ?>
                        <option value="<?php echo $kls->id; ?>" <?php echo ($kelas_id == $kls->id) ? 'selected' : ''; ?>>
                            <?php echo $kls->nama; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                <select name="hari" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Semua Hari</option>
                    <option value="Senin" <?php echo ($hari == 'Senin') ? 'selected' : ''; ?>>Senin</option>
                    <option value="Selasa" <?php echo ($hari == 'Selasa') ? 'selected' : ''; ?>>Selasa</option>
                    <option value="Rabu" <?php echo ($hari == 'Rabu') ? 'selected' : ''; ?>>Rabu</option>
                    <option value="Kamis" <?php echo ($hari == 'Kamis') ? 'selected' : ''; ?>>Kamis</option>
                    <option value="Jumat" <?php echo ($hari == 'Jumat') ? 'selected' : ''; ?>>Jumat</option>
                    <option value="Sabtu" <?php echo ($hari == 'Sabtu') ? 'selected' : ''; ?>>Sabtu</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table id="jadwalTable" class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($jadwal)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data jadwal</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($jadwal as $item): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm"><?php echo $item->semester_nama; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $item->kelas_nama; ?></td>
                            <td class="px-6 py-4 text-sm">
                                <?php 
                                $hari_colors = [
                                    'Senin' => 'bg-blue-100 text-blue-800',
                                    'Selasa' => 'bg-green-100 text-green-800',
                                    'Rabu' => 'bg-yellow-100 text-yellow-800',
                                    'Kamis' => 'bg-purple-100 text-purple-800',
                                    'Jumat' => 'bg-pink-100 text-pink-800',
                                    'Sabtu' => 'bg-indigo-100 text-indigo-800'
                                ];
                                $color = isset($hari_colors[$item->hari]) ? $hari_colors[$item->hari] : 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $color; ?>">
                                    <?php echo $item->hari; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php echo substr($item->jam_mulai, 0, 5); ?> - <?php echo substr($item->jam_selesai, 0, 5); ?>
                            </td>
                            <td class="px-6 py-4 text-sm"><?php echo $item->mapel_nama; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $item->guru_nama; ?></td>
                            <td class="px-6 py-4 text-sm"><?php echo $item->ruangan ?: '-'; ?></td>
                            <td class="px-6 py-4 text-sm">
                                <button onclick="editJadwal(<?php echo $item->id; ?>)" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteJadwal(<?php echo $item->id; ?>)" class="text-red-600 hover:text-red-800">
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

<!-- Add/Edit Modal -->
<div id="jadwalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah Jadwal</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="jadwalForm">
            <input type="hidden" id="jadwal_id" name="jadwal_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                    <select id="semester_id" name="semester_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Semester</option>
                        <?php foreach($semesters as $sem): ?>
                            <option value="<?php echo $sem->id; ?>"><?php echo $sem->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select id="kelas_id" name="kelas_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Kelas</option>
                        <?php foreach($kelas_list as $kls): ?>
                            <option value="<?php echo $kls->id; ?>"><?php echo $kls->nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="mapel_id" name="mapel_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Mata Pelajaran</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guru <span class="text-red-500">*</span></label>
                    <select id="guru_id" name="guru_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Guru</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hari <span class="text-red-500">*</span></label>
                    <select id="hari" name="hari" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                <input type="text" id="ruangan" name="ruangan" placeholder="Contoh: Lab Komputer, Ruang 101" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#jadwalTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[2, 'asc'], [3, 'asc']]
    });

    // Load mata pelajaran and guru
    loadMapel();
    loadGuru();
});

function loadMapel() {
    $.ajax({
        url: '<?php echo base_url("admin/mapel"); ?>',
        method: 'GET',
        success: function() {
            // Load mapel options from database
            $.getJSON('<?php echo base_url("admin/mapel/get_all_json"); ?>', function(data) {
                let options = '<option value="">Pilih Mata Pelajaran</option>';
                data.forEach(function(item) {
                    options += `<option value="${item.id}" data-guru="${item.guru_id || ''}">${item.nama_mapel}</option>`;
                });
                $('#mapel_id').html(options);
            }).fail(function() {
                // Fallback: use PHP data
                $('#mapel_id').html('<option value="">Pilih Mata Pelajaran</option>');
            });
        }
    });
}

function loadGuru() {
    $.ajax({
        url: '<?php echo base_url("admin/guru"); ?>',
        method: 'GET',
        success: function() {
            // Load guru options from database
            $.getJSON('<?php echo base_url("admin/guru/get_all_json"); ?>', function(data) {
                let options = '<option value="">Pilih Guru</option>';
                data.forEach(function(item) {
                    options += `<option value="${item.id}">${item.nama}</option>`;
                });
                $('#guru_id').html(options);
            }).fail(function() {
                // Fallback: use PHP data
                $('#guru_id').html('<option value="">Pilih Guru</option>');
            });
        }
    });
}

// Auto select guru when mapel selected
$('#mapel_id').on('change', function() {
    const guruId = $(this).find(':selected').data('guru');
    if (guruId) {
        $('#guru_id').val(guruId);
    }
});

function showAddModal() {
    $('#modalTitle').text('Tambah Jadwal');
    $('#jadwalForm')[0].reset();
    $('#jadwal_id').val('');
    $('#jadwalModal').removeClass('hidden');
}

function closeModal() {
    $('#jadwalModal').addClass('hidden');
    $('#jadwalForm')[0].reset();
}

$('#jadwalForm').on('submit', function(e) {
    e.preventDefault();
    
    const id = $('#jadwal_id').val();
    const url = id ? '<?php echo base_url("admin/jadwal/edit/"); ?>' + id : '<?php echo base_url("admin/jadwal/add"); ?>';
    
    // Validate time
    const jamMulai = $('#jam_mulai').val();
    const jamSelesai = $('#jam_selesai').val();
    
    if (jamSelesai <= jamMulai) {
        Swal.fire('Error', 'Jam selesai harus lebih besar dari jam mulai', 'error');
        return;
    }
    
    $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
    
    $.ajax({
        url: url,
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire('Berhasil', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan');
            }
        },
        error: function() {
            Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan');
        }
    });
});

function editJadwal(id) {
    $.ajax({
        url: '<?php echo base_url("admin/jadwal/get/"); ?>' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const data = response.data;
                $('#modalTitle').text('Edit Jadwal');
                $('#jadwal_id').val(data.id);
                $('#semester_id').val(data.semester_id);
                $('#kelas_id').val(data.kelas_id);
                $('#mapel_id').val(data.mapel_id);
                $('#guru_id').val(data.guru_id);
                $('#hari').val(data.hari);
                $('#jam_mulai').val(data.jam_mulai);
                $('#jam_selesai').val(data.jam_selesai);
                $('#ruangan').val(data.ruangan);
                $('#jadwalModal').removeClass('hidden');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

function deleteJadwal(id) {
    Swal.fire({
        title: 'Hapus Jadwal?',
        text: 'Data jadwal akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url("admin/jadwal/delete/"); ?>' + id,
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>
