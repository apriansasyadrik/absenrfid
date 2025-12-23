<!-- Rekap Laporan -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Rekap Laporan Per Semester</h2>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="">Pilih Semester</option>
                        <?php foreach ($semester_list as $s): ?>
                            <option value="<?= $s->id ?>" <?= $semester_id == $s->id ? 'selected' : '' ?>><?= $s->jenis ?> (<?= $s->tanggal_mulai ?> - <?= $s->tanggal_selesai ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                    <select name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <option value="siswa" <?= $type == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                        <option value="guru" <?= $type == 'guru' ? 'selected' : '' ?>>Guru</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-5 py-2.5">
                        <i class="fas fa-search mr-2"></i>Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <?php if ($semester_id): ?>
        <!-- Export Button -->
        <div class="mb-4 flex justify-end">
            <a href="<?= base_url('admin/rekap-laporan/export_excel?' . http_build_query(['semester_id' => $semester_id, 'type' => $type])) ?>" class="text-white bg-green-600 hover:bg-green-700 rounded-lg px-5 py-2.5">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table id="rekapTable" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3"><?= $type == 'siswa' ? 'NIS' : 'NIP' ?></th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3"><?= $type == 'siswa' ? 'Kelas' : 'Jabatan' ?></th>
                        <th class="px-6 py-3">Total Hadir</th>
                        <th class="px-6 py-3">Total Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rekap as $item): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $no++ ?></td>
                        <td class="px-6 py-4"><?= $type == 'siswa' ? $item->nis : $item->nip ?></td>
                        <td class="px-6 py-4"><?= $item->nama_lengkap ?></td>
                        <td class="px-6 py-4"><?= $type == 'siswa' ? $item->tingkat . ' ' . $item->nama_kelas : $item->jabatan ?></td>
                        <td class="px-6 py-4"><?= $item->total_hadir ?></td>
                        <td class="px-6 py-4"><?= $item->total_terlambat ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
            Silakan pilih semester untuk melihat rekap.
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#rekapTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' }
    });
});
</script>
