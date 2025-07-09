<div class="wrapper containers">
  <div id="main" class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("students/kelas_list"); ?>
      
    </div>

    <!-- <div class="col-sm-10" style="min-height:200px; min-width: 600px; background: red;"> -->
    <div id="content" class="col-sm-10">

      <div class="row">
        <div class="col-12 p-0">
          <?php if ($signature != '' && $meeting_id != ''): ?>

            <style>
                /* To hide */
                #zmmtg-root {
                    display: none;
                    position: unset;
                }

                .mini-layout-body {
                    margin: 0 !important;
                    margin-top: 10px !important;
                    margin-left: 24% !important;
                }

                .mini-layout-body-title {
                    margin: 0 !important;
                }

                .meeting-app {
                    width: 100% !important;
                }

                #wc-loading {
                    width: 100% !important;
                    /* height: 100% !important;*/
                }

                body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer{ margin-left: auto; margin-bottom: -48px; }
                .main-footer{ padding: 0; border-top:0; }
                .join-dialog { margin-bottom: -48px; }
                .meeting-header { z-index: 99; }
                .main-footer.footer{ z-index:98; }
                .footer-button-base__button{ box-shadow:none; border:0; border-radius:0; }
                .dropup .dropdown-toggle::after, .dropdown-toggle::after, .meeting-info-container { display:none; }
            </style>


            <div class="ReactModal__Body--open">
                <!-- added on import -->
                <div id="zmmtg-root"></div>
                <div id="aria-notify-area"></div>

                <!-- added on meeting init -->
                <div class="ReactModalPortal"></div>
                <div class="ReactModalPortal"></div>
                <div class="ReactModalPortal"></div>
                <div class="ReactModalPortal"></div>
                <div class="global-pop-up-box"></div>
                <div class="sharer-controlbar-container sharer-controlbar-container--hidden"></div>
            </div>

            <!-- Dependencies for client view and component view -->
            <script src="https://source.zoom.us/3.13.1/lib/vendor/react.min.js"></script>
            <script src="https://source.zoom.us/3.13.1/lib/vendor/react-dom.min.js"></script>
            <script src="https://source.zoom.us/3.13.1/lib/vendor/redux.min.js"></script>
            <script src="https://source.zoom.us/3.13.1/lib/vendor/redux-thunk.min.js"></script>
            <script src="https://source.zoom.us/3.13.1/lib/vendor/lodash.min.js"></script>

            <!-- CDN for client view -->
            <script src="https://source.zoom.us/3.13.1/zoom-meeting-3.13.1.min.js"></script>
            <!-- <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.13.1/css/bootstrap.css" /> -->
            <link type="text/css" rel="stylesheet" href="https://source.zoom.us/3.13.1/css/react-select.css" />

            <script>
                console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

                // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
                ZoomMtg.setZoomJSLib("https://source.zoom.us/3.13.1/lib", "/av"); // CDN version defaul

                ZoomMtg.preLoadWasm();
                // ZoomMtg.prepareJssdk();
                const zoomMeetingSDK = document.getElementById('zmmtg-root');

                // To hide
                zoomMeetingSDK.style.display = 'none';

                // To show
                zoomMeetingSDK.style.display = 'block';
                ZoomMtg.init({
                    leaveUrl: base_url + 'students/learning/index/<?php echo $sub_kelas->sub_kelas_id; ?>',
                    //webEndpoint: meetingConfig.webEndpoint,
                    //disableCORP: !window.crossOriginIsolated, // default true
                    disablePreview: true, // default false
                    externalLinkPage: base_url + 'students/learning/index/<?php echo $sub_kelas->sub_kelas_id; ?>',
                    success: function() {
                        ZoomMtg.i18n.load('en-US');
                        ZoomMtg.i18n.reload('en-US');
                        ZoomMtg.join({
                            sdkKey: '<?php echo $client_id; ?>',
                            signature: '<?php echo $signature; ?>', // role in SDK signature needs to be 1
                            meetingNumber: '<?php echo $meeting_id; ?>',
                            passWord: '<?php echo $meeting_password; ?>',
                            userName: '<?php echo $user_name; ?>',
                            userEmail: '<?php echo $user_email; ?>',
                            success: function(res) {
                                console.log("join meeting success");
                                console.log("get attendeelist");
                                ZoomMtg.getAttendeeslist({});
                                ZoomMtg.getCurrentUser({
                                    success: function(res) {
                                        console.log("success getCurrentUser", res.result.currentUser);
                                    },
                                });
                            },
                            error: function(res) {
                                console.log(res);
                            },
                        });
                    },
                    error: function(res) {
                        console.log(res);
                    },
                });
            </script>
          <?php endif; ?>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-9 p-4" style="background: #D6D6D6D1;">
          <div class="clearfix">
            <span class="bg-warning rounded-pill px-2 py-1 float-left"><?php echo $sub_kelas->nama_sub_kelas; ?></span>
            <!-- <img src="assets/img/icon/live.jpg" class="img-fluid " width="75"> -->
            <span class="float-right"><i class="fas fa-circle text-danger fa-fade"></i> Live</span>
          </div>
          <div class="row">
            <div id="learning-box" class="">
              <div class="col-12">
                <?php echo form_open("#",array("id"=>"form_add_learning","class"=>"mt-4")); ?>

                  <input type="hidden" name="sub_kelas_id" value="<?php echo $sub_kelas->sub_kelas_id; ?>">
                  <input type="hidden" name="kelas_id" value="<?php echo $sub_kelas->kelas_id; ?>">
                                
                  <div class="form-group">
                    <label class="font-weight-normal" for="learning_goal">Learning goal</label>
                    <textarea class="form-control" rows="5" disabled><?php echo !empty($learning->learning_goal)?$learning->learning_goal:""; ?></textarea>
                    <span id="learning_goal_error" class="text-danger"></span>
                  </div>

                  <div class="form-group">
                    <label class="font-weight-normal" for="topic">Topic</label>
                    <input type="text" class="form-control" value="<?php echo !empty($learning->topic)?$learning->topic:""; ?>" disabled />
                    <span id="topic_error" class="text-danger"></span>
                  </div>
                </form>
              </div>

              <div id="task" class="col-12 clearfix">
                <hr style="margin-block: -15px; margin-top: 15px;">

                <?php 


                $no =1;
                
                foreach ($tasks->result() as $task) {
                  
                  
                  if($task->jenis_task == 'content'){
                    echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      
                    echo "</div>";
                    echo "<div class='ml-3' data-taksid='$task->task_id'>$task->content</div>";
                  }

                  if($task->jenis_task == 'multiple-choices'){
                      echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      echo "</div>";

                      $content = json_decode($task->content, true);

                      echo "<table class='ml-3 mb-4' data-taskid='$task->task_id'>
                              <tr>
                                <td class='pb-3'>{$content['question']}</td>
                              </tr>";

                      $options = ['option_a', 'option_b', 'option_c', 'option_d'];
                      
                      $selected_value = '';
                      foreach ($answer as $response) {
                          if ($response->input_name == 'option_' . $content['uniqid'] && $response->task_id == $task->task_id) {
                              $selected_value = $response->input_value;
                              break;
                          }
                      }

                      foreach ($options as $option) {
                          $isChecked = ($selected_value == $content[$option]) ? 'checked' : '';
                          // Perubahan di sini: name='option_{$content['uniqid']}'
                          echo "<tr>
                                  <td>
                                    <div class='form-check'>
                                      <input class='form-check-input' type='radio' name='option_{$content['uniqid']}' value='{$content[$option]}' $isChecked />
                                      <label class='form-check-label'>{$content[$option]}</label>
                                    </div>
                                  </td>
                                </tr>";
                      }

                      echo "</table>";
                  }



                  if($task->jenis_task == 'fill-the-blank'){
                    echo "<div style='margin-top:40px;'>";
                      echo "<span class='badge bg-warning rounded-pill px-4 py-2 mb-3 mr-4'>Task $no</span>";
                      
                    echo "</div>";

                    $field_array = json_decode($task->form_task, true);
                    $template = $task->content;

                    echo "<div class='ml-3' data-taksid='$task->task_id'>";
                    echo input_name_toJson($template, $field_array, $answer, $task->task_id);
                    echo "</div>";
                  }



                  $no++;

                }

                ?>

                
                

                <span class="badge badge-primary px-4 py-2 mt-4 cursor-pointer" id="badge_add_task"><i class="fas fa-plus"></i> Add Task</span>
              </div>
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
                        echo "<li class='list-group-item list-students'><i class='fas fa-circle fa-sx pl-1 mr-3'></i>$student->nama_depan $student->nama_belakang</li>";
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

  window.addEventListener('scroll', function() {
    var scrollTop = window.scrollY;
    var documentHeight = document.documentElement.scrollHeight;
    var windowHeight = window.innerHeight;
    var scrollPercentage = (scrollTop / (documentHeight - windowHeight)) * 100;

    if (scrollPercentage >= 10) {
      document.getElementById("learning-box").classList.add("learning-box");
    } else {
      document.getElementById("learning-box").classList.remove("learning-box");
    }
  });


  $(document).ready(function(){
    var sidebarVisible = true;  // Kondisi awal sidebar tampil

    $('#hidden-sidebar').click(function(e) {
        e.preventDefault(); // Mencegah reload halaman

        if (sidebarVisible) {
            // Sembunyikan sidebar dan perbesar konten
            $('#sidebar').hide();
            $('#content').removeClass('col-sm-8').addClass('col-sm-12');
        } else {
            // Tampilkan sidebar dan kembalikan ukuran konten
            $('#sidebar').show();
            $('#content').removeClass('col-sm-12').addClass('col-sm-8');
        }

        // Toggle status sidebarVisible
        sidebarVisible = !sidebarVisible;
    });


    // Inputan 
    $('[data-taksid] input').on('keyup', function(){
        let taskId = $(this).closest('[data-taksid]').data('taksid');

        let inputName = $(this).attr('name');
        let inputValue = $(this).val();

        let subKelasId = $('#form_add_learning input[name="sub_kelas_id"]').val();

        let data = {
            'task_id': taskId,
            'input_name': inputName,
            'input_value': inputValue,
            'sub_kelas_id': subKelasId,
            'csrf_token' : $.cookie('csrf_cookie')
        };

        console.log(data);
        // return false;

        $.ajax({
            url: base_url + 'students/learning/student_response',
            method: 'POST',
            data: data,
            success: function(response) {
                console.log('Data berhasil dikirim:', response);
            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        });
    });

    $('[data-taskid] input[type="radio"]').on('change', function() {
        let taskId = $(this).closest('[data-taskid]').data('taskid');  
        let inputName = $(this).attr('name');  // Name input sudah unik untuk setiap task sekarang
        let inputValue = $('input[name="' + inputName + '"]:checked').val();

        let subKelasId = $('#form_add_learning input[name="sub_kelas_id"]').val();

        let data = {
            'task_id': taskId,
            'input_name': inputName,  // Simpan input_name yang baru (unik)
            'input_value': inputValue,
            'sub_kelas_id': subKelasId,
            'csrf_token' : $.cookie('csrf_cookie')
        };

        $.ajax({
            url: base_url + 'students/learning/student_response',
            method: 'POST',
            data: data,
            success: function(response) {
                console.log('Data berhasil dikirim:', response);
            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
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
        url  : base_url + 'students/learning/send_chat',
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

<?php $this->load->view("students/kelas_list_js"); ?>

