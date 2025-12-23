<!-- Hari Libur -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Hari Libur Nasional</h2>
                <p class="text-gray-600 mt-1">Kelola hari libur nasional dan cuti bersama</p>
            </div>
            <button onclick="showAddModal()" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                <i class="fas fa-plus mr-2"></i>Tambah Hari Libur
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table id="holidayTable" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($holidays as $holiday): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $no++ ?></td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($holiday->tanggal)) ?></td>
                        <td class="px-6 py-4"><?= $holiday->keterangan ?></td>
                        <td class="px-6 py-4">
                            <button onclick="editHoliday(<?= $holiday->id ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteHoliday(<?= $holiday->id ?>)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="holidayModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Tambah Hari Libur</h3>
            <form id="holidayForm">
                <input type="hidden" id="holiday_id" name="id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" required rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Hari Libur';
    document.getElementById('holidayForm').reset();
    document.getElementById('holiday_id').value = '';
    document.getElementById('holidayModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('holidayModal').classList.add('hidden');
}

function editHoliday(id) {
    fetch('<?= base_url('admin/hari-libur/get/') ?>' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Hari Libur';
            document.getElementById('holiday_id').value = data.id;
            document.getElementById('tanggal').value = data.tanggal;
            document.getElementById('keterangan').value = data.keterangan;
            document.getElementById('holidayModal').classList.remove('hidden');
        });
}

function deleteHoliday(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= base_url('admin/hari-libur/delete/') ?>' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
        }
    });
}

document.getElementById('holidayForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('holiday_id').value;
    const url = id ? '<?= base_url('admin/hari-libur/edit/') ?>' + id : '<?= base_url('admin/hari-libur/add') ?>';
    
    const formData = new FormData(this);
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Berhasil!', data.message, 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('Gagal!', data.message, 'error');
        }
    });
});

// Initialize DataTable
$(document).ready(function() {
    $('#holidayTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        order: [[1, 'desc']]
    });
});
</script>
