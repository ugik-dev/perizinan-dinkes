<div id="wrapper">

  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
      <ul class="nav metismenu" id="side-menu">
        <?= $this->load->view('Fragment/SidebarHeaderFragment', NULL, TRUE); ?>
        <li id="dashboard">
          <a href="<?= base_url('/') ?>"><i class="fa fa-home"></i> <span class="nav-label">Beranda</span></a>
        </li>
        <li id="kelolahsiswa">
          <a href="<?= base_url('kabid/hasil_ujian') ?>"><i class="fa fa-user"></i><span class="nav-label">Hasil Ujian</span></a>
        </li>
        <li id="logout">
          <a href="<?= base_url('UserController') ?>" class="logout"><i class="fa fa-sign-out"></i> <span class="nav-label">Logout</span></a>
        </li>
        </li>
      </ul>
    </div>
  </nav>
  <script>
    $(document).ready(function() {});
  </script>