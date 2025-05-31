<span class="sidebar-toggle-btn">
  <i class="fas fa-angle-double-left"></i>
</span>
<div class="pl-3 pr-2" id="class_list" style="margin-top: 25px;">
  <?php foreach ($record as $kelas) {
    echo "<div class='class-box mb-4 pb-2'>";
      echo "<div class='clearfix'>";
        echo "<h4> <span class='badge rounded-pill bg_custom_1 float-left mb-2 pr-3 cursor-pointer class_detail' data-id='$kelas[kelas_id]'><i class='fas fa-circle fa-2xs mr-2'></i> <span class='text-hide-collapsed'>$kelas[nama_kelas]</span></span></h4><span class='class-collaps-class'><i class='fas fa-chevron-circle-down'></i></span>";
        /*echo "<div class='btn-group float-right'>
          <button class='btn btn-success btn-sm btn-xs class_detail' data-id='$kelas[kelas_id]'><i class='fas fa-eye'></i></button>
          <button class='btn btn-secondary btn-sm btn-xs class_lock_unlock' data-id='$kelas[kelas_id]' data-status='$kelas[tutup]'>";
          echo $kelas['tutup'] == 1 ? "<i class='fas fa-lock-open'></i>":"<i class='fas fa-lock'></i>";
          echo "</button>
          <button class='btn btn-danger btn-sm btn-xs class_dell' data-id='$kelas[kelas_id]'><i class='fas fa-trash'></i></button>
        </div>";*/
      echo "</div>";
      echo "<span class='badge' style='background:#BDAEAE;'><i class='fas fa-circle fa-2xs mr-2'></i> <span class='text-hide-collapsed'>$kelas[materi]</span></span>";
      
      foreach ($kelas['sub_kelas'] as $sub_kelas):
        echo "<a href='".base_url()."teacher/learning/index/$sub_kelas[sub_kelas_id]' class='btn btn-sm btn-xs btn-block mt-2 shadow-sm' id=''><i class='fas fa-chalkboard-teacher mr-2'></i> <span class='text-hide-collapsed'>$sub_kelas[nama_sub_kelas]</span></a>";
      endforeach;

      echo "<div class='float-right'>
        <a class='btn btn-sm btn-xs btn-block mt-2 text-info add_course' data-id='$kelas[kelas_id]'><img src='".base_url()."assets/img/icon/1.png' width='20'></a>
      </div>";

      // echo "<button class='btn btn-secondary btn-sm btn-xs ml-auto p-0 mt-2 add_saja' data-id='$kelas[kelas_id]'><i class='fas fa-plus'></i></button>";
      
    echo "</div>";
  } ?>
</div>

<div class="clearfix">
  <p class="text-center">
    <button class="btn btn-primary btn-sm mt-2 mb-3 ml-3" id="add_class"><i class="fas fa-plus-circle"></i><span class='text-hide-collapsed'>Add Class</span></button>
  </p>
</div>