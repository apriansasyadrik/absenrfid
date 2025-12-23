<!-- Main Content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-600 mt-1">Selamat datang, <?= get_user_data('nama') ?>!</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Siswa -->
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($total_siswa) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Total Guru -->
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Guru</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($total_guru) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Absen Siswa Hari Ini -->
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Absen Siswa Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($absen_siswa_hari_ini) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Absen Guru Hari Ini -->
            <div class="card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Absen Guru Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($absen_guru_hari_ini) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Siswa Weekly Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                    Kehadiran Siswa (7 Hari Terakhir)
                </h3>
                <canvas id="siswChart"></canvas>
            </div>
            
            <!-- Guru Weekly Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-line mr-2 text-green-600"></i>
                    Kehadiran Guru (7 Hari Terakhir)
                </h3>
                <canvas id="guruChart"></canvas>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-history mr-2 text-gray-600"></i>
                Aktivitas Terbaru Hari Ini
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Waktu</th>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Jenis</th>
                            <th scope="col" class="px-6 py-3">Kelas/Jabatan</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_activities)): ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">
                                        <?= date('H:i', strtotime($activity['waktu_masuk'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= $activity['nama'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $activity['jenis'] == 'siswa' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                            <?= ucfirst($activity['jenis']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= $activity['jenis'] == 'siswa' ? $activity['nama_kelas'] : '-' ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $activity['status_masuk'] == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $activity['status_masuk'] == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat ' . $activity['keterlambatan_menit'] . ' menit' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada aktivitas hari ini
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Siswa Chart
    const siswaCtx = document.getElementById('siswaChart').getContext('2d');
    new Chart(siswaCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($weekly_siswa, 'tanggal')) ?>,
            datasets: [{
                label: 'Kehadiran Siswa',
                data: <?= json_encode(array_column($weekly_siswa, 'jumlah')) ?>,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Guru Chart
    const guruCtx = document.getElementById('guruChart').getContext('2d');
    new Chart(guruCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($weekly_guru, 'tanggal')) ?>,
            datasets: [{
                label: 'Kehadiran Guru',
                data: <?= json_encode(array_column($weekly_guru, 'jumlah')) ?>,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
