<div class="wrapper containers bg_content">
  <div class="row">
    <div class="col-sm-2 sidebar">
      
      <?php $this->load->view("teacher/kelas_list"); ?>
      
    </div>

    <div class="col-sm-9">
      
      <div class="row">
        <div class="container">
          <h1 class="my-4">Add task</h1>
          <div class="card">
            <div class="card-header border-bottom-0">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Content</button>
                  <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Multiple Choices</button>
                  <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Fill the blank</button>
                  <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Short Answer</button>
                </div>
              </nav>
            </div>
            <div class="card-body">
              

              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">Home</div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">Profile</div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">Contact</div>
              </div>


            </div>
          </div>
        </div>
      </div>



      
      
    </div>




  </div>
</div>

<?php $this->load->view("teacher/kelas_list_js"); ?>

