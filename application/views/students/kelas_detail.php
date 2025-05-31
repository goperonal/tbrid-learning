<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("students/kelas_list"); ?>
      
    </div>

    <div class="col-sm-5 p-4" style="background: #D6D6D6;">

      <table class="w-100 font-weight-bold">
        <tr>
          <td width="60%" class="bg_custom_3 pl-4 py-1">Class code</td>
          <td width="5%"></td>
          <td width="35%" align="center" class="bg_custom_3">
            <?php echo $oRecord->kelas_id; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td>
            <img src="<?php echo base_url(); ?>assets/img/icon/3.png" class="float-right" width="20">
          </td>
        </tr>
        <tr>
          <td class="bg_custom_3 pl-4 py-1">Lock Class</td>
          <td></td>
          <td>
            <div class="btn-group">
              <button type="button" class="btn <?php echo $oRecord->tutup == 1 ? "btn-secondary":"btn-primary";?> btn-sm class_lock_unlock" data-id="<?php echo $oRecord->kelas_id; ?>" data-status="<?php echo $oRecord->tutup; ?>" disabled>Yes</button>
              <button type="button" class="btn <?php echo $oRecord->tutup == 2 ? "btn-secondary":"btn-primary";?> btn-sm class_lock_unlock" data-id="<?php echo $oRecord->kelas_id; ?>" data-status="<?php echo $oRecord->tutup; ?>" disabled>No</button>
            </div>
          </td>
        </tr>
        <tr>
          <td class="py-2" colspan="3"></td>
        </tr>
        <tr>
          <td class="bg_custom_3 pl-4 py-1">Number of Students</td>
          <td></td>
          <td align="center" class="bg_custom_3"><?php echo $mum_of_students->num_rows(); ?></td>
        </tr>
      </table>

    </div>

    <div class="col-sm-5 p-4" style="background: #E4E2E0;">
      <h4 class="mt-2 mb-4">
        <img src="<?php echo base_url(); ?>assets/img/icon/2.png" width="25"> List of Students
      </h4>

      <table id="students_ajax_datatables" class="table table-sm w-100 mt-4" style="background: #D2D2D2;">
        <thead>
          <tr class="bg-light">
            <th>#</th>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- <div class="col-sm-1 bg-white"></div> -->
  </div>
</div>

<?php $this->load->view("students/kelas_list_js"); ?>

