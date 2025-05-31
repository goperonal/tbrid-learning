<script type="text/javascript">
  $(document).ready(function(){

    $('#create-assignment').on('click', function(e) {
      e.preventDefault();
      location.href = base_url + 'teacher/assignment/add/';
    });
    
    $('#add_class').on('click',function() {
      $('#model_add_class').modal('show');
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

    $('.add_course').on('click',function(e) {
      e.preventDefault();
      var course_id = $(this).data('id');
      $('#course_kelas_id').val(course_id);
      $('#model_add_course').modal('show');
    });

    $('#submit_course').on('click',function() {
    
      const form = document.getElementById('form_add_course');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/course/index',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_course').attr('disabled', 'disabled');
          $('#submit_course').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data !== 'sukses'){
            $('#course_error').html(data.course);
            return false;
          }
          else{
            $.session.set('success', 'Course added!');
            location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('login gagal, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_course').attr('disabled', false);
            $('#submit_course').html('Submit');
          }, 300);
        }

      });

    });

    $('#submit_zoom_create_room').on('click',function() {
    
      const form = document.getElementById('form_zoom_create_room');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/learning/create_room',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_zoom_create_room').attr('disabled', 'disabled');
          $('#submit_zoom_create_room').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data.validation == false){
            $('#zoom_title_error').html(data.zoom_title);
            $('#zoom_start_time_error').html(data.zoom_start_time);
            $('#zoom_duration_error').html(data.zoom_duration);
            return false;
          }
          else{
            console.log(data);
            if(data.result == false){
              // $.session.set('success', data.message);
              toastr.error(data.message);
            }
            else{
              // toastr.success(data.message);
              $.session.set('success', data.message);
            }
            location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('Failed, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_zoom_create_room').attr('disabled', false);
            $('#submit_zoom_create_room').html('Submit');
          }, 300);
        }

      });

    });


    $('#class_ajax_datatables').on('click','.class_dell', function(e) {
      var class_id = $(this).data('id');
      $.ajax({
        type : "POST",
        url  : base_url + 'teacher/e_class/delete_class',
        dataType : "json",
        type: 'post',
        data: {
          id:class_id,
          csrf_token: $.cookie('csrf_cookie')
        },
        cache:false,
        success: function(data) {
          
          if(data == true){
            $.session.set('success', 'Class deleted!');
          }
          else{
            $.session.set('error', 'Deleted Failed!');
          }
          // location.reload();
          location.href = base_url + 'teacher/e_class';
        }
      });
    });

    $('.class_detail').on('click', function(e) {
      e.preventDefault(); 
      var class_id = $(this).data('id');
      location.href = base_url + 'teacher/e_class/detail/' + class_id;
    });

    $('.btn-group').on('click','.class_lock_unlock', function(e) {
      
      var class_id = $(this).data('id');
      var class_status = $(this).data('status');
      
      $.ajax({
        type : "POST",
        url  : base_url + 'teacher/e_class/lock_unlock_class',
        dataType : "json",
        type: 'post',
        data: {
          id:class_id,
          status:class_status,
          csrf_token: $.cookie('csrf_cookie')
        },
        cache:false,
        success: function(data) {
          
          if(data == true){
            $.session.set('success', 'Class status Updated!');
          }
          else{
            $.session.set('error', 'Class status failed Updated!');
          }

          // location.reload();
          location.href = base_url + 'teacher/e_class/detail/' + class_id;
        }
      });
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
          url: base_url + 'teacher/e_class/students_ajax_datatables/<?php echo $this->uri->segment(4); ?>',
          type: "POST",
        },
        columnDefs: [
          { targets: [ 0 ], "orderable": false, "className": "text-center"},
          // { targets: [ 2 ], "className": "text-center" },
          { targets: [ 4 ], "orderable": false, "className": "text-center"}
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
        url: base_url + 'teacher/e_class/class_ajax_datatables',
        type: "POST",
      },
      columnDefs: [
        { targets: [ 0 ], "orderable": false, "className": "text-center"},
        { targets: [ 2 ], "className": "text-center" },
        { targets: [ 4 ], "orderable": false, "className": "text-center"}
      ],

    });
    $('.dataTables_processing').removeClass('card');

  });
</script>

<div class="modal fade" id="model_zoom_create_room">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Create Room</h5>
      </div>
      <div class="modal-body">
        <?php echo form_open("#",array("id"=>"form_zoom_create_room")); ?>
          <input type="hidden" name="sub_kelas_id" id="sub_kelas_id" value="<?php echo $sub_kelas->sub_kelas_id; ?>" />
          <div class="form-group row">
            <label for="zoom_title" class="col-sm-3 col-form-label">Title</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="zoom_title" id="zoom_title" placeholder="title">
              <span id="zoom_title_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="zoom_start_time" class="col-sm-3 col-form-label">Start time</label>
            <div class="col-sm-9">
              <input type="datetime-local" class="form-control" name="zoom_start_time" id="zoom_start_time" placeholder="start time">
              <span id="zoom_start_time_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="zoom_duration" class="col-sm-3 col-form-label">Duration</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="zoom_duration" id="zoom_duration" placeholder="duration (minute)">
              <span id="zoom_duration_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row mb-0">
            <div class="offset-sm-3 col-sm-9">
              <button class="btn btn-primary btn-sm" name="submit_zoom_create_room" id="submit_zoom_create_room">Submit</button>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
          </div>
          
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="model_add_course">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add meeting</h5>
      </div>
      <div class="modal-body">
        <?php echo form_open("#",array("id"=>"form_add_course")); ?>
          <div class="form-group row">
            <label for="course" class="col-sm-3 col-form-label">Meeting</label>
            <div class="col-sm-9">
              <input type="hidden" name="course_kelas_id" id="course_kelas_id" />
              <input type="text" class="form-control" name="course" id="course" placeholder="meeting">
              <span id="course_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row mb-0">
            <div class="offset-sm-3 col-sm-9">
              <button class="btn btn-primary btn-sm" name="submit_course" id="submit_course">Submit</button>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
          </div>
          
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="model_add_class">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add Class</h5>
      </div>
      <div class="modal-body">
        <?php echo form_open("#",array("id"=>"form_add_class")); ?>
          <div class="form-group row">
            <label for="nama_kelas" class="col-sm-3 col-form-label">Class Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nama_kelas" id="nama_kelas" placeholder="class name">
              <span id="nama_kelas_error" class="text-danger"></span>
            </div>
          </div>
          <div class="form-group row">
            <label for="materi" class="col-sm-3 col-form-label">Course</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="materi" id="materi" placeholder="course">
              <span id="materi_error" class="text-danger"></span>
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