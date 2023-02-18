<div class="container">

    <div class="wrapperwrapper-content animated fadeInRight">
        <div class="ibox section-container">
            <div class="ibox-content">
                <div class="row">
                    <!-- Earnings (Monthly) Card Example -->
                    <?php
                    $colors = ['primary', 'success', 'warning', 'info', 'danger'];
                    $c = 0;
                    foreach ($dataContent['perizinan'] as $p) {
                        $c++;
                        if ($c == 5) $c = 0;
                    ?>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <?php
                                if ($p['hasil'] == 'Y') {
                                    echo '<div data-token="' . $p['token'] . '" class="buka_sertifikat card-body">';
                                } else if ($p['hasil'] == 'W') {
                                    echo '<div data-token="' . $p['token'] . '" class="lanjut_ujian card-body">';
                                } else {
                                    echo '<div data-id="' . $p['id_perizinan'] . '" class="register card-body">';
                                }
                                ?>
                                <!-- <div data-id='1' class="register card-body"> -->
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-<?= $colors[$c] ?> text-uppercase mb-1">
                                            <?= $p['nama_perizinan'] ?></div>
                                        <?php
                                        if ($p['hasil'] == 'Y') {
                                            echo '<div class="p mb-0 font-weight-bold text-gray-800">*lihat sertifikat</div>';
                                        } else if ($p['hasil'] == 'W') {
                                            echo '<div class="p mb-0 font-weight-bold text-gray-800">*ujian sedang berjalan, klik untuk melanjutkan</div>';
                                        } else {
                                            echo '<div class="p mb-0 font-weight-bold text-gray-800">*klik untuk mendaftar</div>';
                                        }
                                        ?>
                                        <!-- <div class="p mb-0 font-weight-bold text-gray-800">*klik untuk mendaftar</div> -->
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-<?= $p['icon'] ?>  fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="daftar_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Form Daftar</h4>
                <span class="info"></span>
            </div>
            <div class="modal-body" id="modal-body">
                <form role="form" id="daftar_form" onsubmit="return false;" type="multipart" autocomplete="off">
                    <!-- <input type="hidden" id="id_" name="id_session_exam"> -->
                    <input type="hidden" id="id_perizinan" name="id_perizinan">

                    <div class="form-group">
                        <label for="nama_badan">Nama Badan / Toko / Usaha <small>*jika ada</small></label>
                        <input type="text" class="form-control" id="nama_badan" name="nama_badan">
                    </div>
                    <div class="form-group">
                        <label for="alamat_badan">Alamat Badan / Toko / Usaha <small>*jika ada</small></label>
                        <textarea type="text" class="form-control" id="alamat_badan" name="alamat_badan"></textarea>
                    </div>
                    <button class="btn btn-success my-1 mr-sm-2" type="submit" id="daftar_btn" data-loading-text="Loading..." onclick="this.form.target='edit'"><strong>Daftar Sekarang</strong></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#daftar-layanan').addClass('active');

        var DaftarModal = {
            'self': $('#daftar_modal'),
            'info': $('#daftar_modal').find('.info'),
            'form': $('#daftar_modal').find('#daftar_form'),
            'daftar_btn': $('#daftar_modal').find('#daftar_btn'),
            'id_perizinan': $('#daftar_modal').find('#id_perizinan'),
            'nama_badan': $('#daftar_modal').find('#nama_badan'),
            'alamat_badan': $('#daftar_modal').find('#alamat_badan'),
        }

        $('.register').on('click', function() {
            var id = $(this).data('id');
            console.log('s' + id);
            DaftarModal.id_perizinan.val(id)
            // DaftarModal.self.modal('show');
            DaftarModal.form.submit();
        });
        $('.buka_sertifikat').on('click', function() {
            var token = $(this).data('token');

            location.href = "<?= base_url() ?>sertifikat/" + token;

        });
        $('.lanjut_ujian').on('click', function() {
            var token = $(this).data('token');
            swal({
                title: "Konfirmasi",
                text: "Yakin akan melanjutkan ujian? anda akan langsung diarahkan ke halaman ujian",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#18a689",
                confirmButtonText: "Ya, Lanjutkan!",
            }).then((result) => {
                if (!result.value) {
                    return;
                }
                swalLoading();

                location.href = "<?= base_url() ?>pendaftar/ujian/" + token;

            });
        });


        DaftarModal.form.on('submit', (ev) => {
            ev.preventDefault();
            console.log('sub')
            swal({
                title: "Konfirmasi",
                text: "Yakin akan memulai ujian? anda akan langsung diarahkan ke halaman ujian",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#18a689",
                confirmButtonText: "Ya, Mulai Sekarang!",
            }).then((result) => {
                if (!result.value) {
                    return;
                }
                swalLoading();

                $.ajax({
                    url: "<?= site_url('Pendaftar/daftarSessionExam') ?>",
                    'type': 'POST',
                    data: DaftarModal.form.serialize(),
                    success: function(data) {
                        swal.close();
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Gagal", json['message'], "error");
                            return;
                        }
                        // delete dataAvaliabeExam[id];
                        swal("Berhasil", "", "success");
                        // location.reload();
                        window.location.href = '<?= base_url() ?>pendaftar/ujian/' + json['data'];
                        // renderAvaliabeExam(dataAvaliabeExam);
                    },
                    error: function(e) {}
                });
            });
        })

    })
</script>