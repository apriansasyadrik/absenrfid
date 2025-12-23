<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Jadwal Mengajar</h1>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" class="form-inline">
                <label class="mr-2">Semester:</label>
                <select name="semester" class="form-control mr-3" onchange="this.form.submit()">
                    <option value="">Semua Semester</option>
                    <?php if(isset($semesters)): foreach($semesters as $sem): ?>
                        <option value="<?= $sem->id ?>" <?= (isset($_GET['semester']) && $_GET['semester'] == $sem->id) ? 'selected' : '' ?>>
                            <?= $sem->nama_semester ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Schedule by Day -->
    <?php 
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
    ?>

    <?php foreach($days as $index => $day): ?>
        <?php 
        $day_schedules = isset($schedules) ? array_filter($schedules, function($s) use ($day) {
            return $s->hari == $day;
        }) : [];
        ?>

        <?php if(count($day_schedules) > 0): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-<?= $colors[$index] ?> text-white">
                    <h6 class="m-0 font-weight-bold"><?= $day ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="15%">Jam</th>
                                    <th width="25%">Mata Pelajaran</th>
                                    <th width="20%">Kelas</th>
                                    <th width="15%">Ruangan</th>
                                    <th width="25%">Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($day_schedules as $schedule): ?>
                                    <tr>
                                        <td><?= $schedule->jam_mulai ?> - <?= $schedule->jam_selesai ?></td>
                                        <td>
                                            <strong><?= $schedule->nama_mapel ?></strong>
                                        </td>
                                        <td><?= $schedule->tingkat ?> <?= $schedule->nama_kelas ?></td>
                                        <td>
                                            <?= $schedule->ruangan ?: '<span class="text-muted">-</span>' ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= $schedule->nama_semester ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if(!isset($schedules) || count($schedules) == 0): ?>
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada jadwal mengajar</p>
            </div>
        </div>
    <?php endif; ?>

</div>
<!-- /.container-fluid -->
