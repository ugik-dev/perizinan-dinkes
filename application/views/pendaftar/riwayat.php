<div class="wrapper wrapper-content animated fadeInRight">
    <!-- <div class="ibox ssection-container">
        <div class="ibox-content">
            <form class="form-inline" id="toolbar_form" onsubmit="return false;">
                <select class="form-control mr-sm-2" name="id_perizinan" id="id_perizinan" required></select>
                <input type="text" placeholder="Search" class="form-control my-1 mr-sm-2" id="search" name="search">

                <button type="submit" class="btn btn-success my-1 mr-sm-2" id="show_btn" data-loading-text="Loading..." onclick="this.form.target='show'"><i class="fal fa-search"></i> Cari</button>
                <button type="submit" class="btn btn-primary my-1 mr-sm-2" id="add_btn" data-loading-text="Loading..." onclick="this.form.target='add'"><i class="fal fa-plus"></i> Tambah</button>
            </form>
        </div>
    </div> -->

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <!-- <h1>Avaliable Exam</h1> -->
                        <table id="FDataTable" class="table table-bordered table-hover" style="padding:0px">
                            <thead>
                                <tr>
                                    <th style="width: 15%; text-align:center!important">Waktu Pengerjaan</th>
                                    <th style="width: 12%; text-align:center!important">Nama</th>
                                    <th style="width: 12%; text-align:center!important">Hasil Ujian</th>
                                    <th style="width: 12%; text-align:center!important">Score</th>
                                    <th style="width: 12%; text-align:center!important">Status Approval</th>
                                    <th style="width: 7%; text-align:center!important">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script>
    $(document).ready(function() {

        $('#riwayat').addClass('active');

        var DaftarModal = {
            'self': $('#daftar_modal'),
            'info': $('#daftar_modal').find('.info'),
            'form': $('#daftar_modal').find('#daftar_form'),
            'daftar_btn': $('#daftar_modal').find('#daftar_btn'),
            'id_exam': $('#daftar_modal').find('#id_exam'),
            'req_jurusan': $('#daftar_modal').find('#req_jurusan'),
            'req_jurusan_2': $('#daftar_modal').find('#req_jurusan_2'),
        }
        var toolbar = {
            'form': $('#toolbar_form'),
            'showBtn': $('#show_btn'),
            'addBtn': $('#show_btn'),
            'id_perizinan': $('#toolbar_form').find('#id_perizinan'),
            'search': $('#toolbar_form').find('#search'),
        }

        var FDataTable = $('#FDataTable').DataTable({
            'columnDefs': [],
            deferRender: false,
            "order": false,
            'search': false,
            "paging": false,
            'info': false
        });


        var FDataTable2 = $('#FDataTable2').DataTable({
            'columnDefs': [],
            deferRender: true,
            "order": [
                [2, "desc"]
            ]
        });

        var swalSaveConfigure = {
            title: "Konfirmasi",
            text: "Yakin akan memulai ujian?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#18a689",
            confirmButtonText: "Ya, Mulai Sekarang!",
        };

        var swalDaftar = {
            title: "Konfirmasi",
            text: "Yakin akan mendaftar pada ujian ini?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#18a689",
            confirmButtonText: "Ya, Daftar!",
        };


        var dataAvaliabeExam = {};
        var dataRole = {};

        swalLoading();
        $.when(getYourHistory()).done(function(a1, a2, a3) {
            swal.close();
        });



        // function getAllJurusan() {
        //     return $.ajax({
        //         url: `<?php echo site_url('ParameterController/getAllJurusan/') ?>`,
        //         'type': 'get',
        //         data: {},
        //         success: function(data) {
        //             var json = JSON.parse(data);
        //             if (json['error']) {
        //                 return;
        //             }
        //             data = json['data'];
        //             renderJurusan(data);
        //         },
        //         error: function(e) {}
        //     });
        // }

        // function renderJurusan(data) {
        //     DaftarModal.req_jurusan.empty();
        //     DaftarModal.req_jurusan_2.empty();
        //     DaftarModal.req_jurusan.append($('<option>', {
        //         value: "",
        //         text: "-- Pilih --"
        //     }));
        //     DaftarModal.req_jurusan_2.append($('<option>', {
        //         value: "",
        //         text: "-- Pilih --"
        //     }));
        //     Object.values(data).forEach((d) => {
        //         DaftarModal.req_jurusan.append($('<option>', {
        //             value: d['id_jenis_jurusan'],
        //             text: d['nama_jenis_jurusan'],
        //         }));
        //         DaftarModal.req_jurusan_2.append($('<option>', {
        //             value: d['id_jenis_jurusan'],
        //             text: d['nama_jenis_jurusan'],
        //         }));
        //     });
        // }




        function getYourHistory() {
            return $.ajax({
                url: `<?php echo site_url('Pendaftar/getYourHistory/') ?>`,
                'type': 'POST',
                data: toolbar.form.serialize(),
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataHistory = json['data'];
                    renderHistory(dataHistory);
                },
                error: function(e) {}
            });
        }


        function span_status(data) {
            if (data == 'W') {
                return '<i class="fa fa-check text-primary"> Sedang dalam Ujian </i>'
            } else if (data == 'Y') {
                return '<i class="fa fa-check text-success"> Lulus </i>'
            } else {
                return '<i class="fa fa-times text-danger"> Tidak Lulus </i>'
            }
        }



        function span_approval(data, unapv) {
            console.log(unapv);
            if (unapv == '' || unapv == null) {
                if (data == '1') {
                    return '<i class="fa fa-check text-primary"> Menunggu Persetujuan Seksi </i>'
                } else if (data == '2') {
                    return '<i class="fa fa-check text-primary"> Menunggu Persetujuan Kabid </i>'
                } else if (data == '3') {
                    return '<i class="fa fa-check text-primary"> Menunggu Persetujuan Sekretaris</i>'
                } else if (data == '4') {
                    return '<i class="fa fa-check text-primary"> Menunggu Persetujuan Kepala Dinas </i>'
                } else if (data == '5') {
                    return '<i class="fa fa-check text-success">  Selesai </i>'
                } else {
                    return '<i class="fa fa-times text-danger"> - </i>'
                }
            } else {
                if (data == '1') {
                    return '<i class="fa fa-times text-danger"> Ditolak Seksi </i>'
                } else if (data == '2') {
                    return '<i class="fa fa-times text-danger"> Ditolak Kabid </i>'
                } else if (data == '3') {
                    return '<i class="fa fa-times text-danger"> Ditolak Sekretaris</i>'
                } else if (data == '4') {
                    return '<i class="fa fa-times text-danger"> Ditolak Kepala Dinas </i>'
                } else {
                    return '<i class="fa fa-times text-danger"> - </i>'
                }
            }
        }

        function renderHistory(data) {
            if (data == null || typeof data != "object") {
                console.log("User::UNKNOWN DATA");
                return;
            }

            var i = 0;

            var renderData = [];
            Object.values(data).forEach((exam) => {

                //             var detailButton = `
                //   <a class="detail dropdown-item" href='<?= site_url() ?>AdminController/DetailKelolahbank_soal?id_bank_soal=${bank_soal['id_bank_soal']}&nama_bank_soal=${bank_soal['nama']}'><i class='fa fa-share'></i> Detail Desa Wisata</a>
                //   `;
                //             var editButton = `
                //     <a class="edit dropdown-item" data-id='${exam['id_bank_soal']}'><i class='fa fa-pencil'></i> Edit </a>
                //   `;

                //             var button = `
                //     <div class="btn-group" role="group">
                //       <button id="action" type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class='fa fa-bars'></i></button>
                //       <div class="dropdown-menu" aria-labelledby="action">
                //         ${editButton}
                //         ${deleteButton}
                //       </div>
                //     </div>
                //   `;
                if (exam['hasil'] == 'W') {
                    var button = ` <a class="btn btn-primary" href="<?= base_Url() ?>pendaftar/ujian/${exam['token']}"><i class='fa fa-pencil'></i> Lanjutkan Ujian </a `;
                } else if (exam['hasil'] == 'Y') {
                    var button = ` <a class="btn btn-primary" href="<?= base_Url() ?>/sertifikat/${exam['token']}"><i class='fa fa-print'></i> Cetak Sertifikat </a `;
                } else {
                    button = ``;
                }
                renderData.push([exam['start_time'], exam['nama_perizinan'], span_status(exam['hasil']), exam['score'], exam['hasil'] == 'Y' ? span_approval(exam['status_approval']) : '-', button]);
            });
            FDataTable.clear().rows.add(renderData).draw('full-hold');
        }

        FDataTable.on('click', '.start', function() {
            event.preventDefault();
            var token = $(this).data('token');
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }

                window.location.href = '<?= base_url() ?>pendaftar/ujian/' + token;
                // renderAvaliabeExam(dataAvaliabeExam);
            });
        });

        FDataTable.on('click', '.register', function() {
            event.preventDefault();
            var id = $(this).data('id');
            DaftarModal.self.modal('show');
            DaftarModal.id_exam.val(id);
        });

        DaftarModal.form.on('submit', (ev) => {
            ev.preventDefault();
            console.log('sub')
            swal(swalDaftar).then((result) => {
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
                        location.reload();
                        // window.location.href = '<?= base_url() ?>my-task/' + json['data'];
                        // renderAvaliabeExam(dataAvaliabeExam);
                    },
                    error: function(e) {}
                });
            });
        })
    });
</script>