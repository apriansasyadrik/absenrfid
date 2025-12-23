<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Sistem Absensi RFID' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-center">
                <div class="bg-white rounded-full w-24 h-24 mx-auto flex items-center justify-center mb-4">
                    <i class="fas fa-id-card text-5xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-white">Sistem Absensi RFID</h2>
                <p class="text-blue-100 mt-2">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-6 rounded" role="alert">
                    <p class="font-medium"><?= $this->session->flashdata('success') ?></p>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 rounded" role="alert">
                    <p class="font-medium"><?= $this->session->flashdata('error') ?></p>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <div class="p-8">
                <form action="<?= base_url('auth/do_login') ?>" method="POST">
                    <!-- CSRF Token -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                    <!-- Username -->
                    <div class="mb-6">
                        <label for="username" class="block text-gray-700 text-sm font-medium mb-2">
                            <i class="fas fa-user mr-2"></i>Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                            placeholder="Masukkan username"
                            required
                            autofocus
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                            placeholder="Masukkan password"
                            required
                        >
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-6 flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                            <span class="ml-2 text-gray-700 text-sm">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transform transition duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </form>

                <!-- Info -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Gunakan akun yang telah terdaftar
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    &copy; <?= date('Y') ?> Sistem Absensi RFID. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="mt-6 text-center">
            <a href="<?= base_url('absensi') ?>" class="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transform transition duration-200 hover:scale-105">
                <i class="fas fa-desktop mr-2"></i>Tampilan Absensi RFID
            </a>
        </div>
    </div>

</body>
</html>
