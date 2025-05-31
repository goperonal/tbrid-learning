<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div id="content" class="col-sm-10">
        <div class="row">
            <div class="col">
                <p class="text-center mb-0 p-2">
                    <span id="countdown">00:00:00</span>
                </p>
            </div>
        </div>
        <div class="row">
            
            <div class="col-sm-6 p-4" style="background: #D8D0CB; min-height: 900px;">
              <?php echo form_open("",array("id"=>"form_update_assignment")); ?>

                <input type="hidden" name="assignment_id" value="<?php echo $assignment->assignment_id; ?>">
                <table id="assignment-add" class="w-100">
                  <tr>
                    <td class="left">Assignment</td>
                    <td colspan="5">
                      <input type="text" name="assignment" id="assignment" class="w-100" value="<?php echo $assignment->assignment; ?>" disabled="true" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left">Assign to</td>
                    <td colspan="5">
                      <input type="text" class="mr-2" value="<?php echo $assignment->nama_kelas; ?>" disabled="true" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left">Instruction</td>
                    <td colspan="5"></td>
                  </tr>
                  <tr>
                    <td colspan="6" class="p-0">
                      <textarea class="form-control rounded-0" disabled="true" rows="5" style="background: #ECE9E7;"><?php echo $assignment->intruksi; ?></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td class="left" colspan="3">Due date</td>
                    <td>
                      <input type="datetime-local" class="mr-2" name="tanggal_mulai" id="created_date" value="<?php echo $assignment->crated_date; ?>" />
                    </td>
                    <td class="left text-center">To</td>
                    <td class="pl-2">
                      <input type="datetime-local" name="tanggal_akhir" id="due_date" value="<?php echo $assignment->due_date; ?>" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="left">Number of Questions</td>
                    <td colspan="2">
                      <?php
                      $dataArray = json_decode($assignment->questions, true);
                      $jumlahData = count($dataArray); ?>
                      <input type="text" value="<?php echo $jumlahData; ?>" disabled="true" style="width: 108px; text-align: center;" />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="p-2"></td>
                  </tr>
                  <tr>
                    <td colspan="4" class="left">Total Score</td>
                    <td colspan="2">
                      <input type="text" class="w-20" name="total_score" id="total_score" value="<?php echo $assignment->total_score; ?>" disabled="true" style="width: 108px; text-align: center;" />
                    </td>
                  </tr>
                </table>

                <button type="submit" name="submit" class="btn btn-update-assignment mt-4" id="update_questions">Update Questions</button>

                <?php echo form_close(); ?>
            </div>

            <div id="ass_user_list" class="col-sm-2 p-2" style="background: #E9E5E1; min-height: 900px;">
                <span>List of Students</span>
                <ul class="list-group list-group-flush">
                  <?php
                    foreach ($ass_mahasiswa->result() as $student) {
                        echo "<li class='list-group-item list-students student_assignment' data-id='$student->akun_id'><i class='fas fa-caret-right mr-2'></i>$student->nama_depan $student->nama_belakang</li>";
                    }
                  ?>
                </ul>
            </div>

            <div class="col-sm-4 p-4" style="background: #E9E5E1; min-height: 900px;">
                <div id="dynamic_field"></div>
                
            </div>
        </div>
    </div>
    
  </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){

      $('#update_questions').on('click',function(e) {
        e.preventDefault();
        
        const form = document.getElementById('form_update_assignment');
        var form_data = new FormData(form);
        form_data.append("csrf_token", $.cookie('csrf_cookie'));

        $.ajax({
          url  : base_url + 'teacher/assignment/update_assignment',
          dataType : "json",
          type: 'post',
          data: form_data,
          processData: false,
          contentType: false,
          cache:false,
          beforeSend:function(){
            $('#create_questions').attr('disabled', 'disabled');
            $('#create_questions').html('<i class="fa fa-spinner fa-spin"></i> loading.');
          },
          success: function(data) {
            if(data !== 'sukses'){
              $.session.set('Failed updated!');
              return false;
            }
            else{
              $.session.set('success', 'Assignment updated!');
              // location.href = base_url + 'teacher/assignment';
              location.reload();
            }
          },
          error: function (jqXHR, textStatus, errorThrown){
            toastr.error('Failed, internal server error!');
          },
          complete: function(){
            setTimeout(function(){
              $('#create_questions').attr('disabled', false);
              $('#create_questions').html('Create Questions');
            }, 300);
          }

        });

      });

      function sumScores() {
          let total = 0;
          // Ambil semua input yang memiliki class "question-score"
          $('input[name^="question_"][name$="_nilai"]').each(function() {
              // Ambil nilai dari input, jika kosong atau bukan angka, dianggap 0
              let value = parseFloat($(this).val()) || 0;
              total += value;
          });
          // Tampilkan hasil penjumlahan di div dengan id "total-nilai"
          $('#tampilkan-nilai').text('T0TAL NILAI: ' + total);
          // $('#txt_total_nilai').val(total);
      }

      // Panggil fungsi saat nilai input berubah (keyup atau change)
      $('input[name^="question_"][name$="_nilai"]').on('keyup change', function() {
          sumScores();
      });

      // Panggil fungsi sekali saat halaman selesai dimuat
      sumScores();

      $('#dynamic_field').on('keyup', 'input[data-assrespid]', function() {
          let assresid = $(this).data('assrespid');
          let nilai = $(this).val();

          $.ajax({
              url: base_url + 'teacher/assignment/set_nilai_student_response',
              method: 'POST',
              data: { assresid:assresid, nilai:nilai},
              success: function(responseData) {
                  console.log(responseData);
                  sumScores();
              },
              error: function(xhr, status, error) {
                  console.error('Terjadi kesalahan:', error);
              }
          });
      });

      $('#ass_user_list').on('click', '.student_assignment', function(){

          let tasksContainer = $('#dynamic_field');
          let loadingSpinner = $('#loading-overlay');

          let user_id = $(this).data('id');
          var assignment_id = window.location.pathname.split('/')[4];

          let data = {
              'user_id': user_id,
              'assignment_id': assignment_id,
              'csrf_token' : $.cookie('csrf_cookie')
          };

          loadingSpinner.show();

          $.ajax({
              url: base_url + 'teacher/assignment/get_student_response',
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
    });
</script>
    

<script>
    $(document).ready(function() {

        function convertToISO(dateStr) {
            if (!dateStr) {
                console.error('Date string is undefined or null.');
                return null;
            }
            return dateStr.replace(' ', 'T');
        }

        var createdDate = $("input#created_date").val();
        var dueDate = $("input#due_date").val();

        if (!createdDate || !dueDate) {
            console.error('One or both date values are missing.');
            return;
        }

        createdDate = convertToISO(createdDate);
        dueDate = convertToISO(dueDate);

        if (!Date.parse(createdDate) || !Date.parse(dueDate)) {
            console.error('Invalid date format after conversion.');
            return;
        }

        var startTime = new Date(createdDate).getTime();
        var endTime = new Date(dueDate).getTime();

        var x = setInterval(function() {

            var now = new Date().getTime();
            var distance = endTime - now;

            // Calculate days, hours, minutes, and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24)); // Adding days calculation
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display countdown including days
            $("#countdown").html(
                (days > 0 ? days + "d " : '') + 
                (hours < 10 ? '0' : '') + hours + ":" +
                (minutes < 10 ? '0' : '') + minutes + ":" +
                (seconds < 10 ? '0' : '') + seconds
            );

            // If the countdown is over, trigger the expiration event
            if (distance < 0) {
                clearInterval(x);
                $("#countdown").html("EXPIRED");

                var form_data = new FormData();
                form_data.append("status","finished");

                var assignment_id = window.location.pathname.split('/')[4];
                form_data.append('assignment_id', assignment_id);

                form_data.append("csrf_token", $.cookie('csrf_cookie'));

                $.ajax({
                    url: base_url + 'students/assignment/update_status',
                    type: 'POST',
                    dataType: 'json',
                    data: form_data,
                    processData: false,
                    contentType: false,
                    cache:false,
                    success: function(response) {
                        console.log('Success:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        }, 1000);
    });

</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

