<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Zoom extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('zoom_lib');
        $this->load->model('common_model');
    }

    public function index()
    {
        // $this->authorize();
        // $this->load->view('welcome');
        redirect('zoom/video_conference');
    }

    public function getUserID() {
        $this->load->library('Zoom_lib');

        $email = 'aliakbaruncp@gmail.com';
        $user_id = $this->zoom_lib->get_user_id($email);

        echo "User ID for $email is $user_id";
    }

    public function authorize() {
        $auth_url = $this->zoom_lib->get_auth_url();
        redirect($auth_url);
        // pr($auth_url);
    }

    public function callback() {
        $code = $this->input->get('code');
        $message = $this->zoom_lib->request_access_token($code);
        // echo $message;
        pr($message);
    }

    public function create_meeting() {
        $topic = "Belajar CodeIgniter";
        $start_time = "2024-12-05T20:30:00";
        $duration = 30; // 30 mins
        $password = "123456";

        $meeting_info = $this->zoom_lib->create_meeting($topic, $start_time, $duration, $password);
        pr($meeting_info);
    }

    public function list_meetings() {
        $meetings = $this->zoom_lib->list_meetings();

        if (is_array($meetings)) {
            echo "<pre>";
            print_r($meetings);
            echo "</pre>";
        } else {
            echo "Error: " . $meetings;
        }
    }

    public function delete_meeting($meeting_id) {
        $this->zoom_lib->delete_meeting($meeting_id);
        echo "Meeting deleted successfully.";
    }




    public function getZoomConfig() {
        // $this->load->library('Zoom_lib');

        // $user_id = 'me';  // atau ID pengguna Zoom spesifik
        $email = 'aliakbaruncp@gmail.com';
        $user_id = $this->zoom_lib->get_user_id($email);
        $meetingDetails = $this->zoom_lib->create_meeting('Topic', '2024-09-04T15:30:00', 30, '123456');

        $config = [
            'authEndpoint' => base_url('zoom/auth'),
            'sdkKey' => $this->config->item('zoom_sdk_key'),
            'meetingNumber' => $meetingDetails['id'],
            'passWord' => $meetingDetails['password'],
            'role' => 0, // 0 for participant, 1 for host
            'userName' => 'JavaScript',
            'userEmail' => 'user@example.com',
            'registrantToken' => '',
            'zakToken' => $this->zoom_lib->get_zak_token($user_id),
            'leaveUrl' => 'https://yourdomain.com/leave'
        ];

        // echo json_encode($config);
        pr($config);
    }








    public function create_zoom_user() {
        $email = "user@example.com";
        $first_name = "John";
        $last_name = "Doe";

        $result = $this->zoom_lib->create_zoom_user($email, $first_name, $last_name);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function assign_role() {
        $user_id = "user_id_from_zoom";
        $role_id = "role_id_from_zoom";

        $result = $this->zoom_lib->assign_role($user_id, $role_id);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function get_zoom_user_list() {
        $result = $this->zoom_lib->get_zoom_user_list();
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function get_zoom_user_role_list() {
        $result = $this->zoom_lib->get_zoom_user_role_list();
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }






    public function video_conference()
    {
        $user_name = $this->session->user_name;
        $user_email = $this->session->user_email;
        $duration = 30;
        $zoom_user_id = ''; 
        $client_id = $meeting_id = $meeting_password = $signature = $zak_token = '';
        
        if($this->session->level_akses == 'teacher'):
            $host = 1;
        else:
            $host = 0;
        endif;

        $active_conference = get_active_conference();
        if (!empty($active_conference)) {
            $meetings = $active_conference[0];
            $meeting_id = $meetings['vc_room_id'];
            $meeting_password = $meetings['vc_room_password'];
            $duration = $meetings['vc_duration'];
        }

        if ($meeting_id != '' && $duration > 0) {

            if ($host == 1) {
                $zak_token = generate_zoom_access_key($zoom_user_id);
            }

            $client_id = $this->zoom_lib->zoom_sdk_client_id();
            //required for start and join meeting
            $signature = $this->zoom_lib->generate_signature($meeting_id, $host, $duration); 
        }
        
        //pass the data to the view
        $data['client_id'] = $client_id;
        $data['signature'] = $signature;
        $data['meeting_id'] = $meeting_id;
        $data['meeting_password'] = $meeting_password;
        $data['zak_token'] = $zak_token;
        $data['host'] = $host;
        $data['user_name'] = $user_name;
        $data['user_email'] = $user_email;
        $this->load->view('video_conference', $data);
    }

    function create_room()
    {
        $this->form_validation->set_rules('title', 'Title', 'trim|required|alpha_numeric_spaces|max_length[100]');
        $this->form_validation->set_rules('duration', 'Duration', 'trim|required|integer');

        if ($this->form_validation->run() == false) {
            $errors = validation_errors();
            $this->session->set_userdata('form-fail', $errors);
            redirect('zoom/video_conference');
        } else {
            $title = $this->input->post('title');
            $duration = $this->input->post('duration');
            $zoom_user_id = ''; //you can get from zoom admin account 
            $meeting_data = create_zoom_meeting($title, $duration, $zoom_user_id); //create zoom meeting using zoom api
            //check data return by zoom api is not empty and save it in database
            if (!empty($meeting_data)) {
                $room_id = $meeting_data['id'];
                $host_id = $meeting_data['host_email'];
                $password = $meeting_data['encrypted_password'];
                // set default timezone. You can configure timezone in config file for whole application
                date_default_timezone_set('Asia/Makassar');
                $start_time = date('Y-m-d H:i:s');
                $end_time = date('Y-m-d H:i:s', strtotime('+' . $duration . ' minute'));    //creating meeting expiery time using duration to check later whether meeting is active or not

                $data = array(
                    'vc_host_id' => $host_id,
                    'vc_room_id' => $room_id,
                    'vc_room_password' => $password,
                    'vc_title' => $title,
                    'vc_duration' => $duration,
                    'vc_start_time' => $start_time,
                    'vc_end_time' => $end_time,
                    'api_response' => json_encode($meeting_data),
                );
                $table = 'video_conference';
                $result = $this->common_model->insert($data, $table);   //save meeting data into table
                if ((int)$result > 0) {
                    $this->session->set_userdata('form-success', 'Meeting Successfully Added');
                } else {
                    $this->session->set_userdata('form-fail', 'Something Went Wrong! Please Try Again');
                }
            }
            sleep(2);   //sleep for 2 seconds to ensure meeting status is activated
            redirect('zoom/video_conference');
        }
    }
}
