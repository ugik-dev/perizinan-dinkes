<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox ssection-container">
        <div class="ibox-content">
            <form class="form-inline" id="toolbar_form" onsubmit="return false;">
                <!-- <select class="form-control mr-sm-2" name="id_perizinan" id="id_perizinan"></select> -->

                <!-- <input type="hidden" placeholder="" class="form-control" id="id_role" name="id_role" value="3"> -->
                <input type="text" placeholder="Search" class="form-control my-1 mr-sm-2" id="search" name="search">

                <button type="submit" class="btn btn-success my-1 mr-sm-2" id="show_btn" data-loading-text="Loading..." onclick="this.form.target='show'"><i class="fal fa-search"></i> Cari</button>
                <button type="submit" class="btn btn-primary my-1 mr-sm-2" id="add_btn" data-loading-text="Loading..." onclick="this.form.target='add'"><i class="fal fa-plus"></i> Tambah</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="FDataTable" class="table table-bordered table-hover" style="padding:0px">
                            <thead>
                                <tr>

                                    <th style="width: 15%; text-align:center!important">Jadwal</th>
                                    <th style="width: 12%; text-align:center!important">Nama</th>
                                    <th style="width: 7%; text-align:center!important">Perizinan</th>
                                    <th style="width: 7%; text-align:center!important">Hasil Ujian</th>
                                    <th style="width: 7%; text-align:center!important">Status Approval</th>
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


