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
    echo input_name_toJson_by_user($template, $field_array, $answer, $task->task_id);
    echo "</div>";
  }



  $no++;

}

?>

<div id="loading-overlay" class="overlay" style="display: none;">
  <i class="fas fa-2x fa-sync-alt"></i>
</div>