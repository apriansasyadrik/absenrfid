<!-- Laporan Guru -->
<div class="p-4 sm:ml-64">
    <div class="p-4 mt-14">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Laporan Absensi Guru</h2>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4">
            <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <?php foreach ($bulan_list as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $bulan == $key ? 'selected' : '' ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        <?php foreach ($tahun_list as $y): ?>
                            <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-5 py-2.5">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Export Button -->
        <div class="mb-4 flex justify-end">
            <a href="<?= base_url('admin/laporan-guru/export_excel?' . http_build_query(['bulan' => $bulan, 'tahun' => $tahun])) ?>" class="text-white bg-green-600 hover:bg-green-700 rounded-lg px-5 py-2.5">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table id="laporanTable" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">NIP</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Jabatan</th>
                        <th class="px-6 py-3">Total Hadir</th>
                        <th class="px-6 py-3">Total Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($laporan as $item): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4"><?= $no++ ?></td>
                        <td class="px-6 py-4"><?= $item->nip ?></td>
                        <td class="px-6 py-4"><?= $item->nama_lengkap ?></td>
                        <td class="px-6 py-4"><?= $item->jabatan ?></td>
                        <td class="px-6 py-4"><?= $item->total_hadir ?></td>
                        <td class="px-6 py-4"><?= $item->total_terlambat ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#laporanTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' }
    });
});
</script>
