<div class="wrapper containers">
  <div id="main" class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <!-- <div class="col-sm-10" style="min-height:200px; min-width: 600px; background: red;"> -->
    <div id="content" class="col-sm-10">

      <div id="content-list" class="row">
        <div class="col-sm-9 p-4 pt-0" style="background: #D6D6D6D1;">
          <div class="clearfix title-learning">
              <div class="float-left">
                <span class="bg-warning rounded-pill px-2 py-1"><?php echo $sub_kelas->nama_sub_kelas; ?></span>
                <?php
                
                if($live_zoom == true){
                  echo '<a href="'.base_url().'teacher/learning/delete_room/'.$sub_kelas->sub_kelas_id.'"><span class="bg-danger rounded-pill px-4 py-1">Delete Live Meeting</span></a>';
                }
                else{
                  echo '<a href="#" id="zoom_create_room"><span class="bg_custom_2 rounded-pill px-2 py-1">Create Live Meeting</span></a>';
                }

              echo '</div>';

              if($live_zoom == true){
                echo '<a href="' . base_url().'teacher/learning/video_conference/'.$sub_kelas->sub_kelas_id.'">
                <img src="' . base_url().'assets/img/icon/live.jpg" class="img-fluid float-right" width="75"></a>';
              }

              ?>
            </div>
          <div class="row">
            <div class="col-12">
              <?php echo form_open("#",array("id"=>"form_add_learning","class"=>"mt-4")); ?>

                <input type="hidden" name="sub_kelas_id" value="<?php echo $sub_kelas->sub_kelas_id; ?>">
                <input type="hidden" name="kelas_id" value="<?php echo $sub_kelas->kelas_id; ?>">
                              
                <div class="form-group">
                  <label class="font-weight-normal" for="learning_goal">Learning goal</label>
                  <textarea class="form-control" name="learning_goal" id="learning_goal" rows="5"><?php echo !empty($learning->learning_goal)?$learning->learning_goal:""; ?></textarea>
                  <span id="learning_goal_error" class="text-danger"></span>
                </div>

                <div class="form-group">
                  <label class="font-weight-normal" for="topic">Topic</label>
                  <input type="text" class="form-control" name="topic" id="topic" value="<?php echo !empty($learning->topic)?$learning->topic:""; ?>" />
                  <span id="topic_error" class="text-danger"></span>
                </div>

                <div class="form-group">
                  <label class="font-weight-normal" for="tampil">Delivered to Students</label>
                  <select class="form-control" name="tampil" id="tampil" style="max-width: 20%;">
                    <option value="yes" <?php echo isset($learning) && $learning->tampil == "yes" ? "selected" : ""; ?>>Yes</option>
                    <option value="no" <?php echo isset($learning) && $learning->tampil == "no" ? "selected" : ""; ?>>No</option>
                  </select>
                  <span id="tampil_error" class="text-danger"></span>
                </div>

                <button type="button" id="submit_learning" class="btn btn-info btn-sm btn-sm">Save</button>
              </form>
            </div>

            <div id="task" class="col-12 clearfix">
              <hr style="margin-block: -15px; margin-top: 15px;">

              <div id="tasks-container">

                <?php 


                $no =1;
                
                foreach ($tasks->result() as $task) {
                  
                  
                  if($task->jenis_task == 'content'){
                    echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      echo "<span class='badge bg_custom_4 rounded-0 px-4 py-2 ml-3 mb-3 font-weight-normal cursor-pointer text-white badge_edit_task' data-id='$task->task_id' data-active='content'><i class='fas fa-edit'></i> Edit</span>";
                      echo "<span class='badge badge-danger rounded-0 px-4 py-2 ml-3 mb-3 font-weight-normal cursor-pointer text-white delete-task' data-id='$task->task_id' data-sub_class_id='$task->sub_kelas_id'><i class='fas fa-trash'></i> Delete</span>";
                    echo "</div>";
                    echo "<div class='ml-3'>$task->content</div>";
                  }

                  if($task->jenis_task == 'multiple-choices'){
                      echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      echo "</div>";

                      $content = json_decode($task->content, true);

                      // Ambil uniqid dari content untuk membuat nama input unik
                      $uniqid = isset($content['uniqid']) ? $content['uniqid'] : uniqid();

                      echo "<table class='ml-3 mb-4'>
                          <tr>
                            <td class='pb-3'>{$content['question']}</td>
                          </tr>
                          <tr>
                            <td>
                              <div class='form-check'>
                                <input class='form-check-input' type='radio' name='option_{$uniqid}' value='{$content['option_a']}' />
                                <label class='form-check-label'>{$content['option_a']}</label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='form-check'>
                                <input class='form-check-input' type='radio' name='option_{$uniqid}' value='{$content['option_b']}' />
                                <label class='form-check-label'>{$content['option_b']}</label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='form-check'>
                                <input class='form-check-input' type='radio' name='option_{$uniqid}' value='{$content['option_c']}' />
                                <label class='form-check-label'>{$content['option_c']}</label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='form-check'>
                                <input class='form-check-input' type='radio' name='option_{$uniqid}' value='{$content['option_d']}' />
                                <label class='form-check-label'>{$content['option_d']}</label>
                              </div>
                            </td>
                          </tr>
                      </table>";
                  }




                  if($task->jenis_task == 'fill-the-blank'){
                    echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      echo "<span class='badge bg_custom_4 rounded-0 px-4 py-2 ml-3 mb-3 font-weight-normal cursor-pointer text-white badge_edit_task' data-id='$task->task_id' data-active='content'><i class='fas fa-edit'></i> Edit</span>";
                      echo "<span class='badge badge-danger rounded-0 px-4 py-2 ml-3 mb-3 font-weight-normal cursor-pointer text-white delete-task' data-id='$task->task_id' data-sub_class_id='$task->sub_kelas_id'><i class='fas fa-trash'></i> Delete</span>";
                    echo "</div>";

                    $field_array = json_decode($task->form_task, true);
                    $template = $task->content;

                    echo "<div class='ml-3'>" . input_name_toJson($template,$field_array) . "</div>";
                  }



                  $no++;

                }

                ?>

                <div id="loading-overlay" class="overlay" style="display: none;">
                  <i class="fas fa-2x fa-sync-alt"></i>
                </div>
              </div>

              
              

              <span class="badge badge-primary px-4 py-2 mt-4 cursor-pointer" id="badge_add_task"><i class="fas fa-plus"></i> Add Task</span>
            </div>
          </div>
        </div>
        <div id="zoom_right_sidebar" class="col-sm-3">
          <div class="px-2 py-2" style="background: #5a0303;">
            <div id="live_chat" class="card rounded-0 mb-4">
              <div id="live_chat_header" class="card-header">
                Welcome to Live Chat
              </div>
              <div class="card-body overflow-auto p-2" id="chat_content"></div>
              <div class="card-footer">
                <form id="form_live_chat">
                  <div class="input-group">
                    <input type="hidden" name="sub_kelas_id" value="<?php echo $sub_kelas->sub_kelas_id; ?>" />
                    <input type="text" class="form-control rounded-0"  name="live_chat_message" id="live_chat_message" placeholder="Type your message here" />
                    <div class="input-group-append">
                      <button class="btn btn-success rounded-0 border-0" type="button" id="btn_live_chat"><i class="fas fa-paper-plane"></i></button>
                    </div>
                  </div>
                  <span id="live_chat_message_error" class="text-danger"></span>
                </form>
              </div>
            </div>

            <div id="live_user" class="card rounded-0 mt-4">
              <div id="live_user_header" class="card-header">
                <i class="fas fa-circle text-danger mr-3"></i> Live
              </div>
              <!-- <div class="card-body"> -->

                <ul class="list-group list-group-flush">
                  <?php

                    // var_dump($user_list);
                    if($this->session->level_akses == 'teacher')
                      echo "<li class='list-group-item'><i class='fas fa-circle fa-sx pl-1 mr-3'></i>".$this->session->user_name."</li>";

                    foreach ($students->result() as $student) {
                        echo "<li class='list-group-item list-students student_answer' data-id='$student->akun_id'><i class='fas fa-circle fa-sx pl-1 mr-3'></i>$student->nama_depan $student->nama_belakang</li>";
                    }


                  ?>
                </ul>

              <!-- </div> -->
            </div>
          </div>

        </div>
      </div>


    </div> <!-- /col-sm-10 -->

    


  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    // var sidebarVisible = true;  // Kondisi awal sidebar tampil

    // $('#hidden-sidebar').click(function(e) {
    //     e.preventDefault(); // Mencegah reload halaman

    //     if (sidebarVisible) {
    //         // Sembunyikan sidebar dan perbesar konten
    //         $('#sidebar').hide();
    //         $('#content').removeClass('col-sm-8').addClass('col-sm-12');
    //     } else {
    //         // Tampilkan sidebar dan kembalikan ukuran konten
    //         $('#sidebar').show();
    //         $('#content').removeClass('col-sm-12').addClass('col-sm-8');
    //     }

    //     // Toggle status sidebarVisible
    //     sidebarVisible = !sidebarVisible;
    // });

    $('#zoom_create_room').on('click',function() {
      $('#model_zoom_create_room').modal('show');
    });

    $('#live_user').on('click', '.student_answer', function(){

        let tasksContainer = $('#tasks-container');
        let loadingSpinner = $('#loading-overlay');

        let user_id = $(this).data('id');
        let subKelasId = $('#form_add_learning input[name="sub_kelas_id"]').val();

        let data = {
            'user_id': user_id,
            'sub_kelas_id': subKelasId,
            'csrf_token' : $.cookie('csrf_cookie')
        };

        loadingSpinner.show();

        $.ajax({
            url: base_url + 'teacher/learning/get_student_response',
            method: 'POST',
            data: data,
            success: function(responseData) {
                loadingSpinner.hide();
                 tasksContainer.empty();
                 tasksContainer.append(responseData);
            },
            error: function(xhr, status, error) {
              loadingSpinner.hide();
                console.error('Terjadi kesalahan:', error);
            }
        });

    });


    // Fungsi untuk mengambil nilai dari response berdasarkan task_id dan input_name
    function getResponseValue(taskId, inputName, responses) {
        let input_value = '';
        $.each(responses, function(index, response) {
            if (response.task_id == taskId && response.input_name == inputName) {
                input_value = response.input_value;
                return false; // Berhenti setelah menemukan nilai
            }
        });
        return input_value;
    }


    $('#task').on('click','.delete-task', function(e) {
      var task_id = $(this).data('id');
      var sub_class_id = $(this).data('sub_class_id');
      $.ajax({
        type : "POST",
        url  : base_url + 'teacher/task/delete',
        dataType : "json",
        type: 'post',
        data: {
          id:task_id,
          csrf_token: $.cookie('csrf_cookie')
        },
        cache:false,
        success: function(data) {
          
          if(data == true){
            $.session.set('success', 'Task deleted!');
          }
          else{
            $.session.set('error', 'Deleted Failed!');
          }
          // location.reload();
          location.href = base_url + 'teacher/learning/index/' + sub_class_id;
        }
      });
    });

    $('#task').on('click', '.badge_edit_task', function() {
      var task_id = $(this).data('id');
      var task_active = $(this).data('active');
      location.href = base_url + 'teacher/task/edit/' + task_id + '?act=' + task_active;
    });

    $('#badge_add_task').on('click',function() {

      var sub_kelas_id = $("#form_add_learning [name='sub_kelas_id']").val();
      location.href = base_url + 'teacher/task/add/' + sub_kelas_id;

    });

    $('#submit_learning').on('click',function() {
    
      const form = document.getElementById('form_add_learning');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/learning/add_learning',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#submit_learning').attr('disabled', 'disabled');
          $('#submit_learning').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data !== 'sukses'){
            $('#learning_goal_error').html(data.learning_goal);
            $('#topic_error').html(data.topic);
            return false;
          }
          else{
            $.session.set('success', 'Learning goal and topic added!');
            location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('login gagal, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#submit_learning').attr('disabled', false);
            $('#submit_learning').html('Submit');
          }, 300);
        }

      });

    });

    function loadChat() {
      var uri_ID = window.location.pathname.split('/')[4];
      $('#chat_content').load(base_url + 'live_chat/index/' + uri_ID);
    }

    // Memuat konten chat saat halaman dimuat
    loadChat();

    // Memuat ulang chat setiap 5 detik
    setInterval(loadChat, 5000);

    $('#btn_live_chat').on('click',function() {
    
      const form = document.getElementById('form_live_chat');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/learning/send_chat',
        dataType : "json",
        type: 'post',
        data: form_data,
        processData: false,
        contentType: false,
        cache:false,
        beforeSend:function(){
          $('#btn_live_chat').attr('disabled', 'disabled');
          $('#btn_live_chat').html('<i class="fa fa-spinner fa-spin"></i> loading.');
        },
        success: function(data) {
          if(data !== 'sukses'){
            $('#live_chat_message_error').html(data.live_chat_message);
            return false;
          }
          else{
            // $.session.set('success', 'Successfully sent the message!');
            // location.reload();
            loadChat();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('Failed, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#btn_live_chat').attr('disabled', false);
            $('#btn_live_chat').html('<i class="fas fa-paper-plane"></i>');
          }, 300);
        }

      });

    });
  });
</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

