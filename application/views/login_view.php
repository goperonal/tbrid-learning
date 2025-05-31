<div id="auth-container">
  <div class="container set-min-height">
    <div class="row justify-content-md-center">
      <div class="col-sm-5">
        <div class="card">
          <div class="card-header">
            Login account
          </div>
          <div class="card-body">
          <?php echo form_open("",array("id"=>"login_form")); ?>
              <div class="row mt-3">
                <div class="col">
                  <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                  <span id="email_error" class="text-danger"></span>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                  <span id="password_error" class="text-danger"></span>
                </div>
              </div>

            </form>
          </div>
          <div class="card-footer text-center">
            <button class="btn rounded-pill mt-3" name="submit" id="submit">Sign In</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){

  $('#submit').on('click',function() {
    
    const form = document.getElementById('login_form');
    var form_data = new FormData(form);
    form_data.append("csrf_token", $.cookie('csrf_cookie'));

    $.ajax({
      url  : base_url + 'auth/login_proses',
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
        if(data.validation == false){
          $('#email_error').html(data.email);
          $('#password_error').html(data.password);
          return false;
        }
        
        if(data.login !== false){
          $.session.set('success', data.message);
          $(location).attr('href', base_url + 'auth/login_success');
        }
        else{
          toastr.error(data.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        toastr.error('login gagal, internal server error!');
      },
      complete: function(){
        setTimeout(function(){
          $('#submit').attr('disabled', false);
          $('#submit').html('Sign In');
        }, 300);
      }

    });

  });

});

</script>