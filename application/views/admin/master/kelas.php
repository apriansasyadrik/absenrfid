<!-- Main Content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Data Kelas</h1>
                <p class="text-gray-600 mt-1">Kelola data kelas dan walikelas</p>
            </div>
            <button onclick="showAddModal()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>Tambah Kelas
            </button>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p class="font-medium"><?= $this->session->flashdata('success') ?></p>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-medium"><?= $this->session->flashdata('error') ?></p>
            </div>
        <?php endif; ?>

        <!-- Class Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <table id="kelasTable" class="datatable w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama Kelas</th>
                        <th class="px-6 py-3">Tingkat</th>
                        <th class="px-6 py-3">Jurusan</th>
                        <th class="px-6 py-3">Walikelas</th>
                        <th class="px-6 py-3">Tahun Ajaran</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kelas as $k): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?= $no++ ?></td>
                            <td class="px-6 py-4 font-medium"><?= $k['nama_kelas'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Kelas <?= $k['tingkat'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= $k['jurusan'] ?></td>
                            <td class="px-6 py-4"><?= $k['nama_walikelas'] ?? '-' ?></td>
                            <td class="px-6 py-4"><?= $k['tahun_ajaran'] ?></td>
                            <td class="px-6 py-4">
                                <button onclick="editKelas(<?= $k['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete(<?= $k['id'] ?>, '<?= $k['nama_kelas'] ?>')" class="text-red-600 hover:text-red-900">
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
<div id="kelasModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" id="modalTitle">Tambah Kelas</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="kelasForm">
            <input type="hidden" id="kelas_id" name="kelas_id">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kelas" id="nama_kelas" required placeholder="Contoh: X IPA 1" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Tingkat <span class="text-red-500">*</span></label>
                <select name="tingkat" id="tingkat" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Tingkat</option>
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Jurusan</label>
                <input type="text" name="jurusan" id="jurusan" placeholder="IPA, IPS, dll" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Walikelas</label>
                <select name="walikelas_id" id="walikelas_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Walikelas</option>
                    <?php foreach ($guru_list as $id => $nama): ?>
                        <option value="<?= $id ?>"><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Tahun Ajaran</option>
                    <?php foreach ($tahun_ajaran_list as $id => $tahun): ?>
                        <option value="<?= $id ?>"><?= $tahun ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kelas';
    document.getElementById('kelasForm').reset();
    document.getElementById('kelas_id').value = '';
    document.getElementById('kelasModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('kelasModal').classList.add('hidden');
}

function editKelas(id) {
    showLoading();
    
    fetch(base_url + 'admin/kelas/get/' + id)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.status) {
                document.getElementById('modalTitle').textContent = 'Edit Kelas';
                document.getElementById('kelas_id').value = data.data.id;
                document.getElementById('nama_kelas').value = data.data.nama_kelas;
                document.getElementById('tingkat').value = data.data.tingkat;
                document.getElementById('jurusan').value = data.data.jurusan;
                document.getElementById('walikelas_id').value = data.data.walikelas_id;
                document.getElementById('tahun_ajaran_id').value = data.data.tahun_ajaran_id;
                
                document.getElementById('kelasModal').classList.remove('hidden');
            }
        });
}

function confirmDelete(id, nama) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus kelas "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'admin/kelas/delete/' + id;
        }
    });
}

document.getElementById('kelasForm').addEventListener('submit', function(e) {
    e.preventDefault();
    showLoading();
    
    const formData = new FormData(this);
    const kelasId = document.getElementById('kelas_id').value;
    const url = kelasId ? base_url + 'admin/kelas/edit/' + kelasId : base_url + 'admin/kelas/add';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.status) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message
            });
        }
    });
});
</script>
