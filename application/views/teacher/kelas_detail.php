<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div class="col-sm-5 p-4" style="background: #D6D6D6;">

      <table class="w-100 font-weight-bold">
        <tr>
          <td width="60%" class="bg_custom_3 pl-4 py-1">Class code</td>
          <td width="5%"></td>
          <td width="35%" align="center" class="bg_custom_3">
            <span id="textCopyKelasID"><?php echo $oRecord->kelas_id; ?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <span id="copyNotification" style="display: none; color:red;">Copied!</span>
          </td>
          <td>
            <img id="btnCopyKelasID" src="<?php echo base_url(); ?>assets/img/icon/3.png" class="float-right cursor-pointer" width="20">
          </td>
        </tr>
        <tr>
          <td class="bg_custom_3 pl-4 py-1">Lock Class</td>
          <td></td>
          <td>
            <div class="btn-group">
              <button type="button" class="btn <?php echo $oRecord->tutup == 1 ? "btn-secondary":"btn-primary";?> btn-sm class_lock_unlock" data-id="<?php echo $oRecord->kelas_id; ?>" data-status="<?php echo $oRecord->tutup; ?>">Yes</button>
              <button type="button" class="btn <?php echo $oRecord->tutup == 2 ? "btn-secondary":"btn-primary";?> btn-sm class_lock_unlock" data-id="<?php echo $oRecord->kelas_id; ?>" data-status="<?php echo $oRecord->tutup; ?>">No</button>
            </div>
          </td>
        </tr>
        <tr>
          <td class="py-2" colspan="3"></td>
        </tr>
        <tr>
          <td class="bg_custom_3 pl-4 py-1">Number of Students</td>
          <td></td>
          <td align="center" class="bg_custom_3"><?php echo $mum_of_students->num_rows(); ?></td>
        </tr>
      </table>

    </div>

    <div class="col-sm-5 p-4" style="background: #E4E2E0;">
      <h4 class="mt-2 mb-4">
        <img src="<?php echo base_url(); ?>assets/img/icon/2.png" width="25"> List of Students
      </h4>

      <table id="students_ajax_datatables" class="table table-sm w-100 mt-4" style="background: #D2D2D2;">
        <thead>
          <tr class="bg-light">
            <th>#</th>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- <div class="col-sm-1 bg-white"></div> -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#btnCopyKelasID').on('click',function() {
        var copyText = $('#textCopyKelasID');
        var tempInput = $('<input>');
        $('body').append(tempInput);

        tempInput.val($(copyText).text()).select();

        document.execCommand('copy');
        tempInput.remove();

        toastr.success('Copied!');
    });

    $('#students_ajax_datatables').on('click','.class_pass_update',function(e) {
      e.preventDefault();
      var update_pass_akun_id = $(this).data('id');
      $('#update_pass_akun_id').val(update_pass_akun_id);
      $('#model_update_password').modal('show');
    });
    
    $('#submit_class').on('click',function() {
  
      const form = document.getElementById('form_add_class');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/e_class/add_class',
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
          if(data !== 'sukses'){
            $('#nama_kelas_error').html(data.nama_kelas);
            $('#materi_error').html(data.materi);
            return false;
          }
          else{
            $.session.set('success', 'Class added!');
            location.href = base_url + 'teacher/e_class/';
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

    $('#submit_password_akun').on('click',function() {
    
      const form = document.getElementById('form_update_password_akun');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/e_class/update_password',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_password_akun').attr('disabled', 'disabled');
          $('#submit_password_akun').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data !== 'sukses'){
            $('#password_error').html(data.password);
            $('#konfirmasi_password_error').html(data.konfirmasi_password);
            return false;
          }
          else{
            $.session.set('success', 'Password updated!');
            location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('login gagal, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_password_akun').attr('disabled', false);
            $('#submit_password_akun').html('Submit');
          }, 300);
        }

      });

    });

    

  });
</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

<div class="modal fade" id="model_update_password">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <?php echo form_open("#",array("id"=>"form_update_password_akun")); ?>

          <input type="hidden" name="update_pass_akun_id" id="update_pass_akun_id" />

          <div class="form-group">
            <label for="password" class=" font-weight-normal">Password</label>
            <input type="password" class="form-control" name="password" id="password" />
            <span id="password_error" class="text-danger"></span>
          </div>

          <div class="form-group">
            <label for="konfirmasi_password" class=" font-weight-normal">Konfirmasi password</label>
            <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" />
            <span id="konfirmasi_password_error" class="text-danger"></span>
          </div>

          <div class="float-right">
            <button class="btn btn-primary btn-sm" name="submit_password_akun" id="submit_password_akun">Submit</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
          </div>
          
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

