<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div id="content" class="col-sm-10 py-2 px-4">
      <!-- <h1 class="mb-4"></h1> -->
      <div class="clearfix">
        <button id="create-assignment" class="btn btn-create-assignment float-right">Create Assignment</button>
      </div>

      <div class="row box-1 kelas-filter">
          <div class="col">
            <table>
              <tr>
                <td class="left">Kelas</td>
                <td><input type="text" name="kelas" id="kelas"></td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td class="left">Active</td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary btn-sm rounded-0 class_lock_unlock_assignment">Yes</button>
                    <button type="button" class="btn btn-primary btn-sm rounded-0 class_lock_unlock_assignment">No</button>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col">
            <table>
              <tr>
                <td class="left">Created</td>
                <td><input type="date" name="created_date" id="created_date"></td>
              </tr>
              <tr>
                <td colspan="2" class="p-2"></td>
              </tr>
              <tr>
                <td class="left">Due Date</td>
                <td><input type="date" name="due_date" id="due_date"></td>
              </tr>
            </table>
          </div>
      </div>
      
      <div class="card shadow-none border-0 bg-light">
        <div class="card-body bg-light">
          <table id="assignment_ajax_datatables" class="table table-sm w-100 mt-4">
            <thead>
              <tr>
                <th>No.</th>
                <th>Assignment</th>
                <th>CLass</th>
                <th>Active</th>
                <th>Created</th>
                <th>Due Date</th>
              </tr>
            </thead>
            
          </table>
        </div>
      </div>


    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    function loadDataTables() {
      var activeStatus = $('.class_lock_unlock_assignment.active').text();

      $('#assignment_ajax_datatables').DataTable().destroy();
      $('#assignment_ajax_datatables').DataTable({ 
        destroy: true,
        processing: true,
        language: {
          processing: "<i class=\"fas fa-spinner fa-pulse fa-3x\"></i>"
        },
        serverSide: true,
        order: [],
        ajax: {
          url: base_url + 'teacher/assignment/ajax_datatables',
          type: "POST",
          data: function(d) {
            d.csrf_token = $.cookie('csrf_cookie');
            d.kelas = $('#kelas').val();
            d.active = activeStatus;
            d.created_date = $('#created_date').val();
            d.due_date = $('#due_date').val();
          }
        },
        columnDefs: [
          { targets: [ 0 ], "orderable": false, "className": "text-center"},
          { targets: [ 5 ], "orderable": false, "className": "text-center"},
        ],
        preXhr: function(e, settings, data) {
          data.csrf_token = $.cookie('csrf_cookie');
        }
      });
      $('.dataTables_processing').removeClass('card');
    }

    loadDataTables();

    $('.class_lock_unlock_assignment').on('click', function() {
        $('.class_lock_unlock_assignment').removeClass('active');
        $(this).addClass('active');
        loadDataTables();
    });
    
    $('#kelas, #created_date, #due_date').on('keyup change', function() {
        loadDataTables();
    });
    

  });
</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

