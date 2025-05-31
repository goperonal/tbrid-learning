<script type="text/javascript">
  $(document).ready(function(){
    
    $('#join_class').on('click',function() {
      $('#form_join_class .text-danger').html("");
      $('#form_join_class .form-control').val("");
      $('#model_join_class').modal('show');
    });

    $('#submit_class').on('click',function() {
  
      const form = document.getElementById('form_join_class');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'students/e_class/join_class',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_class').attr('disabled', 'disabled');
          $('#submit_class').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {

          if(data == 'locked'){
            toastr.error('kelas terkunci!');
            $('.text-danger').html("");
            return false;
          }

          if(data == 'joinded'){
            toastr.error('sudah terdaftar di kelas ini!');
            $('.text-danger').html("");
            return false;
          }
          
          if(data == 'empty'){
            toastr.error('kode kelas tidak di temukan!');
            $('.text-danger').html("");
            return false;
          }

          if(data.validation == 'error'){
            $('#kode_kelas_error').html(data.kode_kelas);
            $('#nim_error').html(data.nim);
            return false;
          }

          if(data == true){
            $.session.set('success', 'berhasil join di kelas ini!');
            location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('login gagal, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_class').attr('disabled', false);
            $('#submit_class').html('Submit');
          }, 300);
        }

      });

    });

    $('.class_detail').on('click', function(e) {
      e.preventDefault(); 
      var class_id = $(this).data('id');
      location.href = base_url + 'students/e_class/detail/' + class_id;
    });

    $('#students_ajax_datatables').DataTable({
        destroy: true,
        processing: true,
        language: {
          processing: "<i class=\"fas fa-spinner fa-pulse fa-3x\"></i>"
        },
        serverSide: true,
        order: [],
        ajax: {
          url: base_url + 'students/e_class/students_ajax_datatables/<?php echo $this->uri->segment(4); ?>',
          type: "POST",
        },
        columnDefs: [
          { targets: [ 0 ], "orderable": false, "className": "text-center"},
          // { targets: [ 2 ], "className": "text-center" },
          // { targets: [ 4 ], "orderable": false, "className": "text-center"}
        ],
    });
    $('.dataTables_processing').removeClass('card');


    $('#class_ajax_datatables').DataTable({ 
      destroy: true,
      processing: true,
      language: {
        processing: "<i class=\"fas fa-spinner fa-pulse fa-3x\"></i>"
      },
      serverSide: true,
      order: [],
      ajax: {
        url: base_url + 'students/e_class/class_ajax_datatables',
        type: "POST",
      },
      columnDefs: [
        { targets: [ 0 ], "orderable": false, "className": "text-center"},
        { targets: [ 2 ], "className": "text-center" },
        // { targets: [ 4 ], "orderable": false, "className": "text-center"}
      ],

    });
    $('.dataTables_processing').removeClass('card');

  });
</script>

<div class="modal fade" id="model_join_class">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Join Class</h5>
      </div>
      <div class="modal-body">
        <?php echo form_open("#",array("id"=>"form_join_class")); ?>
          <div class="form-group row">
            <label for="kode_kelas" class="col-sm-3 col-form-label">Class Code</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="kode_kelas" id="kode_kelas" placeholder="class code">
              <span id="kode_kelas_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="nim" class="col-sm-3 col-form-label">NIM</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nim" id="nim" placeholder="NIM">
              <span id="nim_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row mb-0">
            <div class="offset-sm-3 col-sm-9">
              <button class="btn btn-primary btn-sm" name="submit_class" id="submit_class">Submit</button>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
          </div>
          
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>