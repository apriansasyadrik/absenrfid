<!-- Main Content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Data Guru & Staff</h1>
                <p class="text-gray-600 mt-1">Kelola data guru, staff, dan RFID</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="showImportModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-import mr-2"></i>Import Excel
                </button>
                <a href="<?= base_url('admin/guru/export') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-file-export mr-2"></i>Export Excel
                </a>
                <button onclick="showAddModal()" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Guru
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p class="font-medium"><?= $this->session->flashdata('success') ?></p>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p class="font-medium"><?= $this->session->flashdata('error') ?></p>
            </div>
        <?php endif; ?>

        <!-- Teacher Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table id="guruTable" class="datatable w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">NIP</th>
                            <th class="px-6 py-3">RFID UID</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Jenis Kelamin</th>
                            <th class="px-6 py-3">Jabatan</th>
                            <th class="px-6 py-3">No HP</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($guru as $g): ?>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4"><?= $no++ ?></td>
                                <td class="px-6 py-4 font-medium"><?= $g['nip'] ?></td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($g['rfid_uid'])): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?= $g['rfid_uid'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Belum Terdaftar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?= $g['nama'] ?></td>
                                <td class="px-6 py-4"><?= $g['jenis_kelamin'] ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= $g['jabatan'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4"><?= $g['no_hp'] ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($g['is_active']): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="editGuru(<?= $g['id'] ?>)" class="text-blue-600 hover:text-blue-900 mr-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmDelete(<?= $g['id'] ?>, '<?= $g['nama'] ?>')" class="text-red-600 hover:text-red-900" title="Delete">
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
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Import Data Guru dari Excel</h3>
            <button onclick="closeImportModal()" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
            <p class="font-medium mb-2">Format Excel:</p>
            <ul class="text-sm space-y-1 ml-4 list-disc">
                <li>Kolom A: NIP</li>
                <li>Kolom B: RFID UID</li>
                <li>Kolom C: Nama Lengkap</li>
                <li>Kolom D: Jenis Kelamin (L/P)</li>
                <li>Kolom E: Tempat Lahir</li>
                <li>Kolom F: Tanggal Lahir (YYYY-MM-DD)</li>
                <li>Kolom G: Alamat</li>
                <li>Kolom H: No HP</li>
                <li>Kolom I: Email</li>
                <li>Kolom J: Jabatan</li>
            </ul>
            <p class="text-sm mt-2">* Baris pertama adalah header (akan diskip)</p>
            <p class="text-sm">* Data yang NIP-nya sudah ada akan diabaikan</p>
        </div>
        
        <form action="<?= base_url('admin/guru/import') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Pilih File Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Format: .xlsx atau .xls, Max: 10MB</p>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-upload mr-2"></i>Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="guruModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" id="modalTitle">Tambah Guru</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="guruForm" enctype="multipart/form-data">
            <input type="hidden" id="guru_id" name="guru_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIP -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">NIP</label>
                    <input type="text" name="nip" id="nip" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- RFID UID -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">RFID UID</label>
                    <input type="text" name="rfid_uid" id="rfid_uid" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Nama -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                
                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Jabatan -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan" placeholder="Contoh: Guru Matematika, Staff TU" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- No HP -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">No HP (WA)</label>
                    <input type="text" name="no_hp" id="no_hp" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <!-- Alamat -->
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Alamat</label>
                <textarea name="alamat" id="alamat" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <!-- Foto -->
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-medium mb-2">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Max: 2MB</p>
            </div>
            
            <!-- Buttons -->
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
function showImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Guru';
    document.getElementById('guruForm').reset();
    document.getElementById('guru_id').value = '';
    document.getElementById('guruModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('guruModal').classList.add('hidden');
}

function editGuru(id) {
    showLoading();
    
    fetch(base_url + 'admin/guru/get/' + id)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.status) {
                document.getElementById('modalTitle').textContent = 'Edit Guru';
                document.getElementById('guru_id').value = data.data.id;
                document.getElementById('nip').value = data.data.nip;
                document.getElementById('rfid_uid').value = data.data.rfid_uid;
                document.getElementById('nama').value = data.data.nama;
                document.getElementById('jenis_kelamin').value = data.data.jenis_kelamin;
                document.getElementById('tempat_lahir').value = data.data.tempat_lahir;
                document.getElementById('tanggal_lahir').value = data.data.tanggal_lahir;
                document.getElementById('alamat').value = data.data.alamat;
                document.getElementById('no_hp').value = data.data.no_hp;
                document.getElementById('email').value = data.data.email;
                document.getElementById('jabatan').value = data.data.jabatan;
                
                document.getElementById('guruModal').classList.remove('hidden');
            }
        });
}

function confirmDelete(id, nama) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Hapus guru "${nama}"? Data tidak dapat dikembalikan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'admin/guru/delete/' + id;
        }
    });
}

// Form submission
document.getElementById('guruForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoading();
    
    const formData = new FormData(this);
    const guruId = document.getElementById('guru_id').value;
    const url = guruId ? base_url + 'admin/guru/edit/' + guruId : base_url + 'admin/guru/add';
    
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
    })
    .catch(error => {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan: ' + error
        });
    });
});
</script>
