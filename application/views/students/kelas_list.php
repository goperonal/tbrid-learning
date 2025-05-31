<div class="pl-3 pr-2" id="class_list" style="margin-top: 25px;">
  <?php foreach ($record as $kelas) {
    echo "<div class='class-box mb-4 pb-2'>";
      echo "<div class='clearfix'>";
        echo "<h4> <span class='badge rounded-pill bg_custom_1 float-left mb-2 pr-3 cursor-pointer class_detail' data-id='$kelas[kelas_id]'><i class='fas fa-circle fa-2xs mr-2'></i> $kelas[nama_kelas]</span></h4>";
        /*echo "<div class='btn-group float-right'>
          <button class='btn btn-success btn-sm btn-xs class_detail' data-id='$kelas[kelas_id]'><i class='fas fa-eye'></i></button>
          <button class='btn btn-secondary btn-sm btn-xs class_lock_unlock' data-id='$kelas[kelas_id]' data-status='$kelas[tutup]'>";
          echo $kelas['tutup'] == 1 ? "<i class='fas fa-lock-open'></i>":"<i class='fas fa-lock'></i>";
          echo "</button>
          <button class='btn btn-danger btn-sm btn-xs class_dell' data-id='$kelas[kelas_id]'><i class='fas fa-trash'></i></button>
        </div>";*/
      echo "</div>";
      echo "<span class='badge' style='background:#BDAEAE;'><i class='fas fa-circle fa-2xs mr-2'></i> $kelas[materi]</span>";
      
      foreach ($kelas['sub_kelas'] as $sub_kelas):
        echo "<a href='".base_url()."students/learning/index/$sub_kelas[sub_kelas_id]' class='btn btn-sm btn-xs btn-block mt-2 shadow-sm' id=''><i class='fas fa-chalkboard-teacher mr-2'></i> $sub_kelas[nama_sub_kelas]</a>";
      endforeach;
      
    echo "</div>";
  } ?>
</div>

<div class="clearfix">
  <p class="text-center">
    <button class="btn btn-primary btn-sm mt-2 mb-3" id="join_class">Join Class</button>
  </p>
</div>