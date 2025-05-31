<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div class="col-sm-10 px-4 bg-white">
      <h1 class="my-4">Add task</h1>
      <div class="card card-primary card-outline card-tabs shadow-none border-left border-right border-bottom">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-content-tab" data-toggle="tab" data-target="#nav-content" type="button" role="tab" aria-controls="nav-content" aria-selected="true">Content</button>
              <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Multiple Choices</button>
              <button class="nav-link" id="nav-fill-the-blank-tab" data-toggle="tab" data-target="#nav-fill-the-blank" type="button" role="tab" aria-controls="nav-fill-the-blank" aria-selected="false">Fill the blank</button>
            </div>
          </nav>
        </div>
        <div class="card-body">
          

          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-content" role="tabpanel" aria-labelledby="nav-content-tab">
              <?php echo form_open('teacher/task/save/'.$this->uri->segment(4)); ?>
                <textarea name="content" class="texteditor" ></textarea>

                <button class="btn e-btn1 mt-2" name="submit" type="submit">Save</button>

              <?php echo form_close(); ?>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
              <?php echo form_open('teacher/task/save/'.$this->uri->segment(4)); ?>
                <input type="hidden" name="content_type" value="multiple-choices">
                      
                <div class="form-group">
                  <label class="font-weight-normal" for="question">Question</label>
                  <textarea name="question" id="question" class="form-control texteditor"></textarea>
                </div>

                <table class="w-100">
                  <tr>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_a">Option A</label>
                        <input type="text" name="option_a" id="option_a" class="form-control" />
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_b">Option B</label>
                        <input type="text" name="option_b" id="option_b" class="form-control" />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_c">Option C</label>
                        <input type="text" name="option_c" id="option_c" class="form-control" />
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_d">Option D</label>
                        <input type="text" name="option_d" id="option_d" class="form-control" />
                      </div>
                    </td>
                  </tr>
                </table>

                <button class="btn e-btn1 mt-2" name="submit" type="submit">Save</button>

              <?php echo form_close(); ?>
            </div>
            <div class="tab-pane fade" id="nav-fill-the-blank" role="tabpanel" aria-labelledby="nav-fill-the-blank-tab">
              <?php echo form_open('teacher/task/save/'.$this->uri->segment(4)); ?>
                <input type="hidden" name="content_type" value="fill-the-blank">
                <input type="hidden" id="json_data" name="json_data" value="">

                <div id="dynamic_field">
                        
                  <div id="row1" class="form-group">
                    <label class="font-weight-normal" for="intruksi">Instruction</label>
                    <textarea name="intruksi" id="intruksi" class="form-control texteditor"></textarea>
                    <span class="badge badge-primary cursor-pointer" id="badge_add_form"><i class="fas fa-plus"></i> Add Form</span>
                    <!-- <span id="copyNotification" style="display: none; color:red;">Teks berhasil disalin!</span> -->
                  </div>


                </div>

                <button class="btn e-btn1 mt-2" name="submit" type="submit">Save</button>

              <?php echo form_close(); ?>
            </div>


        </div>
      </div>
    </div>




  </div>
</div>

<script type="text/javascript">

  function get_input_name_toJson() {

    var namesObject = {};

    $('#dynamic_field input[type="text"]').each(function() {
        var namaInput = $(this).attr('name');

        if (!(namaInput in namesObject)) {
            namesObject[namaInput] = true;
        }
    });

    var jsonString = JSON.stringify(namesObject);
    $('#json_data').val(jsonString);
  }

  $(document).ready(function(){      
    var i=1;  

    $('#badge_add_form').click(function(){  
      i++;             
      $('#dynamic_field').append(`<div id="row`+i+`" class="form-group">
        <div class="input-group">
          <input type="text" name="field_`+i+`" class="form-control" />
          <div id="copyButton" class="input-group-append w-20 cursor-pointer">
            <span id="textToCopy_`+i+`" class="input-group-text copyButton">{field_`+i+`}</span>
          </div>
          <button class="btn btn-danger rounded-0 ml-2 btn_remove" data-id="`+i+`" type="button">X</button>
        </div>
      </div>`);
      get_input_name_toJson();
    });

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).data("id");
      $('#row'+button_id+'').remove();
      get_input_name_toJson();
    });

    

    $('#dynamic_field').on('click', '.copyButton', function() {
        var id = $(this).attr('id').split('_')[1];
        var copyText = $('#textToCopy_' + id);

        var tempInput = $('<input>');
        $('body').append(tempInput);

        tempInput.val($(copyText).text()).select();

        document.execCommand('copy');
        tempInput.remove();

        // Tampilkan notifikasi
        // $('#copyNotification').fadeIn().delay(2000).fadeOut();
        toastr.success('Copied!');
    });
    
  }); 
</script>



<?php $this->load->view("teacher/kelas_list_js"); ?>


