<div id="wrapper">

  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav metismenu" id="side-menu">
        <?= $this->load->view('Fragment/SidebarHeaderFragmentPendaftar', NULL, TRUE); ?>
        <li id="dashboard">
          <a href="<?= base_url('pendaftar/') ?>"><i class="fa fa-home"></i> <span class="nav-label">Data Diri</span></a>
        </li>
        <li id="daftar-layanan">
          <a href="<?= base_url('pendaftar/daftar-layanan') ?>"><i class="fa fa-comments"></i> <span class="nav-label">Daftar Layanan</span></a>
        </li>
        <li id="riwayat">
          <a href="<?= base_url('pendaftar/riwayat') ?>"><i class="fa fa-comments"></i> <span class="nav-label">Riwayat Ujian</span></a>
        </li>
        <li id="logout">
          <a href="<?= base_url('AdminController') ?>" class="logout"><i class="fa fa-sign-out"></i> <span class="nav-label">Logout</span></a>
        </li>
        </li>
    </div>
  </nav>
  <script>
    $(document).ready(function() {});
  </script>