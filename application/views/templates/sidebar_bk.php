<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('bk/dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Portal BK</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('bk/dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Bimbingan Konseling
    </div>

    <!-- Nav Item - Monitoring -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'monitoring') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('bk/monitoring') ?>">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Monitoring Siswa</span></a>
    </li>

    <!-- Nav Item - Surat -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'surat') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('bk/surat') ?>">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Surat Panggilan</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Akun
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item <?= ($this->uri->segment(2) == 'profile') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('bk/profile') ?>">
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
