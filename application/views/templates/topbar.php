<!-- Top Navigation Bar -->
<nav class="bg-white border-b border-gray-200 fixed w-full z-40 top-0 left-0">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <!-- Sidebar Toggle -->
                <button id="sidebar-toggle" type="button" class="inline-flex items-center p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Logo & Title -->
                <a href="<?= base_url('dashboard') ?>" class="flex ml-2 md:mr-24">
                    <i class="fas fa-id-card text-3xl text-blue-600 mr-3"></i>
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap hidden md:block">Absensi RFID</span>
                </a>
            </div>
            
            <!-- Right Menu -->
            <div class="flex items-center">
                <!-- User Menu -->
                <div class="flex items-center ml-3 relative">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2 hidden md:block">
                            <?= get_user_data('nama') ?>
                        </span>
                        <span class="text-xs text-gray-500 mr-3 hidden md:block">
                            (<?= ucfirst(get_user_data('role')) ?>)
                        </span>
                    </div>
                    
                    <button type="button" class="flex text-sm bg-gray-100 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                        <i class="fas fa-user-circle text-3xl text-gray-600"></i>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div class="hidden absolute right-0 top-full mt-2 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-lg" id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900"><?= get_user_data('nama') ?></span>
                            <span class="block text-sm text-gray-500 truncate"><?= get_user_data('username') ?></span>
                        </div>
                        <ul class="py-2">
                            <li>
                                <a href="<?= base_url('profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle user dropdown
    document.getElementById('user-menu-button').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('user-dropdown').classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        document.getElementById('user-dropdown').classList.add('hidden');
    });
</script>
