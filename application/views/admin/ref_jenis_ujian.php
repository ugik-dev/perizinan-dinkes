<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox ssection-container">
        <div class="ibox-content">
            <form class="form-inline" id="toolbar_form" onsubmit="return false;">
                <input type="hidden" placeholder="nama_perizinan / NIK" class="form-control my-1 mr-sm-2" id="search" name="search">
                <input type="hidden" class="form-control my-1 mr-sm-2" id="ex_role" name="ex_role" value="4">

                <button hidden type="submit" class="btn btn-success my-1 mr-sm-2" id="show_btn" data-loading-text="Loading..." onclick="this.form.target='show'"><i class="fal fa-search"></i> Cari</button>
                <button type="submit" class="btn btn-primary my-1 mr-sm-2" id="add_btn" data-loading-text="Loading..." onclick="this.form.target='add'"><i class="fa fa-plus"></i> Tambah</button>
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

                                    <th style="width: 15%; text-align:center!important">ID</th>
                                    <th style="width: 12%; text-align:center!important">Nama</th>
                                    <th style="width: 3%; text-align:center!important">Durasi</th>
                                    <th style="width: 3%; text-align:center!important">Jumlah Soal</th>
                                    <th style="width: 3%; text-align:center!important">Passing Grade</th>
                                    <th style="width: 3%; text-align:center!important">Peraturan</th>
                                    <th style="width: 3%; text-align:center!important">SKTIM</th>
                                    <th style="width: 3%; text-align:center!important">Status</th>
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


