<div id="auth-container">
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-sm-5">
        <div class="card">
          <div class="card-header">
            Creat a new account
          </div>
          <div class="card-body">
            <?php echo form_open("",array("id"=>"regist_form")); ?>
              <div class="row">
                <div class="col">
                  <input type="text" name="nama_depan" id="nama_depan" class="form-control" placeholder="First name">
                  <span id="nama_depan_error" class="text-danger"></span>
                </div>
                <div class="col">
                  <input type="text" name="nama_belakang" id="nama_belakang" class="form-control" placeholder="Last name">
                  <span id="nama_belakang_error" class="text-danger"></span>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col">
                  <input type="text" name="institusi" id="institusi" class="form-control" placeholder="Istitutions">
                  <span id="institusi_error" class="text-danger"></span>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input level" type="radio" id="teacher" value="teacher" name="level" checked>
                    <label class="form-check-label" for="teacher">Teacher</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input level" type="radio" id="student" value="student" name="level">
                    <label class="form-check-label" for="student">Student</label>
                  </div>
                  <br><span id="level_error" class="text-danger"></span>
                </div>
              </div>
              <!-- <div class="panel_nim hidden">
                <div class="row mt-3">
                  <div class="col">
                    <input type="text" name="nim" id="nim" class="form-control" placeholder="NIM">
                    <span id="nim_error" class="text-danger"></span>
                  </div>
                </div>
              </div> -->


              <div class="row mt-3">
                <div class="col">
                  <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                  <span id="email_error" class="text-danger"></span>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                  <span id="password_error" class="text-danger"></span>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col">
                  <input type="password" class="form-control" name="confirm_password" id="confirm_passsword" placeholder="Confirm paswword">
                  <span id="confirm_password_error" class="text-danger"></span>
                </div>
              </div>

              
            <?php echo form_close(); ?>
          </div>
          <div class="card-footer text-center">
            <button class="btn rounded-pill mt-3" name="submit" id="submit">SingUp</button>
            <br><a href="<?php echo base_url(); ?>auth/login" class="small">Already have an account</a>
          </div>
        </div>
      </div>
    </div>

  </div>
  
</div>

<script type="text/javascript">
$(document).ready(function(){

  /*$('.level').on('click', function() {
    var level = $(this).val();
    
    if ( level == 'student' ) {
      $('#regist_form .panel_nim').removeClass('hidden');
    }
    else {
      $('#regist_form .panel_nim').addClass('hidden');
    }
  });*/

  $('#submit').on('click',function() {
    
    const form = document.getElementById('regist_form');
    var form_data = new FormData(form);
    form_data.append("csrf_token", $.cookie('csrf_cookie'));

    $.ajax({
      url  : base_url + 'auth/regist_proses',
      dataType : "json",
      type: 'post',
      data: form_data,
      processData: false,
      contentType: false,
      cache:false,
      beforeSend:function(){
        $('#submit').attr('disabled', 'disabled');
        $('#submit').html('<i class="fa fa-spinner fa-spin"></i> loading.');
      },
      success: function(data) {
        if(data !== 'sukses'){
          $('#nama_depan_error').html(data.nama_depan);
          $('#nama_belakang_error').html(data.nama_belakang);
          $('#institusi_error').html(data.institusi);
          $('#nim_error').html(data.nim);
          $('#email_error').html(data.email);
          $('#password_error').html(data.password);
          $('#confirm_password_error').html(data.confirm_password);
          $('#level_error').html(data.level);
        }
        else{
          $.session.set('success', 'Registration success!');
          location.reload();
        }
        // $('#submit').attr('disabled', false);
      },
      error: function (jqXHR, textStatus, errorThrown){
        toastr.error('login gagal, internal server error!');
      },
      complete: function(){
        setTimeout(function(){
          $('#submit').attr('disabled', false);
          $('#submit').html('SingUp');
        }, 300);
      }

    });

  });

});

</script>