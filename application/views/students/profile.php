<div class="wrapper containers">
  <div id="main" class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("students/kelas_list"); ?>
      
    </div>

    <div id="content" class="col-sm-8 p-4" style="background: #D6D6D6D1;">
      <span class="bg-warning rounded-pill px-2 py-1"><?php echo $title; ?></span>
      <div class="row">
        <div class="col-12">
          <?php echo form_open("#",array("id"=>"form_update_profile","class"=>"mt-4")); ?>
                          
            <div class="form-group row">
              <label for="nama_depan" class="col-sm-3 col-form-label">Nama Depan</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_depan" id="nama_depan" value="<?php echo $profile->nama_depan; ?>" />
                <span id="nama_depan_error" class="text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="nama_belakang" class="col-sm-3 col-form-label">Nama Belakang</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="nama_belakang" id="nama_belakang" value="<?php echo $profile->nama_belakang; ?>" />
                <span id="nama_belakang_error" class="text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="email" class="col-sm-3 col-form-label">Email</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="email" id="email" value="<?php echo $profile->email; ?>" />
                <span id="email_error" class="text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="password" class="col-sm-3 col-form-label">Password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="password" id="password" placeholder="kosongkan jika tidak ingin diubah!">
                <span id="password_error" class="text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <label for="konfirmasi_password" class="col-sm-3 col-form-label">Konfirmasi Password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" placeholder="kosongkan jika tidak ingin diubah!">
                <span id="konfirmasi_password_error" class="text-danger"></span>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-9 offset-sm-3">
                <button type="button" id="submit_profile" class="btn btn-info btn-sm btn-sm">Submit</button>
              </div>
            </div>

            
          </form>
        </div>

        
      </div>
    </div>


  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#submit_profile').on('click',function() {
      
      const form = document.getElementById('form_update_profile');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'profile/update_proses',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_profile').attr('disabled', 'disabled');
          $('#submit_profile').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data !== 'sukses'){
            $('#nama_depan_error').html(data.nama_depan);
            $('#nama_belakang_error').html(data.nama_belakang);
            $('#email_error').html(data.email);
            $('#password_error').html(data.password);
            $('#konfirmasi_password_error').html(data.konfirmasi_password);
          }
          else{
            $.session.set('success', 'Profile updated!');
            location.reload();
          }
          // $('#submit_profile').attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('Failed!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_profile').attr('disabled', false);
            $('#submit_profile').html('SingUp');
          }, 300);
        }

      });

    });
  });
</script>

<?php $this->load->view("students/kelas_list_js"); ?>

