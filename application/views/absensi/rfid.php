<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Absensi RFID' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow: hidden;
        }
        
        .rfid-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .scan-area {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .latest-item {
            transition: all 0.3s ease;
        }
        
        .latest-item:hover {
            transform: translateX(5px);
        }
        
        #current-time {
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body class="rfid-display">
    
    <!-- Header -->
    <div class="bg-white bg-opacity-10 backdrop-blur-md border-b border-white border-opacity-20">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <?php if (!empty($settings['logo'])): ?>
                        <img src="<?= base_url('assets/uploads/logo/' . $settings['logo']) ?>" alt="Logo" class="h-16 w-16 mr-4">
                    <?php else: ?>
                        <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-school text-3xl text-blue-600"></i>
                        </div>
                    <?php endif; ?>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold"><?= isset($settings['nama_sekolah']) ? $settings['nama_sekolah'] : 'SISTEM ABSENSI RFID' ?></h1>
                        <p class="text-sm text-white text-opacity-80"><?= isset($settings['alamat']) ? $settings['alamat'] : '' ?></p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div id="current-time" class="text-4xl font-bold mb-1"></div>
                    <div id="current-date" class="text-lg"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Scan Area (Left/Center) -->
            <div class="lg:col-span-2">
                <!-- Scan Prompt -->
                <div id="scan-prompt" class="bg-white bg-opacity-10 backdrop-blur-md border-2 border-white border-opacity-30 rounded-3xl p-12 text-center mb-6 scan-area">
                    <div class="mb-6">
                        <i class="fas fa-id-card text-white text-8xl mb-4 opacity-80"></i>
                    </div>
                    <h2 class="text-4xl font-bold text-white mb-4">Silakan Tap Kartu RFID Anda</h2>
                    <p class="text-xl text-white text-opacity-80">Dekatkan kartu ke reader untuk melakukan absensi</p>
                    <div class="mt-8 flex justify-center">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-white rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                            <div class="w-3 h-3 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            <div class="w-3 h-3 bg-white rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Scan Result (Hidden by default) -->
                <div id="scan-result" class="hidden bg-white rounded-3xl shadow-2xl p-8 mb-6">
                    <div class="flex items-center">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <img id="result-foto" src="" alt="Foto" class="w-32 h-32 rounded-full object-cover border-4 border-blue-500 shadow-lg">
                        </div>
                        
                        <!-- Info -->
                        <div class="ml-8 flex-grow">
                            <div class="mb-4">
                                <span id="result-type-badge" class="px-4 py-2 rounded-full text-sm font-semibold"></span>
                            </div>
                            <h3 id="result-nama" class="text-3xl font-bold text-gray-900 mb-2"></h3>
                            <p id="result-kelas" class="text-xl text-gray-600 mb-4"></p>
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-blue-500 text-2xl mr-2"></i>
                                    <span id="result-waktu" class="text-2xl font-semibold text-gray-900"></span>
                                </div>
                                <div id="result-status-container" class="flex items-center">
                                    <span id="result-status" class="px-4 py-2 rounded-lg text-lg font-semibold"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Success Icon -->
                        <div class="flex-shrink-0 ml-4">
                            <i id="result-icon" class="fas fa-check-circle text-6xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Today -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white text-opacity-80 text-sm mb-1">Total Siswa Hadir</p>
                                <p id="stat-siswa" class="text-4xl font-bold text-white">0</p>
                            </div>
                            <i class="fas fa-user-graduate text-white text-4xl opacity-50"></i>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white text-opacity-80 text-sm mb-1">Total Guru Hadir</p>
                                <p id="stat-guru" class="text-4xl font-bold text-white">0</p>
                            </div>
                            <i class="fas fa-chalkboard-teacher text-white text-4xl opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Latest Attendance List (Right) -->
            <div class="lg:col-span-1">
                <div class="bg-white bg-opacity-10 backdrop-blur-md border border-white border-opacity-20 rounded-2xl p-6">
                    <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Absensi Hari Ini
                    </h3>
                    
                    <div id="latest-list" class="space-y-3 max-h-[600px] overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                        <?php if (!empty($latest_absensi)): ?>
                            <?php foreach ($latest_absensi as $item): ?>
                                <div class="latest-item bg-white rounded-xl p-4 shadow">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($item['foto'])): ?>
                                                <img src="<?= base_url('assets/uploads/' . ($item['jenis'] == 'siswa' ? 'foto_siswa/' : 'foto_guru/') . $item['foto']) ?>" alt="Foto" class="w-12 h-12 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-3 flex-grow">
                                            <p class="font-semibold text-gray-900 text-sm"><?= $item['nama'] ?></p>
                                            <p class="text-xs text-gray-600"><?= $item['jenis'] == 'siswa' ? $item['nama_kelas'] : 'Guru' ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-medium text-gray-900"><?= date('H:i', strtotime($item['waktu_masuk'])) ?></p>
                                            <span class="px-2 py-1 rounded text-xs font-medium <?= $item['status_masuk'] == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= $item['status_masuk'] == 'tepat_waktu' ? 'Tepat' : 'Terlambat' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-white text-opacity-60 py-8">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada absensi hari ini</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Sound Effect (optional) -->
    <audio id="success-sound" preload="auto">
        <source src="<?= base_url('assets/sounds/success.mp3') ?>" type="audio/mpeg">
    </audio>
    
    <script>
        const base_url = '<?= base_url() ?>';
        
        // Update clock
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            document.getElementById('current-time').textContent = time;
            document.getElementById('current-date').textContent = date;
        }
        
        // Update statistics
        function updateStatistics() {
            $.ajax({
                url: base_url + 'api/rfid/get_latest',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Count siswa and guru
                        let siswaCount = 0;
                        let guruCount = 0;
                        
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                if (item.jenis === 'siswa') siswaCount++;
                                if (item.jenis === 'guru') guruCount++;
                            });
                        }
                        
                        $('#stat-siswa').text(siswaCount);
                        $('#stat-guru').text(guruCount);
                    }
                }
            });
        }
        
        // Show scan result
        function showScanResult(data) {
            // Hide prompt
            $('#scan-prompt').fadeOut(300);
            
            // Set photo
            const defaultFoto = base_url + 'assets/images/default-avatar.png';
            const fotoPath = data.foto ? base_url + 'assets/uploads/' + (data.type === 'masuk' ? (data.kelas ? 'foto_siswa/' : 'foto_guru/') : '') + data.foto : defaultFoto;
            $('#result-foto').attr('src', fotoPath);
            
            // Set type badge
            const isSiswa = data.kelas !== undefined;
            $('#result-type-badge').removeClass().addClass('px-4 py-2 rounded-full text-sm font-semibold ' + (isSiswa ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'));
            $('#result-type-badge').text(isSiswa ? 'SISWA' : 'GURU');
            
            // Set info
            $('#result-nama').text(data.nama);
            $('#result-kelas').text(isSiswa ? data.kelas : data.jabatan || 'Guru');
            $('#result-waktu').text(new Date(data.waktu).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));
            
            // Set status
            const isOnTime = data.status.includes('Tepat') || data.type === 'pulang';
            $('#result-status').removeClass().addClass('px-4 py-2 rounded-lg text-lg font-semibold ' + (isOnTime ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'));
            $('#result-status').text(data.status);
            
            // Set icon
            $('#result-icon').removeClass().addClass('fas fa-check-circle text-6xl ' + (isOnTime ? 'text-green-500' : 'text-red-500'));
            
            // Show result
            $('#scan-result').removeClass('hidden').hide().fadeIn(500);
            
            // Play sound (optional)
            try {
                document.getElementById('success-sound').play();
            } catch (e) {}
            
            // Hide result after 5 seconds
            setTimeout(function() {
                $('#scan-result').fadeOut(300, function() {
                    $('#scan-prompt').fadeIn(300);
                });
            }, 5000);
            
            // Refresh latest list
            refreshLatestList();
            updateStatistics();
        }
        
        // Refresh latest attendance list
        function refreshLatestList() {
            $.ajax({
                url: base_url + 'api/rfid/get_latest?limit=20',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status && response.data) {
                        let html = '';
                        
                        response.data.forEach(function(item) {
                            const fotoPath = item.foto ? base_url + 'assets/uploads/' + (item.jenis === 'siswa' ? 'foto_siswa/' : 'foto_guru/') + item.foto : '';
                            const displayName = item.jenis === 'siswa' ? item.nama_kelas : 'Guru';
                            const statusClass = item.status_masuk === 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            const statusText = item.status_masuk === 'tepat_waktu' ? 'Tepat' : 'Terlambat';
                            const waktu = new Date(item.waktu_masuk).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                            
                            html += `
                                <div class="latest-item bg-white rounded-xl p-4 shadow slide-in">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            ${fotoPath ? `<img src="${fotoPath}" alt="Foto" class="w-12 h-12 rounded-full object-cover">` : `<div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center"><i class="fas fa-user text-gray-400"></i></div>`}
                                        </div>
                                        <div class="ml-3 flex-grow">
                                            <p class="font-semibold text-gray-900 text-sm">${item.nama}</p>
                                            <p class="text-xs text-gray-600">${displayName}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-medium text-gray-900">${waktu}</p>
                                            <span class="px-2 py-1 rounded text-xs font-medium ${statusClass}">${statusText}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        $('#latest-list').html(html || '<div class="text-center text-white text-opacity-60 py-8"><i class="fas fa-inbox text-4xl mb-2"></i><p>Belum ada absensi hari ini</p></div>');
                    }
                }
            });
        }
        
        // Initialize
        $(document).ready(function() {
            // Update clock every second
            updateClock();
            setInterval(updateClock, 1000);
            
            // Update statistics
            updateStatistics();
            setInterval(updateStatistics, 10000); // Every 10 seconds
            
            // Refresh latest list
            setInterval(refreshLatestList, 5000); // Every 5 seconds
            
            // Listen for RFID scan simulation (for testing - remove in production)
            // You can test by calling: simulateScan('test_rfid_uid')
            window.simulateScan = function(rfid_uid) {
                $.ajax({
                    url: base_url + 'api/rfid/scan',
                    method: 'POST',
                    data: { rfid_uid: rfid_uid },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            showScanResult(response.data);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            };
        });
    </script>
</body>
</html>
