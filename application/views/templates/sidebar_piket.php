<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('piket/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Portal Piket</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'piket' && ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '')) ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('piket/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Guru Piket
    </div>

    <!-- Nav Item - Izin KBM -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'izin-kbm') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('piket/izin-kbm') ?>">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Izin KBM</span></a>
    </li>

    <!-- Nav Item - Rekap -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'rekap') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('piket/rekap') ?>">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Rekap Izin</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Mengajar
    </div>

    <!-- Nav Item - Jadwal -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'guru' && $this->uri->segment(2) == 'jadwal') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('guru/jadwal') ?>">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Jadwal Mengajar</span></a>
    </li>

    <!-- Nav Item - Jurnal -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'guru' && $this->uri->segment(2) == 'jurnal') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('guru/jurnal') ?>">
            <i class="fas fa-fw fa-book"></i>
            <span>Jurnal & Absensi</span></a>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'guru' && $this->uri->segment(2) == 'laporan') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('guru/laporan') ?>">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Laporan Kinerja</span></a>
    </li>

    <?php 
    // Check if user role includes walikelas
    $role = $this->session->userdata('role');
    $is_walikelas = ($role == 'walikelas');
    ?>

    <?php if ($is_walikelas): ?>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Wali Kelas
    </div>

    <!-- Nav Item - Input Sakit/Izin -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'walikelas' && $this->uri->segment(2) == 'izin-siswa') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('walikelas/izin-siswa') ?>">
            <i class="fas fa-fw fa-file-medical"></i>
            <span>Input Sakit/Izin</span></a>
    </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Akun
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item <?= ($this->uri->segment(1) == 'guru' && $this->uri->segment(2) == 'profile') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('guru/profile') ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Profile</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
