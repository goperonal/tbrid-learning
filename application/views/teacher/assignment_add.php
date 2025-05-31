<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div id="assignment-main" class="col-sm-5 p-4" style="background: #D8D0CB;">

      <div class="row">
        <div class="col">
          <?php echo form_open("",array("id"=>"form_add_assignment")); ?>
            <input type="hidden" id="json_data" name="json_data">
            <!-- <textarea id="json_data" rows="5" class="w-100"></textarea> -->
            <table id="assignment-add" class="w-100">
              <tr>
                <td class="left">Assignment</td>
                <td colspan="5">
                  <input type="text" name="assignment" id="assignment" class="w-100">
                  <span id="assignment_error" class="text-danger"></span>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td class="left">Assign to</td>
                <td colspan="5">
                  <select class="w-100" name="kelas" id="kelas">
                    <?php

                      foreach ($record as $value) {
                        echo "<option value='$value[kelas_id]'>$value[nama_kelas]</option>";
                      }
                    ?>
                  </select>
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
                  <!-- <textarea class="texteditor" name="assignment_intruksi" id="assignment_intruksi"></textarea> -->
                  <textarea class="form-control rounded-0" name="assignment_intruksi" id="assignment_intruksi" rows="5"></textarea>
                  <span id="assignment_intruksi_error" class="text-danger"></span>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td class="left" colspan="3">Due date</td>
                <td>
                  <input type="datetime-local" class="mr-2" name="tanggal_mulai" id="tanggal_mulai">
                  <span id="tanggal_mulai_error" class="text-danger"></span>
                </td>
                <td class="left text-center">To</td>
                <td class="pl-2">
                  <input type="datetime-local" name="tanggal_akhir" id="tanggal_akhir">
                  <span id="tanggal_akhir_error" class="text-danger"></span>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td colspan="4" class="left">Number of Questions</td>
                <td colspan="2"><input type="text" name="noq" id="noq" style="width: 108px; text-align: center;"></td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td colspan="4" class="left">Total Score</td>
                <td colspan="2">
                  <input type="text" class="w-20" name="total_score" id="total_score" style="width: 108px; text-align: center;">
                  <span id="total_score_error" class="text-danger"></span>
                </td>
              </tr>
              <!-- <tr>
                <td colspan="6" class="p-0">
                  <textarea id="json_data" name="json_data" class="w-100" rows="5"></textarea>
                </td>
              </tr> -->
            </table>

            

            <button type="submit" name="submit" class="btn btn-create-assignment mt-4" id="create_questions">Create Questions</button>

          <?php echo form_close(); ?>

        </div>
      </div>

    </div>

    <div class="col-sm-5 p-4" style="background: #CDC5C5;">
      <button class="btn btn-create-assignment mb-4" id="btn-create-assignment">Add Question</button>

      <div id="dynamic_field"></div>


    </div>
    
  </div>
</div>