<div class="modal inmodal" id="bank_soal_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Form Jadwal</h4>
                <span class="info"></span>
            </div>
            <div class="modal-body" id="modal-body">
                <form role="form" id="bank_soal_form" onsubmit="return false;" type="multipart" autocomplete="off">
                    <input type="hidden" id="id_session_exam" name="id_session_exam">
                    <input type="hidden" class="form-control mr-sm-2" name="poin_mode" id="poin_mode" value="poin">

                    <input type="hidden" class="form-control mr-sm-2" name="id_perizinan" id="id_perizinan" value="1">
                    <!-- <div class="form-group">
                        <label for="nama">Mata Pelajaran</label>
                    </div> -->
                    <div class="form-group">
                        <label for="nama">Nama Jadwal</label>
                        <input type="text" placeholder="" class="form-control" id="name_session_exam" name="name_session_exam" required="required"></input>
                    </div>
                    <!-- <div class="form-group">
                        <label for="nama">Score</label>
                        <select class="form-control mr-sm-2" name="poin_mode" id="poin_mode">
                            <option value="avg"> Akumukasi 100</option>
                            <option value="poin"> Poin</option>
                        </select>
                    </div> -->
                    <label for="open_start">Waktu Pengerjaan Mengerjakan</label>
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="open_start"> Dari</label>
                                    <input type="datetime-local" placeholder="" class="form-control" id="open_start" name="open_start" required="required"></input>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="open_start"> Sampai</label>
                                    <input type="datetime-local" placeholder="" class="form-control" id="open_end" name="open_end" required="required"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Waktu Ujian (Menit)</label>
                        <input type="number" placeholder="" class="form-control" id="limit_time" name="limit_time" required="required"></input>
                    </div>
                    <!-- <div class="form-group">
                        <label for="password">Jumlah Soal</label>
                        <input type="number" placeholder="" class="form-control" id="limit_soal" name="limit_soal" required="required"></input>
                    </div> -->

                    <button class="btn btn-success my-1 mr-sm-2" type="submit" id="add_btn" data-loading-text="Loading..." onclick="this.form.target='add'"><strong>Tambah Data</strong></button>
                    <button class="btn btn-success my-1 mr-sm-2" type="submit" id="save_edit_btn" data-loading-text="Loading..." onclick="this.form.target='edit'"><strong>Simpan Perubahan</strong></button>
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

        $('#open_session').addClass('active');

        var toolbar = {
            'form': $('#toolbar_form'),
            'showBtn': $('#show_btn'),
            'addBtn': $('#show_btn'),
            'id_perizinan': $('#toolbar_form').find('#id_perizinan'),
            'search': $('#toolbar_form').find('#search'),
        }

        var FDataTable = $('#FDataTable').DataTable({
            'columnDefs': [],
            deferRender: true,
            "order": [
                [0, "desc"]
            ]
        });

        var SessionModal = {
            'self': $('#bank_soal_modal'),
            'info': $('#bank_soal_modal').find('.info'),
            'form': $('#bank_soal_modal').find('#bank_soal_form'),
            'addBtn': $('#bank_soal_modal').find('#add_btn'),
            'saveEditBtn': $('#bank_soal_modal').find('#save_edit_btn'),
            'id_session_exam': $('#bank_soal_modal').find('#id_session_exam'),
            'id_perizinan': $('#bank_soal_modal').find('#id_perizinan'),
            'name_session_exam': $('#bank_soal_modal').find('#name_session_exam'),
            'open_start': $('#bank_soal_modal').find('#open_start'),
            'open_end': $('#bank_soal_modal').find('#open_end'),
            'limit_soal': $('#bank_soal_modal').find('#limit_soal'),
            'limit_time': $('#bank_soal_modal').find('#limit_time'),
            'poin_mode': $('#bank_soal_modal').find('#poin_mode'),
        }

        // getAllMapel();
        swalLoading();
        $.when(getAllSession()).done(function(a1, a2, a3) {
            swal.close();
        });

        var swalSaveConfigure = {
            title: "Konfirmasi simpan",
            text: "Yakin akan menyimpan data ini?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#18a689",
            confirmButtonText: "Ya, Simpan!",
        };

        var swalApprovConf = {
            title: "Konfirmasi Approv",
            text: "Yakin akan Approv data ini?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#49be25",
            confirmButtonText: "Ya, Approv!",
        };

        var swalDeapprovConf = {
            title: "Konfirmasi Tolak",
            text: "Yakin akan Tolak data ini?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#be252b",
            confirmButtonText: "Ya, Tolak!",
        };

        var swalUndoConf = {
            title: "Konfirmasi Pembatalan",
            text: "Yakin akan Membatalkan Aksi data ini?",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#49be25",
            confirmButtonText: "Ya, Membatalkan Aksi!",
        };

        var dataSessionExam = {};
        var dataRole = {};

        toolbar.form.submit(function(event) {
            event.preventDefault();
            switch (toolbar.form[0].target) {
                case 'show':
                    getAllSession();
                    break;
                case 'add':
                    showSessionModal();
                    break;
            }
        });

        // getAllSession()

        function getAllSession() {
            return $.ajax({
                url: `<?php echo site_url('AdminController/getAllSession/') ?>`,
                'type': 'POST',
                data: toolbar.form.serialize(),
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataSessionExam = json['data'];
                    renderKelolahbank_soal(dataSessionExam);
                },
                error: function(e) {}
            });
        }




        function renderKelolahbank_soal(data) {
            if (data == null || typeof data != "object") {
                console.log("User::UNKNOWN DATA");
                return;
            }

            var i = 0;
            var ses = <?= json_encode($this->session->userdata()) ?>;
            var renderData = [];
            console.log(ses);
            Object.values(data).forEach((exam) => {
                btn_appv = ``;
                if (ses['nama_role'] == "kasi") {
                    if (exam['status_approval'] == 1) {
                        btn_appv = `
                        <a class="approv dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-check text-success'> Approv</i> </a>
                        <a class="unapprov dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-times text-danger'> Tolak Approv</i></a>
                        `;
                    } else if (exam['status_approval'] == 2) {
                        btn_appv = `
                        <a class="undo dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-undo text-danger'> Batal Aksi</i> </a>
                        `;
                    }
                }

                if (ses['nama_role'] == "kabid") {
                    if (exam['status_approval'] == 2) {
                        btn_appv = `
                        <a class="approv dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-check text-success'> Approv</i> </a>
                        <a class="unapprov dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-times text-danger'> Tolak Approv</i></a>
                        `;
                    } else if (exam['status_approval'] == 3) {
                        btn_appv = `
                        <a class="undo dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-undo text-danger'> Batal Aksi</i> </a>
                        `;
                    }
                }


                if (ses['nama_role'] == "sekretaris") {
                    if (exam['status_approval'] == 3) {
                        btn_appv = `
                        <a class="approv dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-check text-success'> Approv</i> </a>
                        <a class="unapprov dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-times text-danger'> Tolak Approv</i></a>
                        `;
                    } else if (exam['status_approval'] == 4) {
                        btn_appv = `
                        <a class="undo dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-undo text-danger'> Batal Aksi</i> </a>
                        `;
                    }
                }

                if (ses['nama_role'] == "kadin") {
                    if (exam['status_approval'] == 4) {
                        btn_appv = `
                        <a class="approv dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-check text-success'> Approv</i> </a>
                        <a class="unapprov dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-times text-danger'> Tolak Approv</i></a>
                        `;
                    } else if (exam['status_approval'] == 5) {
                        btn_appv = `
                        <a class="undo dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-undo text-danger'> Batal Aksi</i> </a>
                        `;
                    }
                }
                if (exam['unapprove'] == ses['id_user'])
                    btn_appv = `
                        <a class="undo dropdown-item" data-id='${exam['id_session_exam_user']}'><i class='fa fa-undo text-danger'> Batal Aksi</i> </a>
                        `;

                var detailButton = `
                        <a class="cetak dropdown-item" data-id='${exam['token']}'><i class='fa fa-print'></i> Lihat Sertifikat</a>
                        `;

                var button = `
                        <div class="btn-group" role="group">
                            <button id="action" type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class='fa fa-bars'></i></button>
                            <div class="dropdown-menu" aria-labelledby="action">
                                ${btn_appv}
                                ${detailButton}
                            </div>
                        </div>
                        `;

                renderData.push([exam['start_time'], exam['nama'], exam['nama_perizinan'], span_status(exam['hasil']) + '<br> Skor :' + exam['score'], exam['hasil'] == 'Y' ? span_approval(exam['status_approval'], exam['unapprove']) : '-', button]);
            });
            FDataTable.clear().rows.add(renderData).draw('full-hold');
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
        FDataTable.on('click', '.edit', function() {
            event.preventDefault();
            SessionModal.form.trigger('reset');
            SessionModal.addBtn.hide();
            SessionModal.saveEditBtn.show();
            var id = $(this).data('id');
            var exam = dataSessionExam[id];
            SessionModal.id_session_exam.val(exam['id_session_exam']);
            // SessionModal.id_perizinan.val(1);
            SessionModal.open_start.val(exam['open_start'].replace(' ', 'T'));
            SessionModal.open_end.val(exam['open_end'].replace(' ', 'T'));
            SessionModal.name_session_exam.val(exam['name_session_exam']);
            SessionModal.limit_soal.val(exam['limit_soal']);

            SessionModal.poin_mode.val(exam['poin_mode']);
            SessionModal.limit_time.val(exam['limit_time']);
            SessionModal.self.modal('show');
        });

        FDataTable.on('click', '.cetak', function() {
            var id = $(this).data('id');
            console.log('<?= base_url() ?>sertifikat/' + id);
            window.open('<?= base_url() ?>sertifikat/' + id, '_blank');
        });

        FDataTable.on('click', '.approv', function() {
            event.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            swal(swalApprovConf).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= site_url('AdminController/approv') ?>",
                    'type': 'POST',
                    data: {
                        'id_session_exam_user': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Approv Gagal", json['message'], "error");
                            return;
                        }
                        dataSessionExam[id] = json['data'];
                        swal("Approv Berhasil", "", "success");
                        renderKelolahbank_soal(dataSessionExam);
                    },
                    error: function(e) {}
                });
            });
        });

        FDataTable.on('click', '.unapprov', function() {
            event.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            swal(swalDeapprovConf).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= site_url('AdminController/unapprov') ?>",
                    'type': 'POST',
                    data: {
                        'id_session_exam_user': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Approv Gagal", json['message'], "error");
                            return;
                        }
                        dataSessionExam[id] = json['data'];
                        swal("Approv Berhasil", "", "success");
                        renderKelolahbank_soal(dataSessionExam);
                    },
                    error: function(e) {}
                });
            });
        });

        FDataTable.on('click', '.undo', function() {
            event.preventDefault();
            var id = $(this).data('id');
            console.log(id)
            swal(swalUndoConf).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= site_url('AdminController/undo') ?>",
                    'type': 'POST',
                    data: {
                        'id_session_exam_user': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Batalkan Aksi Gagal", json['message'], "error");
                            return;
                        }
                        dataSessionExam[id] = json['data'];
                        swal("Batalkan Aksi Berhasil", "", "success");
                        renderKelolahbank_soal(dataSessionExam);
                    },
                    error: function(e) {}
                });
            });
        });

        function showSessionModal() {
            SessionModal.self.modal('show');
            SessionModal.addBtn.show();
            SessionModal.saveEditBtn.hide();
            SessionModal.form.trigger('reset');
            // SessionModal.id_perizinan.val(toolbar.id_perizinan.val());
        }

        SessionModal.form.submit(function(event) {
            event.preventDefault();
            switch (SessionModal.form[0].target) {
                case 'add':
                    addKelolahbank_soal();
                    break;
                case 'edit':
                    editKelolahbank_soal();
                    break;
            }
        });

        function addKelolahbank_soal() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(SessionModal.addBtn);
                $.ajax({
                    url: `<?= site_url('AdminController/addSessionExam') ?>`,
                    'type': 'POST',
                    data: SessionModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(SessionModal.addBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var exam = json['data']
                        dataSessionExam[exam['id_session_exam']] = exam;
                        swal("Simpan Berhasil", "", "success");
                        renderKelolahbank_soal(dataSessionExam);
                        SessionModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }

        function editKelolahbank_soal() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(SessionModal.saveEditBtn);
                $.ajax({
                    url: `<?= site_url('AdminController/editSessionExam') ?>`,
                    'type': 'POST',
                    data: SessionModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(SessionModal.saveEditBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var exam = json['data']
                        dataSessionExam[exam['id_session_exam']] = exam;
                        swal("Simpan Berhasil", "", "success");
                        renderKelolahbank_soal(dataSessionExam);
                        SessionModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }
    });
</script>