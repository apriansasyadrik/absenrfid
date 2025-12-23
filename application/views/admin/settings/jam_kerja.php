<!-- Jam Kerja Settings -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Pengaturan Jam Kerja</h2>
            <p class="text-gray-600 mt-1">Atur jam kerja dan hari libur untuk sistem absensi</p>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="<?= base_url('admin/jam-kerja/update') ?>" method="post">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Hari Kerja</th>
                                <th class="px-6 py-3">Jam Masuk</th>
                                <th class="px-6 py-3">Jam Pulang</th>
                                <th class="px-6 py-3">Toleransi (menit)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            foreach ($days as $day):
                                $jk = isset($jam_kerja[$day]) ? $jam_kerja[$day] : null;
                            ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900"><?= $day ?></td>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="is_kerja_<?= $day ?>" value="1" <?= ($jk && $jk->is_kerja) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="time" name="jam_masuk_<?= $day ?>" value="<?= $jk ? $jk->jam_masuk : '07:00' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="time" name="jam_pulang_<?= $day ?>" value="<?= $jk ? $jk->jam_pulang : '15:00' ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="toleransi_<?= $day ?>" value="<?= $jk ? $jk->toleransi_menit : '15' ?>" min="0" max="60" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
