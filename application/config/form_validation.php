<?php

$config['teacher/learning/send_chat'] = array(
  array(
    'field' => 'live_chat_message',
    'label' => 'message',
    'rules' => 'required|trim|min_length[2]|max_length[300]'
  )
);

// ==========================================
// PROFILE

$config['profile/update_proses'] = array(
  array(
    'field' => 'nama_depan',
    'label' => 'First name',
    'rules' => 'required|trim|max_length[10]'
  ),
  array(
    'field' => 'nama_belakang',
    'label' => 'Last name',
    'rules' => 'trim|max_length[10]'
  ),
  array(
    'field' => 'email',
    'label' => 'Email',
    'rules' => 'required|trim|valid_email|max_length[100]'
  ),
  array(
    'field' => 'password',
    'label' => 'Password',
    'rules' => 'trim|min_length[5]'
  ),
  array(
    'field' => 'konfirmasi_password',
    'label' => 'Confirm password',
    'rules' => 'trim|matches[password]'
  )
);

// ==========================================
// AUTH

$config['auth/regist_proses'] = array(
  array(
    'field' => 'nama_depan',
    'label' => 'First name',
    'rules' => 'required|trim|max_length[10]'
  ),
  array(
    'field' => 'nama_belakang',
    'label' => 'Last name',
    'rules' => 'trim|max_length[10]'
  ),
  array(
    'field' => 'institusi',
    'label' => 'Istitutions',
    'rules' => 'required|trim|max_length[100]'
  ),
/*  array(
    'field' => 'nim',
    'label' => '',
    'rules' => 'callback_nim_check'
  ),*/
  array(
    'field' => 'email',
    'label' => 'Email',
    'rules' => 'required|trim|valid_email|is_unique[akun.email]|max_length[100]'
  ),
  array(
    'field' => 'password',
    'label' => 'Password',
    'rules' => 'required|trim|min_length[5]'
  ),
  array(
    'field' => 'confirm_password',
    'label' => 'Confirm password',
    'rules' => 'required|trim|matches[password]'
  ),
  array(
    'field' => 'level',
    'label' => 'Level access',
    'rules' => 'required|trim'
  )
);

$config['auth/login_proses'] = array(
  array(
    'field' => 'email',
    'label' => 'Email',
    'rules' => 'required|trim|valid_email|max_length[100]'
  ),
  array(
    'field' => 'password',
    'label' => 'Password',
    'rules' => 'required|trim|min_length[5]'
  )
);

// =================================================
// COURSE

$config['teacher/course/index'] = array(
  array(
    'field' => 'course',
    'label' => 'Course',
    'rules' => 'callback_course_check'
  )
);

// =================================================
// TEACHER learning_class

$config['teacher/e_class/add_class'] = array(
  array(
    'field' => 'nama_kelas',
    'label' => 'Class name',
    'rules' => 'callback_nama_kelas_check'
  ),
  array(
    'field' => 'materi',
    'label' => 'Course',
    'rules' => 'required|trim|min_length[5]|max_length[45]'
  )
);

$config['teacher/e_class/update_password'] = array(
  array(
    'field' => 'password',
    'label' => 'Password',
    'rules' => 'required|trim|min_length[5]'
  ),
  array(
    'field' => 'konfirmasi_password',
    'label' => 'Confirm password',
    'rules' => 'required|trim|matches[password]'
  ),
);

$config['teacher/learning/add_learning'] = array(
  array(
    'field' => 'learning_goal',
    'label' => 'Learning goal',
    'rules' => 'required|trim|min_length[5]|max_length[300]'
  ),
  array(
    'field' => 'topic',
    'label' => 'Topic',
    'rules' => 'required|trim|min_length[5]|max_length[100]'
  )
);

$config['teacher/learning/create_room'] = array(
  array(
    'field' => 'zoom_title',
    'label' => 'Title',
    'rules' => 'required|trim|max_length[100]'
  ),
  array(
    'field' => 'zoom_start_time',
    'label' => 'Start time',
    'rules' => 'required|trim'
  ),
  array(
    'field' => 'zoom_duration',
    'label' => 'Duration',
    'rules' => 'required|trim|max_length[100]'
  )
);

$config['teacher/assignment/add_assignment'] = array(
  array(
    'field' => 'assignment',
    'label' => 'Assignment',
    'rules' => 'required|trim'
  ),
  /*array(
    'field' => 'assignment_intruksi',
    'label' => 'Intruksi',
    'rules' => 'required|trim|min_length[5]|max_length[100]'
  ),*/
  array(
    'field' => 'tanggal_mulai',
    'label' => 'Due date',
    'rules' => 'required|trim'
  ),
  array(
    'field' => 'tanggal_akhir',
    'label' => 'Due date',
    'rules' => 'required|trim'
  ),
  array(
    'field' => 'total_score',
    'label' => 'Total score',
    'rules' => 'required|trim|numeric|min_length[2]|max_length[3]'
  )
);

// =================================================
// STUDENTS learning_class

$config['students/join_class'] = array(
  array(
    'field' => 'kode_kelas',
    'label' => 'Class Code',
    'rules' => 'required|trim|numeric|min_length[2]|max_length[100]'
  ),
  array(
    'field' => 'nim',
    'label' => 'NIM',
    'rules' => 'required|trim|numeric|min_length[8]|max_length[12]'
  )
);

$config['students/add_learning'] = array(
  array(
    'field' => 'learning_goal',
    'label' => 'Learning goal',
    'rules' => 'required|trim|min_length[5]|max_length[300]'
  ),
  array(
    'field' => 'topic',
    'label' => 'Topic',
    'rules' => 'required|trim|min_length[5]|max_length[100]'
  )
);

$config['error_prefix'] = '';
$config['error_suffix'] = '';