<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div class="col-sm-10 px-4 bg-white">
      <h1 class="my-4">Edit task</h1>
      <div class="card card-primary card-outline card-tabs shadow-none border-left border-right border-bottom">
        <div class="card-header p-0 pt-1 border-bottom-0">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link <?php echo $tab_active == 'content' ? 'active' : ''; ?>" id="nav-content-tab" data-toggle="tab" data-target="#nav-content" type="button" role="tab" aria-controls="nav-content" aria-selected="true">Content</button>
              <button class="nav-link <?php echo $tab_active == 'multiple-choices' ? 'active' : ''; ?>" id="nav-multiple-choices-tab" data-toggle="tab" data-target="#nav-multiple-choices" type="button" role="tab" aria-controls="nav-multiple-choices" aria-selected="false">Multiple Choices</button>
              <button class="nav-link <?php echo $tab_active == 'fill-the-blank' ? 'active' : ''; ?>" id="nav-fill-the-blank-tab" data-toggle="tab" data-target="#nav-fill-the-blank" type="button" role="tab" aria-controls="nav-fill-the-blank" aria-selected="false">Fill the blank</button>
            </div>
          </nav>
        </div>
        <div class="card-body">
          

          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade <?php echo $tab_active == 'content' ? 'show active' : ''; ?>" id="nav-content" role="tabpanel" aria-labelledby="nav-content-tab">
              <?php echo form_open('teacher/task/save/'.$task->sub_kelas_id); ?>
                <input type="hidden" name="task_id" value="<?php echo $task->task_id; ?>" />
                <textarea name="content" class="texteditor" rows="60"><?php echo $task->jenis_task == 'content'?$task->content:''; ?></textarea>

                <div class="mt-2">
                  <button class="btn btn-sm e-btn1 mt-2 rounded-0" name="submit" type="submit">Save</button>
                  <button data-id="<?php echo $task->sub_kelas_id; ?>" class="btn btn-sm e-btn2 mt-2 rounded-0 btn-cancel" name="cancel" type="button">Cancel</button>
                </div>

              <?php echo form_close(); ?>
            </div>
            <div class="tab-pane fade <?php echo $tab_active == 'multiple-choices' ? 'show active' : ''; ?>" id="nav-multiple-choices" role="tabpanel" aria-labelledby="nav-multiple-choices-tab">
              <?php echo form_open('teacher/task/save/'.$task->sub_kelas_id); ?>
                <input type="hidden" name="content_type" value="multiple-choices">
                <input type="hidden" name="task_id" value="<?php echo $task->task_id; ?>" />

                <?php
                if($task->jenis_task == "multiple-choices")
                  $task_json = json_decode($task->content,true);
                ?>
                      
                <div class="form-group">
                  <label class="font-weight-normal" for="question">Question</label>
                  <textarea name="question" id="question" class="form-control texteditor"><?php echo $task->jenis_task == 'multiple-choices'?$task_json['question']:''; ?></textarea>
                </div>

                <table class="w-100">
                  <tr>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_a">Option A</label>
                        <input type="text" name="option_a" id="option_a" class="form-control" value="<?php echo $task->jenis_task == 'multiple-choices'?$task_json['option_a']:''; ?>" />
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_b">Option B</label>
                        <input type="text" name="option_b" id="option_b" class="form-control" value="<?php echo $task->jenis_task == 'multiple-choices'?$task_json['option_b']:''; ?>" />
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_c">Option C</label>
                        <input type="text" name="option_c" id="option_c" class="form-control" value="<?php echo $task->jenis_task == 'multiple-choices'?$task_json['option_c']:''; ?>" />
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <label class="font-weight-normal" for="option_d">Option D</label>
                        <input type="text" name="option_d" id="option_d" class="form-control" value="<?php echo $task->jenis_task == 'multiple-choices'?$task_json['option_d']:''; ?>" />
                      </div>
                    </td>
                  </tr>
                </table>

                <div class="mt-2">
                  <button class="btn btn-sm e-btn1 mt-2 rounded-0" name="submit" type="submit">Save</button>
                  <button data-id="<?php echo $task->sub_kelas_id; ?>" class="btn btn-sm e-btn2 mt-2 rounded-0 btn-cancel" name="cancel" type="button">Cancel</button>
                </div>

              <?php echo form_close(); ?>
            </div>
            <div class="tab-pane fade <?php echo $tab_active == 'fill-the-blank' ? 'show active' : ''; ?>" id="nav-fill-the-blank" role="tabpanel" aria-labelledby="nav-fill-the-blank-tab">
              <?php echo form_open('teacher/task/save/'.$task->sub_kelas_id); ?>

                <?php
                if($task->jenis_task == "fill-the-blank")
                  $task_json = json_decode($task->form_task,true); ?>

                <input type="hidden" name="content_type" value="fill-the-blank">
                <input type="hidden" id="json_data" name="json_data" value='<?php echo $task->form_task; ?>'>
                <input type="hidden" name="task_id" value="<?php echo $task->task_id; ?>" />
                <input type="hidden" name="count_array" value="<?php echo $task->jenis_task == 'fill-the-blank'?count($task_json) +1:''; ?>" />

                <div id="dynamic_field">
                        
                  <div id="row0" class="form-group">
                    <label class="font-weight-normal" for="intruksi">Instruction</label>
                    <textarea name="intruksi" id="intruksi" class="form-control texteditor"><?php echo $task->jenis_task == 'fill-the-blank'?$task->content:''; ?></textarea>
                    <span class="badge badge-primary cursor-pointer" id="badge_add_form"><i class="fas fa-plus"></i> Add Form</span>
                  </div>

                  <?php

                  if($task->jenis_task == "fill-the-blank"):
                  
                    foreach ($task_json as $key => $value) {
                      
                      echo '<div id="'.str_replace('field_','row',$key).'" class="form-group">
                        <div class="input-group">
                          <input type="text" name="'.$key.'" class="form-control" />
                          <div class="input-group-append">
                            <span class="input-group-text">{'.$key.'}</span>
                          </div>
                          <button class="btn btn-danger rounded-0 ml-2 btn_remove" data-id="'.str_replace('field_','',$key).'" type="button">X</button>
                        </div>
                      </div>';

                    }

                  endif;

                  ?>

                </div>

                

                <div class="mt-2">
                  <button class="btn btn-sm e-btn1 mt-2 rounded-0" name="submit" type="submit">Save</button>
                  <button data-id="<?php echo $task->sub_kelas_id; ?>" class="btn btn-sm e-btn2 mt-2 rounded-0 btn-cancel" name="cancel" type="button">Cancel</button>
                </div>

              <?php echo form_close(); ?>
            </div>

            
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
    var i = $('#nav-fill-the-blank input[name="count_array"]').val();

    $('#badge_add_form').click(function(){  
      i++;             
      $('#dynamic_field').append(`<div id="row`+i+`" class="form-group">
        <div class="input-group">
          <input type="text" name="field_`+i+`" class="form-control" />
          <div class="input-group-append w-20">
            <span class="input-group-text">{field_`+i+`}</span>
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

    $('.btn-cancel').on('click', function(e) {
      e.preventDefault(); 
      var sub_kelas_id = $(this).data('id');
      location.href = base_url + 'teacher/learning/index/' + sub_kelas_id;
    });  
    
  }); 
</script>



<?php $this->load->view("teacher/kelas_list_js"); ?>


