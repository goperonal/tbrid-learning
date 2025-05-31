<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div id="content" class="col-sm-10 py-2 px-4" style="background: #E4E2E0;">
      <h1 class="mb-4"><?php echo $title; ?></h1>
      <table id="class_ajax_datatables" class="table table-sm w-100 mt-4 bg-light">
        <thead>
          <tr class="bg-light">
            <th class="text-center">No.</th>
            <th>CLass Name</th>
            <th>Number of Students</th>
            <th>Lock Class</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<?php $this->load->view("teacher/kelas_list_js"); ?>

