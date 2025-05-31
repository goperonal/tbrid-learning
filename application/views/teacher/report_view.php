<div id="main" class="wrapper containers bg_content">
  <div class="row">
    <div id="sidebar" class="col-sm-2 sidebar report-sidebar">
      <span class="sidebar-toggle-btn">
        <i class="fas fa-angle-double-left"></i>
      </span>
      <div class="pl-3 pr-2" id="class_list" style="margin-top: 25px;">
        <?php foreach ($record as $kelas) {
          echo "<div class='class-box pb-2'>";
            echo "<div class='clearfix'>";
              echo "<h4> <span class='badge rounded-pill bg_custom_1 float-left mb-2 pr-3 cursor-pointer report-kelas-id' data-id='$kelas[kelas_id]'><i class='fas fa-circle fa-2xs mr-2'></i> <span class='text-hide-collapsed'>$kelas[nama_kelas]</span></span></h4>";
            echo "</div>";
          echo "</div>";
        } ?>
      </div>
      
    </div>

    <div id="content" class="col-sm-10 bg-light py-2 px-4">
      <!-- <h1 class="mb-4"></h1> -->

      <style type="text/css">
        .page-link {
            padding: .4rem .65rem;
            background-color: #eee;
            color: black;
            border: 0;
        }
        .page-item:last-child .page-link {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
      </style>
      
      <div class="clearfix" style="margin-top: 30px;">
        <div class="float-left">
          <div class="pagination-links">
              <?= $pagination ?>
          </div>
        </div>
        <a href="<?= base_url('teacher/report/export_to_excel/' . $this->uri->segment(4)) ?>" class="btn btn-success btn-sm rounded-fill float-right">Export</a>
      </div>

      <style type="text/css">
        table#assignment_ajax_datatables tr th,
        table#assignment_ajax_datatables tr td{
          border: 1px solid white;
        }
      </style>
      
      <table id="assignment_ajax_datatables" class="table table-sm w-100 mt-2">
          <thead>
              <tr>
                  <th>NO</th>
                  <?php if (!empty($laporan['assignments'])): ?>
                      <th><?= strtoupper($laporan['assignments'][0]['nama_kelas']) ?></th>
                  <?php else: ?>
                      <th>NAMA KELAS</th>
                  <?php endif; ?>
                  
                  <?php foreach ($laporan['assignments'] as $assignment): ?>
                      <th style="text-align: center;"><?= $assignment['assignment'] ?></th>
                  <?php endforeach; ?>
                  <th style="text-align: center;">Average</th>
              </tr>
          </thead>
          <tbody>
              <?php 
              $no = 1;
              if (!empty($laporan['mahasiswa'])):
                  foreach ($laporan['mahasiswa'] as $mhs): ?>
                      <tr>
                          <td><?= $no++ ?></td>
                          <td><?= $mhs['nama'] ?></td>

                          <?php 
                          $total_nilai = 0;
                          $jumlah_assignment = count($laporan['assignments']);
                          
                          foreach ($laporan['assignments'] as $assignment): 
                              // Mencari nilai untuk assignment ini
                              $nilai = isset($mhs['nilai'][$assignment['assignment_id']]) ? $mhs['nilai'][$assignment['assignment_id']] : 0;
                              $total_nilai += $nilai;
                          ?>
                              <td align="center"><?= $nilai ?></td>
                          <?php endforeach; ?>

                          <td align="center"><?= $jumlah_assignment > 0 ? round($total_nilai / $jumlah_assignment, 2) : 0 ?></td>
                      </tr>
                  <?php endforeach;
              else: ?>
                  <tr>
                      <td colspan="<?= count($laporan['assignments']) + 3 ?>">Tidak ada data mahasiswa atau assignment</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>



      <!-- Pagination links -->
      <div class="pagination-links">
          <?= $pagination ?>
      </div>



    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function(){

    $('.report-kelas-id').on('click', function(e) {
      e.preventDefault();
      var kelas_id = $(this).data('id');
      location.href = base_url + 'teacher/report/index/' + kelas_id;
    });
  });
  
</script>

<?php $this->load->view("teacher/kelas_list_js"); ?>