<script>

  $(document).ready(function(){

    // Assignment
    // =============================================================================================

    $('#create_questions').on('click',function(e) {
      e.preventDefault();
      
      const form = document.getElementById('form_add_assignment');
      var form_data = new FormData(form);
      form_data.append("csrf_token", $.cookie('csrf_cookie'));

      $.ajax({
        url  : base_url + 'teacher/assignment/save_assignment',
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
            $('#assignment_error').html(data.assignment);
            $('#assignment_intruksi_error').html(data.assignment_intruksi);
            $('#tanggal_mulai_error').html(data.tanggal_mulai);
            $('#tanggal_akhir_error').html(data.tanggal_akhir);
            $('#total_score_error').html(data.total_score);
            return false;
          }
          else{
            $.session.set('success', 'Assignment added!');
            location.href = base_url + 'teacher/assignment';
            // location.reload();
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
          toastr.error('login gagal, internal server error!');
        },
        complete: function(){
          setTimeout(function(){
            $('#create_questions').attr('disabled', false);
            $('#create_questions').html('Create Questions');
          }, 300);
        }

      });

    });

    // =============================================================================================
    // End Assignment
    // =============================================================================================

  });

  $(document).ready(function() {
    let i = 0; // ID untuk pertanyaan yang baru ditambahkan
    let questionCount = 0; // Melacak jumlah pertanyaan

    // Fungsi untuk mendapatkan data dari input
    function getInputData() {
        let inputData = [];

        $('#dynamic_field .form-group').each(function() {
            let groupId = $(this).data('id');

            if (groupId !== undefined) {
                let id = groupId;
                let questionName = `Question ${groupId}`;
                let questionScore = parseFloat($(this).find(`input[name="question_${groupId}_score"]`).val()) || 0;
                let questionInstruksi = CKEDITOR.instances[`question_${groupId}_intruksi`]?.getData() || '';

                let dataObject = {
                    id : id,
                    name: questionName,
                    [`question_${groupId}_score`]: questionScore,
                    [`question_${groupId}_intruksi`]: questionInstruksi
                };

                inputData.push(dataObject);
            }
        });

        return inputData;
    }

    // Fungsi untuk memperbarui textarea dengan data JSON
    function updateTextarea() {
        let data = getInputData();
        let jsonString = JSON.stringify(data, null, 2); // Format JSON dengan indentasi 2 spasi
        $('#json_data').val(jsonString); // Perbarui textarea dengan ID 'json_data'
    }

    // Fungsi untuk memperbarui jumlah pertanyaan
    function updateQuestionCount() {
        $('#noq').val(questionCount); // Perbarui jumlah pertanyaan di elemen dengan ID 'noq'
    }

    // Fungsi untuk memperbarui total skor
    function updateTotalScore() {
        let totalScore = 0;

        // Iterasi melalui semua input skor dan jumlahkan nilainya
        $('#dynamic_field input[name^="question_"][name$="_score"]').each(function() {
            let score = parseFloat($(this).val()) || 0; // Parse nilai ke float, default 0 jika NaN
            totalScore += score;
        });

        $('#total_score').val(totalScore); // Perbarui input total skor dengan total yang dihitung
    }

    // Event handler untuk menambahkan pertanyaan baru
    $('#btn-create-assignment').click(function() {
        i++; // Increment ID untuk pertanyaan baru
        questionCount++; // Increment jumlah pertanyaan

        $('#dynamic_field').append(`
            <div data-id="${i}" class="form-group">
                <div class="form-group mb-0">
                    <label class="font-weight-normal" for="question_${i}" style="background:#ddd; padding: 2px 10px;">Question ${i}</label>
                    <input type="text" name="question_${i}_score" class="question-score" style="width: 60px;text-align: center;">
                    <button class="btn btn-sm btn-danger rounded-0 btn_remove" data-id="${i}" type="button" style="margin-bottom: 3px; margin-top:-2px; padding: 3px 6px;">X</button>
                </div>
                <textarea name="question_${i}_intruksi" class="texteditor w-100" style="height: 50px !important;"></textarea>
            </div>
        `);

        CKEDITOR.replace(`question_${i}_intruksi`,{
            height: 100
        });

        CKEDITOR.instances[`question_${i}_intruksi`].on('change', function() {
            updateTextarea();
        });

        updateTextarea();
        updateQuestionCount(); // Perbarui jumlah pertanyaan setelah menambahkan elemen
        updateTotalScore(); // Perbarui total skor setelah menambahkan elemen
    });

    // Event handler untuk menghapus pertanyaan
    $(document).on('click', '.btn_remove', function() {
        let button_id = $(this).data("id");
        $(`[data-id=${button_id}]`).remove();
        questionCount--; // Decrement jumlah pertanyaan setelah menghapus elemen
        updateTextarea();
        updateQuestionCount(); // Perbarui jumlah pertanyaan setelah menghapus elemen
        updateTotalScore(); // Perbarui total skor setelah menghapus elemen
    });

    // Event handler untuk memperbarui total skor saat input berubah
    $(document).on('input', '.question-score', function() {
        updateTextarea();
        updateTotalScore(); // Perbarui total skor setiap kali input berubah
    });

    // Inisialisasi jumlah pertanyaan dan total skor saat halaman dimuat
    $('#dynamic_field .form-group').each(function() {
        questionCount++;
    });
    updateQuestionCount();
    updateTotalScore();
  });

</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

