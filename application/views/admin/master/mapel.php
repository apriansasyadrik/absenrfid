<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Mata Pelajaran</h1>
            <p class="text-gray-600">Kelola data mata pelajaran dan guru pengampu</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('admin/mapel/export') ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
            <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Tambah Mata Pelajaran
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4">
            <table id="mapelTable" class="display w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                        <th>KKM</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($mapel as $m): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-mono text-sm"><?= $m->kode_mapel ?></span></td>
                        <td class="font-semibold"><?= $m->nama_mapel ?></td>
                        <td>
                            <?php if($m->nama_guru): ?>
                                <span class="text-gray-700"><?= $m->nama_guru ?></span>
                            <?php else: ?>
                                <span class="text-gray-400 italic">Belum ada guru</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                <?= $m->kkm ?>
                            </span>
                        </td>
                        <td class="text-gray-600 text-sm"><?= $m->deskripsi ?: '-' ?></td>
                        <td>
                            <div class="flex gap-2">
                                <button onclick="editData(<?= $m->id ?>)" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteData(<?= $m->id ?>)" class="text-red-600 hover:text-red-800">
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
    <div class="relative top-20 mx-auto p-5 border w-[500px] shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold">Tambah Mata Pelajaran</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="mapelForm">
            <input type="hidden" id="edit_id" name="edit_id">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kode Mata Pelajaran *</label>
                <input type="text" id="kode_mapel" name="kode_mapel" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 uppercase" 
                       placeholder="MTK, IPA, IPS, dll" maxlength="20" required>
                <p class="text-xs text-gray-500 mt-1">Kode akan otomatis diubah ke huruf kapital</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Mata Pelajaran *</label>
                <input type="text" id="nama_mapel" name="nama_mapel" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                       placeholder="Matematika, Bahasa Indonesia, dll" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Guru Pengampu</label>
                <select id="guru_id" name="guru_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    <option value="">Pilih Guru</option>
                    <?php foreach($guru as $g): ?>
                    <option value="<?= $g->id ?>"><?= $g->nama_lengkap ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Opsional, bisa diisi kemudian</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">KKM (Kriteria Ketuntasan Minimal) *</label>
                <input type="number" id="kkm" name="kkm" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                       placeholder="75" min="1" max="100" required>
                <p class="text-xs text-gray-500 mt-1">Nilai antara 1-100</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                          placeholder="Deskripsi mata pelajaran (opsional)"></textarea>
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
    $('#mapelTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Auto uppercase kode mapel
    $('#kode_mapel').on('input', function() {
        this.value = this.value.toUpperCase();
    });
});

function openAddModal() {
    $('#modalTitle').text('Tambah Mata Pelajaran');
    $('#mapelForm')[0].reset();
    $('#edit_id').val('');
    $('#formModal').removeClass('hidden');
}

function closeModal() {
    $('#formModal').addClass('hidden');
}

function editData(id) {
    $.ajax({
        url: '<?= base_url("admin/mapel/get/") ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('#modalTitle').text('Edit Mata Pelajaran');
                $('#edit_id').val(response.data.id);
                $('#kode_mapel').val(response.data.kode_mapel);
                $('#nama_mapel').val(response.data.nama_mapel);
                $('#guru_id').val(response.data.guru_id || '');
                $('#kkm').val(response.data.kkm);
                $('#deskripsi').val(response.data.deskripsi || '');
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
        text: 'Apakah Anda yakin ingin menghapus mata pelajaran ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("admin/mapel/delete/") ?>' + id,
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

$('#mapelForm').on('submit', function(e) {
    e.preventDefault();
    
    var id = $('#edit_id').val();
    var url = id ? '<?= base_url("admin/mapel/edit/") ?>' + id : '<?= base_url("admin/mapel/add") ?>';
    
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
