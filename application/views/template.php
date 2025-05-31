<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= base_url('assets/img/tbrid-favicon.png'); ?>" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminlte.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

    <title>T-Brid Learning</title>

    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- Your custom JS file -->
    <script src="<?= base_url('assets/js/customTbrid.js') ?>"></script>

    <script> var base_url = "<?php echo base_url(); ?>" </script>


    <style type="text/css">
        #students_ajax_datatables_wrapper #students_ajax_datatables_info{
            display: none;
        }
    </style>
</head>

<body>

    <div class="containers">
        <div class="row">
            <div class="col-sm-12">
                <div id="desktop-navbar">
                    <nav class="navbar" style="background-color:#023E51;">
                        <a class="navbar-brand" href="<?php echo base_url(); ?>">
                            <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="T-Brid Learning" class="img-fluid mr-4" width="150">
                        </a>
                        <?php if($this->session->setatus_login == 'user_login'): ?>
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="<?php echo base_url('profile'); ?>">
                                        <img src="<?php echo base_url(); ?>assets/img/icon/5.png" alt="T-Brid Learning" class="img-fluid ml-4" width="35"> <span class="user_name"><?php echo get_login_name(); ?></span></a>
                                </li>
                            </ul>
                        <?php endif; ?>
                        
                        <div id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <?php

                                if($this->session->setatus_login == 'user_login'):
                                    if($this->session->level_akses == 'teacher'):
                                        echo "<a class='nav-item nav-link' href='".base_url()."teacher/e_class'>Class</a>";
                                        echo "<a class='nav-item nav-link' href='".base_url()."teacher/assignment'>Assignment</a>";
                                        echo "<a class='nav-item nav-link' href='".base_url()."teacher/report'>Report</a>";
                                    else:
                                        echo "<a class='nav-item nav-link' href='".base_url()."students/e_class'>Class</a>";
                                        echo "<a class='nav-item nav-link' href='".base_url()."students/assignment'>Assignment</a>";
                                    endif;
                                    echo "<a class='nav-item nav-link text-danger' href='".base_url()."auth/logout'>Logout</a>";
                                else:
                                    echo "<a class='nav-item nav-link' href='".base_url()."auth/login'>Log In</a>
                                    <a class='nav-item nav-link' href='".base_url()."auth'>Sign Up</a>";
                                endif;

                                ?>
                            </div>
                        </div>
                    </nav>
                </div>

                <div id="mobile-navbar">
                    <nav class="navbar navbar-expand-lg navbar-light py-1" style="background-color:#023E51;">
                        <span class="nav-link" id="hidden-sidebar">
                            <i id="menu-icon" class=" fas fa-bars fa-2x"></i>
                        </span>
                        <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="T-Brid Learning" class="img-fluid" width="150"></a>
                        <ul class="nav justify-content-end nav-mobile-content">
                            <?php if($this->session->setatus_login == 'user_login'): ?>
                                <li class="nav-item">
                                <a class="nav-link myaccount" href="<?php echo base_url(); ?>profile">
                                    <img src="<?php echo base_url(); ?>assets/img/icon/5.png" alt="<?php echo get_login_name(); ?>" class="img-fluid" width="20"><span class="user_name"><?php echo get_login_name(); ?></span></a>
                                </li>
                                <?php if($this->session->level_akses == 'teacher'): ?>
                                    <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url(); ?>teacher/e_class">Class</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url(); ?>teacher/assignment">Assignment</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url(); ?>teacher/report">Report</a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url(); ?>students/e_class">Class</a>
                                    </li>
                                    <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url(); ?>students/assignment">Assignment</a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                <a class="nav-link text-danger" href="<?php echo base_url(); ?>auth/logout">Logout</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url(); ?>auth/login">Log In</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url(); ?>auth">Sign Up</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    
    </div>


    <?php echo $contents; ?>
    

    <div id="footer" class="containers clearfix">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Contact Us</h3>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-sm-2">
                    <i class="fab fa-instagram"></i> @t-bridlearning </br>
                    <i class="fas fa-phone-square"></i> +6282327205149
                </div>
                <div class="col-sm-2">
                    <i class="fab fa-facebook-square"></i> T-Brid Learning </br>
                    <i class="fab fa-telegram fa-1x"></i> +6282327205149
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/script.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/jquery.cookie.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.session.js"></script>

    <!-- panggil ckeditor.js -->
    <script type="text/javascript" src="<?php echo base_url('plugins/ckeditor/ckeditor.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('plugins/ckeditor/adapters/jquery.js'); ?>"></script>

    <script type="text/javascript">
      CKEDITOR.replaceAll( 'texteditor', {
        filebrowserImageBrowseUrl : "<?php echo base_url('plugins/kcfinder/browse.php'); ?>",
        height: '600px',
      });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {

            
            $("#task img").addClass("img-fluid");
            $("#task table").wrap("<div class='table-responsive'></div>");
            $('#task img[src*="/elearning/plugins/kcfinder/upload/images/"]').each(function() {
                
                var imageUrl = $(this).attr('src');

                imageUrl = imageUrl.replace('/elearning', 'https://t-bridlearning.com');
                // imageUrl = imageUrl.replace('/elearning', 'http://localhost/elearning');

                $(this).attr('src', imageUrl);
            });
        });
    </script>
</body>

</html>