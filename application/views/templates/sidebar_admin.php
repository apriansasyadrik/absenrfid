<!-- Admin Sidebar -->
<aside id="sidebar" class="sidebar fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200">
    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            <!-- Dashboard -->
            <li>
                <a href="<?= base_url('dashboard') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group">
                    <i class="fas fa-tachometer-alt w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            
            <!-- Pengaturan -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-blue-50" onclick="toggleSubmenu('pengaturan')">
                    <i class="fas fa-cog w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Pengaturan</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="submenu-pengaturan" class="hidden py-2 space-y-2">
                    <li>
                        <a href="<?= base_url('admin/settings') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-school text-sm mr-2"></i>Sekolah
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/jam-kerja') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-clock text-sm mr-2"></i>Jam Kerja
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/hari-libur') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-calendar-times text-sm mr-2"></i>Hari Libur
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Data Master -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-blue-50" onclick="toggleSubmenu('master')">
                    <i class="fas fa-database w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Data Master</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="submenu-master" class="hidden py-2 space-y-2">
                    <li>
                        <a href="<?= base_url('admin/tahun-ajaran') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-calendar text-sm mr-2"></i>Tahun Ajaran
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/semester') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-calendar-alt text-sm mr-2"></i>Semester
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/kelas') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-door-open text-sm mr-2"></i>Kelas
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/siswa') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-user-graduate text-sm mr-2"></i>Siswa
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/guru') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-chalkboard-teacher text-sm mr-2"></i>Guru & Staff
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Mata Pelajaran & Jadwal -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-blue-50" onclick="toggleSubmenu('akademik')">
                    <i class="fas fa-book w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Akademik</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="submenu-akademik" class="hidden py-2 space-y-2">
                    <li>
                        <a href="<?= base_url('admin/mapel') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-book-open text-sm mr-2"></i>Mata Pelajaran
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/jadwal') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-calendar-week text-sm mr-2"></i>Jadwal Pelajaran
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- WhatsApp -->
            <li>
                <a href="<?= base_url('admin/wa-settings') ?>" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-blue-50 group">
                    <i class="fab fa-whatsapp w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="ml-3">WA Notifikasi</span>
                </a>
            </li>
            
            <!-- Laporan -->
            <li>
                <button type="button" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg group hover:bg-blue-50" onclick="toggleSubmenu('laporan')">
                    <i class="fas fa-file-alt w-5 text-gray-500 group-hover:text-blue-600"></i>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Laporan</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul id="submenu-laporan" class="hidden py-2 space-y-2">
                    <li>
                        <a href="<?= base_url('admin/laporan-siswa') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-users text-sm mr-2"></i>Laporan Siswa
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/laporan-guru') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-user-tie text-sm mr-2"></i>Laporan Guru
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/rekap-laporan') ?>" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-50">
                            <i class="fas fa-chart-bar text-sm mr-2"></i>Rekap Laporan
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Divider -->
            <li class="pt-4 mt-4 space-y-2 border-t border-gray-200">
                <!-- RFID Display -->
                <a href="<?= base_url('absensi') ?>" target="_blank" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group">
                    <i class="fas fa-desktop w-5 text-gray-500 group-hover:text-green-600"></i>
                    <span class="ml-3">Tampilan RFID</span>
                    <i class="fas fa-external-link-alt ml-auto text-xs"></i>
                </a>
            </li>
            
            <!-- Logout -->
            <li>
                <a href="<?= base_url('logout') ?>" class="flex items-center p-2 text-red-600 rounded-lg hover:bg-red-50 group">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
    function toggleSubmenu(id) {
        const submenu = document.getElementById('submenu-' + id);
        submenu.classList.toggle('hidden');
    }
    
    // Sidebar toggle
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
