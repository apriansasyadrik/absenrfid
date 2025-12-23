<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan WhatsApp Notifikasi</h1>
        <p class="text-gray-600">Konfigurasi WhatsApp API untuk notifikasi otomatis</p>
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

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('api')" id="tab-api" class="tab-button active whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-cog mr-2"></i>API Configuration
                </button>
                <button onclick="showTab('template')" id="tab-template" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-file-alt mr-2"></i>Message Templates
                </button>
                <button onclick="showTab('kelas')" id="tab-kelas" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-users mr-2"></i>Active Classes
                </button>
                <button onclick="showTab('queue')" id="tab-queue" class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-list mr-2"></i>Queue Status
                </button>
            </nav>
        </div>
    </div>

    <!-- API Configuration Tab -->
    <div id="content-api" class="tab-content">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <h3 class="text-lg font-semibold mb-4">API Configuration</h3>
            <form action="<?= base_url('admin/wasettings/save_settings') ?>" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">API URL *</label>
                    <input type="url" name="api_url" 
                           value="<?= $settings->api_url ?? '' ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                           placeholder="https://api.whatsapp.com/send" required>
                    <p class="text-xs text-gray-500 mt-1">URL endpoint WhatsApp API Gateway</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">API Key *</label>
                    <input type="text" name="api_key" 
                           value="<?= $settings->api_key ?? '' ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                           placeholder="Your API Key" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Sender ID *</label>
                    <input type="text" name="sender" 
                           value="<?= $settings->sender ?? '' ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                           placeholder="628123456789" required>
                    <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp pengirim (format: 628xxx)</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Link URL (Optional)</label>
                    <input type="url" name="link_url" 
                           value="<?= $settings->link_url ?? '' ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" 
                           placeholder="https://school.com">
                    <p class="text-xs text-gray-500 mt-1">URL yang akan ditambahkan di akhir pesan (optional)</p>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               <?= (isset($settings->is_active) && $settings->is_active) ? 'checked' : '' ?> 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan WhatsApp Notifikasi</span>
                    </label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-save mr-1"></i> Simpan Konfigurasi
                    </button>
                    <button type="button" onclick="testSend()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-paper-plane mr-1"></i> Test Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Templates Tab -->
    <div id="content-template" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Message Templates</h3>
            <p class="text-gray-600 mb-4">Gunakan variabel: {nama}, {kelas}, {tanggal}, {jam}, {status}, {keterangan}</p>
            
            <?php foreach($templates as $t): ?>
            <div class="mb-6 p-4 border border-gray-200 rounded">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold">
                        <?= ucfirst(str_replace('_', ' ', $t->tipe)) ?>
                    </h4>
                    <span class="text-xs text-gray-500">Tipe: <?= $t->tipe ?></span>
                </div>
                <textarea id="template-<?= $t->tipe ?>" 
                          class="w-full p-2 border rounded text-sm font-mono" 
                          rows="5"><?= $t->template ?></textarea>
                <button onclick="saveTemplate('<?= $t->tipe ?>')" 
                        class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Template
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Active Classes Tab -->
    <div id="content-kelas" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <h3 class="text-lg font-semibold mb-4">Pilih Kelas yang Aktif Notifikasi</h3>
            <p class="text-gray-600 mb-4">Hanya kelas yang dicentang yang akan menerima notifikasi WhatsApp</p>
            
            <form id="kelasForm">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    <?php foreach($kelas as $k): ?>
                    <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="kelas_ids[]" value="<?= $k->id ?>" 
                               <?= in_array($k->id, $active_kelas) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm"><?= $k->nama_kelas ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-save mr-1"></i> Simpan Pilihan Kelas
                </button>
            </form>
        </div>
    </div>

    <!-- Queue Status Tab -->
    <div id="content-queue" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Queue Status</h3>
                <button onclick="refreshStats()" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="text-yellow-800 text-sm font-semibold mb-1">Pending</div>
                    <div id="stat-pending" class="text-2xl font-bold text-yellow-600">-</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="text-green-800 text-sm font-semibold mb-1">Sent</div>
                    <div id="stat-sent" class="text-2xl font-bold text-green-600">-</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="text-red-800 text-sm font-semibold mb-1">Failed</div>
                    <div id="stat-failed" class="text-2xl font-bold text-red-600">-</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="text-blue-800 text-sm font-semibold mb-1">Total</div>
                    <div id="stat-total" class="text-2xl font-bold text-blue-600">-</div>
                </div>
            </div>

            <div class="flex gap-2">
                <button onclick="clearFailedQueue()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    <i class="fas fa-trash mr-1"></i> Clear Failed Queue
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Tab management
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('active', 'border-blue-500', 'text-blue-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const button = document.getElementById('tab-' + tabName);
    button.classList.add('active', 'border-blue-500', 'text-blue-600');
    button.classList.remove('border-transparent', 'text-gray-500');
}

// Test send message
function testSend() {
    Swal.fire({
        title: 'Test Kirim Pesan',
        input: 'text',
        inputLabel: 'Masukkan nomor WhatsApp tujuan',
        inputPlaceholder: '08123456789',
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) {
                return 'Nomor harus diisi!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("admin/wasettings/test_send") ?>',
                type: 'POST',
                data: { phone: result.value },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire('Berhasil', response.message, 'success');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}

// Save template
function saveTemplate(tipe) {
    const template = document.getElementById('template-' + tipe).value;
    
    $.ajax({
        url: '<?= base_url("admin/wasettings/save_template") ?>',
        type: 'POST',
        data: { jenis: tipe, template: template },
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                Swal.fire('Berhasil', response.message, 'success');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

// Save active classes
$('#kelasForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= base_url("admin/wasettings/save_active_classes") ?>',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                Swal.fire('Berhasil', response.message, 'success');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
});

// Refresh stats
function refreshStats() {
    $.ajax({
        url: '<?= base_url("admin/wasettings/queue_stats") ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('#stat-pending').text(response.data.pending);
                $('#stat-sent').text(response.data.sent);
                $('#stat-failed').text(response.data.failed);
                $('#stat-total').text(response.data.total);
            }
        }
    });
}

// Clear failed queue
function clearFailedQueue() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Hapus semua pesan yang gagal dari queue?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("admin/wasettings/clear_failed_queue") ?>',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire('Berhasil', response.message, 'success');
                        refreshStats();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}

// Initial load
$(document).ready(function() {
    refreshStats();
    
    // Auto refresh stats every 30 seconds when on queue tab
    setInterval(function() {
        if (!$('#content-queue').hasClass('hidden')) {
            refreshStats();
        }
    }, 30000);
});
</script>

<style>
.tab-button {
    border-bottom-color: transparent;
    color: #6b7280;
}
.tab-button.active {
    border-bottom-color: #3b82f6;
    color: #3b82f6;
}
</style>
