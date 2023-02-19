<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox ssection-container">
        <div class="ibox-content">
            <form class="form-inline" id="toolbar_form" onsubmit="return false;">
                <input type="hidden" placeholder="sktim_nama / NIK" class="form-control my-1 mr-sm-2" id="search" name="search">
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
                <h4 class="modal-title">Form SKTim</h4>
                <span class="info"></span>
            </div>
            <div class="modal-body" id="modal-body">
                <form role="form" id="user_form" onsubmit="return false;" type="multipart" autocomplete="off">
                    <input type="hidden" id="sktim_id" name="sktim_id">

                    <div class="form-group">
                        <label for="sktim_nama">sktim_nama</label>
                        <input type="text" placeholder="" class="form-control" id="sktim_nama" name="sktim_nama" required="required">
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
        $('#ref_sktim').addClass('active');


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

        var RefSKTimModal = {
            'self': $('#user_modal'),
            'info': $('#user_modal').find('.info'),
            'form': $('#user_modal').find('#user_form'),
            'addBtn': $('#user_modal').find('#add_btn'),
            'saveEditBtn': $('#user_modal').find('#save_edit_btn'),
            'sktim_id': $('#user_modal').find('#sktim_id'),
            'sktim_nama': $('#user_modal').find('#sktim_nama'),
            'sktim_tentang': $('#user_modal').find('#sktim_tentang'),
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
                    getAllRefSKTim();
                    break;
                case 'add':
                    showRefSKTimModal();
                    break;
            }
        });

        // getAllRole();

        // function getAllRole() {
        //     return $.ajax({
        //         url: `<?php echo base_url('MasterController/getAllSKTim/') ?>`,
        //         'type': 'GET',
        //         data: {},
        //         success: function(data) {
        //             var json = JSON.parse(data);
        //             if (json['error']) {
        //                 return;
        //             }
        //             dataRole = json['data'];
        //             renderRoleSelection(dataRole);
        //         },
        //         error: function(e) {}
        //     });
        // }


        getAllRefSKTim();

        function getAllRefSKTim() {
            return $.ajax({
                url: `<?php echo base_url('MasterController/getAllSKTim/') ?>`,
                'type': 'POST',
                data: toolbar.form.serialize(),
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataMaster = json['data'];
                    renderRefSKTim(dataMaster);
                },
                error: function(e) {}
            });
        }


        // function renderRoleSelection(data) {
        //     RefSKTimModal.id_role.empty();
        //     RefSKTimModal.id_role.append($('<option>', {
        //         value: "",
        //         text: "-- Pilih Role --"
        //     }));
        //     Object.values(data).forEach((d) => {
        //         RefSKTimModal.id_role.append($('<option>', {
        //             value: d['id_role'],
        //             text: d['id_role'] + ' :: ' + d['title_role'],
        //         }));
        //     });
        // }


        function renderRefSKTim(data) {
            if (data == null || typeof data != "object") {
                console.log("User::UNKNOWN DATA");
                return;
            }

            var i = 0;

            var renderData = [];
            Object.values(data).forEach((d) => {

                var editButton = `
        <a class="edit dropdown-item" data-id='${d['sktim_id']}'><i class='fa fa-pencil'></i> Edit </a>
      `;
                var deleteButton = `
        <a class="delete dropdown-item" data-id='${d['sktim_id']}'><i class='fa fa-trash'></i> Hapus </a>
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
                renderData.push([d['sktim_id'], d['sktim_nama'], button]);
            });
            FDataTable.clear().rows.add(renderData).draw('full-hold');
        }


        FDataTable.on('click', '.edit', function() {
            event.preventDefault();
            RefSKTimModal.form.trigger('reset');
            RefSKTimModal.self.modal('show');
            RefSKTimModal.addBtn.hide();
            RefSKTimModal.saveEditBtn.show();
            var id = $(this).data('id');
            var d = dataMaster[id];
            console.log(d);
            RefSKTimModal.sktim_id.val(d['sktim_id']);
            RefSKTimModal.sktim_nama.val(d['sktim_nama']);

        });


        FDataTable.on('click', '.delete', function() {
            event.preventDefault();
            var id = $(this).data('id');
            swal(swalDeleteConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= base_url('MasterController/deleteSKTim') ?>",
                    'type': 'POST',
                    data: {
                        'sktim_id': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Delete Gagal", json['message'], "error");
                            return;
                        }
                        delete dataMaster[id];
                        swal("Delete Berhasil", "", "success");
                        renderRefSKTim(dataMaster);
                    },
                    error: function(e) {}
                });
            });
        });

        function showRefSKTimModal() {
            RefSKTimModal.self.modal('show');
            RefSKTimModal.addBtn.show();
            RefSKTimModal.saveEditBtn.hide();
            RefSKTimModal.form.trigger('reset');
        }


        RefSKTimModal.form.submit(function(event) {
            event.preventDefault();
            switch (RefSKTimModal.form[0].target) {
                case 'add':
                    addRefSKTim();
                    break;
                case 'edit':
                    editRefSKTim();
                    break;
            }
        });

        function addRefSKTim() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefSKTimModal.addBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/addSKTim') ?>`,
                    'type': 'POST',
                    data: RefSKTimModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefSKTimModal.addBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['sktim_id']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefSKTim(dataMaster);
                        RefSKTimModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }

        function editRefSKTim() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefSKTimModal.saveEditBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/editSKTim') ?>`,
                    'type': 'POST',
                    data: RefSKTimModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefSKTimModal.saveEditBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['sktim_id']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefSKTim(dataMaster);
                        RefSKTimModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }
    });
</script>