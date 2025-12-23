<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Semester</h1>
            <p class="text-gray-600">Kelola data semester per tahun ajaran</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Tambah Semester
        </button>
    </div>

    <!-- Alert Messages -->
    <?php if($this->session->flashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $this->session->flashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $this->session->flashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
            <table id="semesterTable" class="display w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($semester as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="font-semibold"><?= $s->nama_tahun ?></td>
                        <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold"><?= $s->nama_semester ?></span></td>
                        <td><?= date('d/m/Y', strtotime($s->tanggal_mulai)) ?></td>
                        <td><?= date('d/m/Y', strtotime($s->tanggal_selesai)) ?></td>
                        <td>
                            <?php if($s->is_active): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Aktif</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <?php if(!$s->is_active): ?>
                                <a href="<?= base_url('admin/semester/set_active/'.$s->id) ?>" 
                                   class="text-green-600 hover:text-green-800" 
                                   onclick="return confirm('Aktifkan semester ini?')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                                <?php endif; ?>
                                <button onclick="editData(<?= $s->id ?>)" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteData(<?= $s->id ?>)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="formModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold">Tambah Semester</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="semesterForm">
            <input type="hidden" id="edit_id" name="edit_id">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tahun Ajaran *</label>
                <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach($tahun_ajaran as $ta): ?>
                    <option value="<?= $ta->id ?>"><?= $ta->nama_tahun ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Semester *</label>
                <select id="nama_semester" name="nama_semester" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="">Pilih Semester</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai *</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai *</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                <select id="is_active" name="is_active" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="0">Tidak Aktif</option>
                    <option value="1">Aktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#semesterTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function openAddModal() {
    $('#modalTitle').text('Tambah Semester');
    $('#semesterForm')[0].reset();
    $('#edit_id').val('');
    $('#formModal').removeClass('hidden');
}

function closeModal() {
    $('#formModal').addClass('hidden');
}

function editData(id) {
    $.ajax({
        url: '<?= base_url("admin/semester/get/") ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('#modalTitle').text('Edit Semester');
                $('#edit_id').val(response.data.id);
                $('#tahun_ajaran_id').val(response.data.tahun_ajaran_id);
                $('#nama_semester').val(response.data.nama_semester);
                $('#tanggal_mulai').val(response.data.tanggal_mulai);
                $('#tanggal_selesai').val(response.data.tanggal_selesai);
                $('#is_active').val(response.data.is_active);
                $('#formModal').removeClass('hidden');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

function deleteData(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("admin/semester/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
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

$('#semesterForm').on('submit', function(e) {
    e.preventDefault();
    
    var id = $('#edit_id').val();
    var url = id ? '<?= base_url("admin/semester/edit/") ?>' + id : '<?= base_url("admin/semester/add") ?>';
    
    $.ajax({
        url: url,
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                Swal.fire('Berhasil', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
});
</script>
