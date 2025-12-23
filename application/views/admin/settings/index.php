<!-- Main Content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sekolah</h1>
            <p class="text-gray-600 mt-1">Kelola informasi sekolah dan logo</p>
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

        <!-- Settings Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- School Info Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-school mr-2 text-blue-600"></i>
                        Informasi Sekolah
                    </h2>

                    <form action="<?= base_url('admin/settings/update') ?>" method="POST">
                        <!-- CSRF Token -->
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <!-- Nama Sekolah -->
                        <div class="mb-6">
                            <label for="nama_sekolah" class="block text-gray-700 text-sm font-medium mb-2">
                                Nama Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nama_sekolah" 
                                name="nama_sekolah" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="<?= isset($settings['nama_sekolah']) ? $settings['nama_sekolah'] : '' ?>"
                                required
                            >
                        </div>

                        <!-- Alamat -->
                        <div class="mb-6">
                            <label for="alamat" class="block text-gray-700 text-sm font-medium mb-2">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="alamat" 
                                name="alamat" 
                                rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            ><?= isset($settings['alamat']) ? $settings['alamat'] : '' ?></textarea>
                        </div>

                        <!-- Kepala Sekolah -->
                        <div class="mb-6">
                            <label for="kepala_sekolah" class="block text-gray-700 text-sm font-medium mb-2">
                                Nama Kepala Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="kepala_sekolah" 
                                name="kepala_sekolah" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="<?= isset($settings['kepala_sekolah']) ? $settings['kepala_sekolah'] : '' ?>"
                                required
                            >
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Logo Upload -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-image mr-2 text-blue-600"></i>
                        Logo Sekolah
                    </h2>

                    <!-- Current Logo -->
                    <div class="mb-6 text-center">
                        <?php if (!empty($settings['logo'])): ?>
                            <img 
                                src="<?= base_url('assets/uploads/logo/' . $settings['logo']) ?>" 
                                alt="Logo Sekolah" 
                                class="w-48 h-48 object-contain mx-auto rounded-lg border border-gray-200 p-2"
                            >
                            <a 
                                href="<?= base_url('admin/settings/delete_logo') ?>" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus logo?')"
                                class="inline-block mt-4 text-red-600 hover:text-red-800 text-sm"
                            >
                                <i class="fas fa-trash mr-1"></i>Hapus Logo
                            </a>
                        <?php else: ?>
                            <div class="w-48 h-48 mx-auto bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-image text-4xl mb-2"></i>
                                    <p class="text-sm">Belum ada logo</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Upload Form -->
                    <form action="<?= base_url('admin/settings/upload_logo') ?>" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Token -->
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="mb-4">
                            <label for="logo" class="block text-gray-700 text-sm font-medium mb-2">
                                Upload Logo Baru
                            </label>
                            <input 
                                type="file" 
                                id="logo" 
                                name="logo" 
                                accept="image/jpeg,image/jpg,image/png"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: JPG, JPEG, PNG. Max: 2MB
                            </p>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            <i class="fas fa-upload mr-2"></i>Upload Logo
                        </button>
                    </form>

                    <!-- Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-sm font-semibold text-blue-900 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>Tips:
                        </h3>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>• Gunakan logo dengan ukuran persegi</li>
                            <li>• Resolusi minimal 300x300 px</li>
                            <li>• Background transparan (PNG)</li>
                            <li>• Logo akan ditampilkan di laporan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