<div class="modal inmodal" id="user_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Form Perizinan</h4>
                <span class="info"></span>
            </div>
            <div class="modal-body" id="modal-body">
                <form role="form" id="user_form" onsubmit="return false;" type="multipart" autocomplete="off">
                    <input type="hidden" id="id_perizinan" name="id_perizinan">

                    <div class="form-group">
                        <label for="nama_perizinan">Nama Perizinan</label>
                        <input type="text" placeholder="" class="form-control" id="nama_perizinan" name="nama_perizinan" required="required">
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="durasi">Durasi</label>
                                <input type="number" placeholder="" class="form-control" id="durasi" name="durasi" required="required">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="jml_soal">Jumlah Soal</label>
                                <input type="number" placeholder="" class="form-control" id="jml_soal" name="jml_soal" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="passinggrade">Passing Grade</label>
                                <input type="number" placeholder="" class="form-control" id="passinggrade" name="passinggrade" required="required">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <select class="form-control mr-sm-2" name="icon" id="icon" required="required">
                                    <option value="home">Home</option>
                                    <option value="cutlery">cutlery</option>
                                    <option value="tint">tint</option>
                                    <option value="bed">bed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="menkes_active">Peraturan</label>
                        <select class="form-control mr-sm-2" name="menkes_active" id="menkes_active" required="required"></select>
                    </div>

                    <div class="form-group">
                        <label for="sktim_active">SK Tim</label>
                        <select class="form-control mr-sm-2" name="sktim_active" id="sktim_active" required="required"></select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control mr-sm-2" name="status" id="status" required="required">
                            <option value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                        </select>
                    </div>
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

        $('#setting_parm').addClass('active');
        $('#ref_jenis_ujian').addClass('active');


        var toolbar = {
            'form': $('#toolbar_form'),
            'showBtn': $('#show_btn'),
            'addBtn': $('#show_btn'),
        }

        var FDataTable = $('#FDataTable').DataTable({
            'columnDefs': [],
            deferRender: true,
            "order": [
                [0, "desc"]
            ]
        });

        var RefPerizinanModal = {
            'self': $('#user_modal'),
            'info': $('#user_modal').find('.info'),
            'form': $('#user_modal').find('#user_form'),
            'addBtn': $('#user_modal').find('#add_btn'),
            'saveEditBtn': $('#user_modal').find('#save_edit_btn'),
            'id_perizinan': $('#user_modal').find('#id_perizinan'),
            'nama_perizinan': $('#user_modal').find('#nama_perizinan'),
            'durasi': $('#user_modal').find('#durasi'),
            'menkes_active': $('#user_modal').find('#menkes_active'),
            'sktim_active': $('#user_modal').find('#sktim_active'),
            'status': $('#user_modal').find('#status'),
            'jml_soal': $('#user_modal').find('#jml_soal'),
            'passinggrade': $('#user_modal').find('#passinggrade'),
            'icon': $('#user_modal').find('#icon'),
        }


        var swalSaveConfigure = {
            title: "Konfirmasi simpan",
            text: "Yakin akan menyimpan data ini?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#18a689",
            confirmButtonText: "Ya, Simpan!",
        };

        var swalDeleteConfigure = {
            title: "Konfirmasi hapus",
            text: "Yakin akan menghapus data ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Hapus!",
        };

        var dataMaster = {};
        var dataRole = {};

        toolbar.form.submit(function(event) {
            event.preventDefault();
            switch (toolbar.form[0].target) {
                case 'show':
                    getAllRefPerizinan();
                    break;
                case 'add':
                    showRefPerizinanModal();
                    break;
            }
        });

        getAllMenkes();

        function getAllMenkes() {
            return $.ajax({
                url: `<?php echo base_url('MasterController/getAllPeraturan/') ?>`,
                'type': 'GET',
                data: {},
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataRole = json['data'];
                    renderPeraturanSelection(dataRole);
                },
                error: function(e) {}
            });
        }

        function renderPeraturanSelection(data) {
            RefPerizinanModal.menkes_active.empty();
            RefPerizinanModal.menkes_active.append($('<option>', {
                value: "",
                text: "---"
            }));
            Object.values(data).forEach((d) => {
                RefPerizinanModal.menkes_active.append($('<option>', {
                    value: d['menkes_id'],
                    text: d['menkes_nama'] + ' :: ' + d['menkes_tentang'],
                }));
            });
        }
        getAllSKTim();

        function getAllSKTim() {
            return $.ajax({
                url: `<?php echo base_url('MasterController/getAllSKTim/') ?>`,
                'type': 'GET',
                data: {},
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataRole = json['data'];
                    renderSKTIMSelection(dataRole);
                },
                error: function(e) {}
            });
        }

        function renderSKTIMSelection(data) {
            RefPerizinanModal.sktim_active.empty();
            RefPerizinanModal.sktim_active.append($('<option>', {
                value: "",
                text: "---"
            }));
            Object.values(data).forEach((d) => {
                RefPerizinanModal.sktim_active.append($('<option>', {
                    value: d['sktim_id'],
                    text: d['sktim_nama'],
                }));
            });
        }
        getAllRefPerizinan();

        function getAllRefPerizinan() {
            return $.ajax({
                url: `<?php echo base_url('MasterController/getAllPerizinan/') ?>`,
                'type': 'POST',
                data: toolbar.form.serialize(),
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataMaster = json['data'];
                    renderRefPerizinan(dataMaster);
                },
                error: function(e) {}
            });
        }

        function renderRefPerizinan(data) {
            if (data == null || typeof data != "object") {
                console.log("User::UNKNOWN DATA");
                return;
            }

            var i = 0;

            var renderData = [];
            Object.values(data).forEach((d) => {

                var editButton = `
        <a class="edit dropdown-item" data-id='${d['id_perizinan']}'><i class='fa fa-pencil'></i> Edit </a>
      `;
                var deleteButton = `
        <a class="delete dropdown-item" data-id='${d['id_perizinan']}'><i class='fa fa-trash'></i> Hapus </a>
      `;
                var button = `
        <div class="btn-group" role="group">
          <button id="action" type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class='fa fa-bars'></i></button>
          <div class="dropdown-menu" aria-labelledby="action">
            ${editButton}
            ${deleteButton}
          </div>
        </div>
      `;
                renderData.push([d['id_perizinan'], d['nama_perizinan'], d['durasi'], d['jml_soal'], d['passinggrade'], d['menkes_nama'] + '<br><br>Tentang : ' + d['menkes_tentang'], d['sktim_nama'], d['status'] == '1' ? 'Aktif' : 'Tidak Aktif', button]);
            });
            FDataTable.clear().rows.add(renderData).draw('full-hold');
        }


        FDataTable.on('click', '.edit', function() {
            event.preventDefault();
            RefPerizinanModal.form.trigger('reset');
            RefPerizinanModal.self.modal('show');
            RefPerizinanModal.addBtn.hide();
            RefPerizinanModal.saveEditBtn.show();
            var id = $(this).data('id');
            var d = dataMaster[id];
            console.log(d);
            RefPerizinanModal.id_perizinan.val(d['id_perizinan']);
            RefPerizinanModal.nama_perizinan.val(d['nama_perizinan']);
            RefPerizinanModal.durasi.val(d['durasi']);
            RefPerizinanModal.jml_soal.val(d['jml_soal']);
            RefPerizinanModal.menkes_active.val(d['menkes_active']);
            RefPerizinanModal.sktim_active.val(d['sktim_active']);
            RefPerizinanModal.icon.val(d['icon']);
            RefPerizinanModal.passinggrade.val(d['passinggrade']);
            RefPerizinanModal.status.val(d['status']);

        });


        FDataTable.on('click', '.delete', function() {
            event.preventDefault();
            var id = $(this).data('id');
            swal(swalDeleteConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= base_url('MasterController/deletePerizinan') ?>",
                    'type': 'POST',
                    data: {
                        'id_perizinan': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Delete Gagal", json['message'], "error");
                            return;
                        }
                        delete dataMaster[id];
                        swal("Delete Berhasil", "", "success");
                        renderRefPerizinan(dataMaster);
                    },
                    error: function(e) {}
                });
            });
        });

        function showRefPerizinanModal() {
            RefPerizinanModal.self.modal('show');
            RefPerizinanModal.addBtn.show();
            RefPerizinanModal.saveEditBtn.hide();
            RefPerizinanModal.form.trigger('reset');
        }

        RefPerizinanModal.form.submit(function(event) {
            event.preventDefault();
            switch (RefPerizinanModal.form[0].target) {
                case 'add':
                    addRefPerizinan();
                    break;
                case 'edit':
                    editRefPerizinan();
                    break;
            }
        });

        function addRefPerizinan() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefPerizinanModal.addBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/addPerizinan') ?>`,
                    'type': 'POST',
                    data: RefPerizinanModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefPerizinanModal.addBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['id_perizinan']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefPerizinan(dataMaster);
                        RefPerizinanModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }

        function editRefPerizinan() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefPerizinanModal.saveEditBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/editPerizinan') ?>`,
                    'type': 'POST',
                    data: RefPerizinanModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefPerizinanModal.saveEditBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['id_perizinan']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefPerizinan(dataMaster);
                        RefPerizinanModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }
    });
</script>