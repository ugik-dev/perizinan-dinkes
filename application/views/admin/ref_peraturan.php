<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox ssection-container">
        <div class="ibox-content">
            <form class="form-inline" id="toolbar_form" onsubmit="return false;">
                <input type="hidden" placeholder="menkes_nama / NIK" class="form-control my-1 mr-sm-2" id="search" name="search">
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
                                    <th style="width: 12%; text-align:center!important">Tentang</th>
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
                <h4 class="modal-title">Form Peraturan</h4>
                <span class="info"></span>
            </div>
            <div class="modal-body" id="modal-body">
                <form role="form" id="user_form" onsubmit="return false;" type="multipart" autocomplete="off">
                    <input type="hidden" id="menkes_id" name="menkes_id">

                    <div class="form-group">
                        <label for="menkes_nama">menkes_nama</label>
                        <input type="text" placeholder="" class="form-control" id="menkes_nama" name="menkes_nama" required="required">
                    </div>
                    <div class="form-group">
                        <label for="menkes_tentang">menkes_tentang</label>
                        <input type="text" placeholder="" class="form-control" id="menkes_tentang" name="menkes_tentang" required="required">
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
        $('#ref_peraturan').addClass('active');


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

        var RefPeraturanModal = {
            'self': $('#user_modal'),
            'info': $('#user_modal').find('.info'),
            'form': $('#user_modal').find('#user_form'),
            'addBtn': $('#user_modal').find('#add_btn'),
            'saveEditBtn': $('#user_modal').find('#save_edit_btn'),
            'menkes_id': $('#user_modal').find('#menkes_id'),
            'menkes_nama': $('#user_modal').find('#menkes_nama'),
            'menkes_tentang': $('#user_modal').find('#menkes_tentang'),
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
                    getAllRefPeraturan();
                    break;
                case 'add':
                    showRefPeraturanModal();
                    break;
            }
        });

        // getAllRole();

        // function getAllRole() {
        //     return $.ajax({
        //         url: `<?php echo base_url('MasterController/getAllPeraturan/') ?>`,
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


        getAllRefPeraturan();

        function getAllRefPeraturan() {
            return $.ajax({
                url: `<?php echo base_url('MasterController/getAllPeraturan/') ?>`,
                'type': 'POST',
                data: toolbar.form.serialize(),
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['error']) {
                        return;
                    }
                    dataMaster = json['data'];
                    renderRefPeraturan(dataMaster);
                },
                error: function(e) {}
            });
        }


        // function renderRoleSelection(data) {
        //     RefPeraturanModal.id_role.empty();
        //     RefPeraturanModal.id_role.append($('<option>', {
        //         value: "",
        //         text: "-- Pilih Role --"
        //     }));
        //     Object.values(data).forEach((d) => {
        //         RefPeraturanModal.id_role.append($('<option>', {
        //             value: d['id_role'],
        //             text: d['id_role'] + ' :: ' + d['title_role'],
        //         }));
        //     });
        // }


        function renderRefPeraturan(data) {
            if (data == null || typeof data != "object") {
                console.log("User::UNKNOWN DATA");
                return;
            }

            var i = 0;

            var renderData = [];
            Object.values(data).forEach((d) => {

                var editButton = `
        <a class="edit dropdown-item" data-id='${d['menkes_id']}'><i class='fa fa-pencil'></i> Edit </a>
      `;
                var deleteButton = `
        <a class="delete dropdown-item" data-id='${d['menkes_id']}'><i class='fa fa-trash'></i> Hapus </a>
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
                renderData.push([d['menkes_id'], d['menkes_nama'], d['menkes_tentang'], button]);
            });
            FDataTable.clear().rows.add(renderData).draw('full-hold');
        }


        FDataTable.on('click', '.edit', function() {
            event.preventDefault();
            RefPeraturanModal.form.trigger('reset');
            RefPeraturanModal.self.modal('show');
            RefPeraturanModal.addBtn.hide();
            RefPeraturanModal.saveEditBtn.show();
            var id = $(this).data('id');
            var d = dataMaster[id];
            console.log(d);
            RefPeraturanModal.menkes_id.val(d['menkes_id']);
            RefPeraturanModal.menkes_nama.val(d['menkes_nama']);
            RefPeraturanModal.menkes_tentang.val(d['menkes_tentang']);

        });


        FDataTable.on('click', '.delete', function() {
            event.preventDefault();
            var id = $(this).data('id');
            swal(swalDeleteConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    url: "<?= base_url('MasterController/deletePeraturan') ?>",
                    'type': 'POST',
                    data: {
                        'menkes_id': id
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Delete Gagal", json['message'], "error");
                            return;
                        }
                        delete dataMaster[id];
                        swal("Delete Berhasil", "", "success");
                        renderRefPeraturan(dataMaster);
                    },
                    error: function(e) {}
                });
            });
        });

        function showRefPeraturanModal() {
            RefPeraturanModal.self.modal('show');
            RefPeraturanModal.addBtn.show();
            RefPeraturanModal.saveEditBtn.hide();
            RefPeraturanModal.form.trigger('reset');
        }


        RefPeraturanModal.form.submit(function(event) {
            event.preventDefault();
            switch (RefPeraturanModal.form[0].target) {
                case 'add':
                    addRefPeraturan();
                    break;
                case 'edit':
                    editRefPeraturan();
                    break;
            }
        });

        function addRefPeraturan() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefPeraturanModal.addBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/addPeraturan') ?>`,
                    'type': 'POST',
                    data: RefPeraturanModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefPeraturanModal.addBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['menkes_id']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefPeraturan(dataMaster);
                        RefPeraturanModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }

        function editRefPeraturan() {
            swal(swalSaveConfigure).then((result) => {
                if (!result.value) {
                    return;
                }
                buttonLoading(RefPeraturanModal.saveEditBtn);
                $.ajax({
                    url: `<?= base_url('MasterController/editPeraturan') ?>`,
                    'type': 'POST',
                    data: RefPeraturanModal.form.serialize(),
                    success: function(data) {
                        buttonIdle(RefPeraturanModal.saveEditBtn);
                        var json = JSON.parse(data);
                        if (json['error']) {
                            swal("Simpan Gagal", json['message'], "error");
                            return;
                        }
                        var d = json['data']
                        dataMaster[d['menkes_id']] = d;
                        swal("Simpan Berhasil", "", "success");
                        renderRefPeraturan(dataMaster);
                        RefPeraturanModal.self.modal('hide');
                    },
                    error: function(e) {}
                });
            });
        }
    });
</script>