<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_controller extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->load->model('Chat_model');
	}

	public function index()
	{
		$this->load->view('register');
	}
    public function register()
	{
        $image = null;
        // print_r($_FILES['img']['name']);die;
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[chat_users.email]');
		$this->form_validation->set_rules('mobile','Mobile','required|is_unique[chat_users.mobile]');
		$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[8]');
		$this->form_validation->set_rules('cpassword','Confirm Password','required|matches[password]');
		$this->form_validation->set_rules('address','Address','required');
		$this->form_validation->set_rules('gender','Gender','required');
        $this->form_validation->set_rules('img','image','callback_img_error');
        if($this->form_validation->run()==false){
            $response=[
                'status'=>'error',
                'error'=>$this->form_validation->error_array(),
            ];
        }else{
            $token = bin2hex(random_bytes(32));
            $name=$this->security->xss_clean($this->input->post('name'));
            $email=$this->security->xss_clean($this->input->post('email'));
            $mobile=$this->security->xss_clean($this->input->post('mobile'));
            $password=$this->security->xss_clean($this->input->post('password'));
            $address=$this->security->xss_clean($this->input->post('address'));
            $gender=$this->security->xss_clean($this->input->post('gender'));

            $data = array(
                'name'=> $name,
                'email'=>$email,
                'mobile'=>$mobile,
                'password'=>password_hash($password, PASSWORD_BCRYPT),
                'address'=>$address,
                'gender'=>$gender,
                'img'=>$this->image,
                'token'=> $token 

            );

            $users = $this->Chat_model->register($data);
            if($users){
                $response=[
                    'status'=>'success',
                    'message'=>'User register successfully.',
                ];
            }
        }
        echo json_encode($response);
	}

    public function img_error()
	{
        // print_r($_FILES['img']['name']);die;
		if(!empty($_FILES['img']['name'])){
            $config['upload_path'] = "./uploads/";
            $config['allowed_types']= "jpg|png|jpeg";
            $config['max_size']= 2048;
            $config['file_name']= time()."_".$_FILES['img']['name'];

            $this->load->library('upload',$config);
            if($this->upload->do_upload('img')){
                $upload_data=$this->upload->data();
                $this->image = "uploads/".$upload_data['file_name'];
                return true;
            }else{
                $this->form_validation->set_message('img_error', $this->upload->display_errors('',''));
                return false;
            }
        }else{
            $this->form_validation->set_message('img_error', 'Please Select image');
            return false;
        }
	}
    
	public function login_form()
	{
		$this->load->view('login');
	}

    public function user_login()
	{
		$this->form_validation->set_rules('email','Email','required|valid_email');
		$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[8]');
        if($this->form_validation->run()==false){
            $response=[
                'status'=>'error',
                'error'=>$this->form_validation->error_array(),
            ];
        }else{

            $field = 'email';
            $identity=$this->security->xss_clean($this->input->post('email'));
            $password=$this->security->xss_clean($this->input->post('password'));
            $users = $this->Chat_model->login($field,$identity,$password);
        
            if($users){
                $this->session->set_userdata('users',$users);
                 $this->Chat_model->status_update($_SESSION['users']->id,1);
                $response=[
                    'status'=>'success',
                    'message'=>'User Login successfully.',
                    'redirect'=> base_url('dashboard'),
                ];
            }else{
                $response=[
                    'status'=>'unauthorized',
                    'message'=>'Credential Not Matched.',
                    // 'redirect'=> base_url('dashboard'),
                ];
            }
        }
        echo json_encode($response);
	}

    public function dashboard(){
        // echo "Hello dashboard";
        // print_r($_SESSION['users']);
 if(!$this->session->userdata('users')){
    redirect('login');
 }else{

    $data['active_user'] = $this->Chat_model->active_user();
    $data['lists'] = $this->Chat_model->list();
    $this->load->view('dashboard',$data);
 }      
}


public function start(){
    // echo "Hello dashboard";
    // print_r($_POST['id']);die;
if(!$this->session->userdata('users')){
redirect('login');
}else{
    $user_id = $_POST['id'];
    // Manage Request 
    // print_r($this->input->post('status'));die;
    if(!empty($this->input->post('status'))){

        if($this->input->post('status')==1){
            $data = array(
                'sender_id'=>$_SESSION['users']->id,
                'reciever_id'=>$user_id,
                'status'=>$this->input->post('status'),
            );

            $req_status = $this->Chat_model->req_status($data);
            $response = [
                'status'=>'success',
                'message'=>'Request Send Successfully',
                // 'redirect'=>base_url('chat/' . $user_id),
            ]; 
        }else{
            
                $sender_id =$_SESSION['users']->id;
                $reciever_id = $user_id;
                $status = $this->input->post('status');
          

            $req_status = $this->Chat_model->update_req_status($sender_id, $reciever_id, $status);
            if($status == 2){
                $response = [
                    'status'=>'success',
                    'message'=>'Request accepted',
                    // 'redirect'=>base_url('chat/' . $user_id),
                ]; 
            }else{
                $this->db->where("(sender_id={$sender_id} AND reciever_id ={$reciever_id})")->delete('request');
                // print_r($this->db->last_query());die;
                $response = [
                    'status'=>'success',
                    'message'=>'Request Rejected',
                    // 'redirect'=>base_url('chat/' . $user_id),
                ]; 
            }
           
        }
    }else{

        $user = $this->Chat_model->get_user($user_id);
if($user){
    $response = [
        'status'=>'success',
        'redirect'=>base_url('chat/' . $user_id),
    ];
}else{
    $response = [
        'status'=>'error',
        'message'=>'User not found',
    ];
}
    }

echo json_encode($response);
}
}


public function chat($user_id){
    // echo "Hello dashboard";
    // print_r($_POST['id']);die;
if(!$this->session->userdata('users')){
redirect('login');
}else{
 $sender_id = $_SESSION['users']->id;
$data['user'] = $this->Chat_model->get_user($user_id);
$data['messages'] = $this->Chat_model->get_msg($sender_id, $user_id);
// print_r($data['messages']);die;
$this->load->view('chat',$data);
}

}

public function get_msg(){
    // echo "Hello dashboard";
    // print_r($_POST['id']);die;
if(!$this->session->userdata('users')){
redirect('login');
}else{
 $receiver_id = $_GET['receiver_id'];
 $sender_id = $_SESSION['users']->id;
// $data['user'] = $this->Chat_model->get_user($user_id);
$messages = $this->Chat_model->get_msg($sender_id, $receiver_id);
// print_r($messages);die;
if($messages){
    $response = [
        'status'=> 'success',
        'data' => $messages,
    ];
}
}
echo json_encode($response);
}

public function send_message()
{
    $this->form_validation->set_rules('message','Message','required');
    if($this->form_validation->run()==false){
        $response=[
            'status'=>'error',
            'error'=>$this->form_validation->error_array(),
        ];
    }else{
        $message = $this->security->xss_clean($this->input->post('message'));
        $sender_id = $this->security->xss_clean($this->input->post('sender_id'));
        $reciever_id = $this->security->xss_clean($this->input->post('reciever_id'));

        $data = array(
            'message'=> $message,
            'sender_id'=>$sender_id,
            'reciever_id'=>$reciever_id,
        );

        $users = $this->Chat_model->sendMessage($data);
        if($users){
            $response=[
                'status'=>'success',
                // 'redirect'=>base_url('start'),
            ];
        }
    }
    echo json_encode($response);
}


    public function logout(){

        $this->Chat_model->status_update($_SESSION['users']->id,0);
        $this->session->unset_userdata('users');

        $this->session->sess_destroy();
        redirect('login');

}

public function get_profile(){

   $data['active_user'] = $this->Chat_model->active_user();
   $this->load->view('update_profile',$data);

}

public function update_profile()
{
    $image = null;
    // print_r($_FILES['img']['name']);die;
    $this->form_validation->set_rules('name','Name','required');
    $this->form_validation->set_rules('address','Address','required');
    $this->form_validation->set_rules('gender','Gender','required');
    if(!empty($_FILES['img']['name'])){

        $this->form_validation->set_rules('img','image','callback_img_error');
    }else{
        $this->image = $this->input->post('old_img');
    }
    if($this->form_validation->run()==false){
        $response=[
            'status'=>'error',
            'error'=>$this->form_validation->error_array(),
        ];
    }else{
        $id=$this->security->xss_clean($this->input->post('id'));
        $name=$this->security->xss_clean($this->input->post('name'));
        $address=$this->security->xss_clean($this->input->post('address'));
        $gender=$this->security->xss_clean($this->input->post('gender'));

        $data = array(
            'name'=> $name,
            'address'=>$address,
            'gender'=>$gender,
            'img'=>$this->image

        );

        $users = $this->Chat_model->update_profile($id, $data);
        if($users){
            $response=[
                'status'=>'success',
                'message'=>'User Profile update successfully.',
            ];
        }
    }
    echo json_encode($response);
}

public function change_password()
{
    $this->form_validation->set_rules('old_password','Old Password','required|min_length[6]|max_length[8]');
    $this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[8]');
    $this->form_validation->set_rules('cpassword','Confirm Password','required|matches[cpassword]');
    if($this->form_validation->run()==false){
        $response=[
            'status'=>'error',
            'error'=>$this->form_validation->error_array(),
        ];
    }else{
        $id=$this->security->xss_clean($this->input->post('id'));
        $password=$this->security->xss_clean($this->input->post('password'));
        $old_password=$this->security->xss_clean($this->input->post('old_password'));
        $check = $this->Chat_model->login('id',$id,$old_password);
        if(!empty($check)){

            $data = array(
                'password'=>password_hash($password, PASSWORD_BCRYPT),
                
    
            );
    
            $users = $this->Chat_model->update_profile($id, $data);
            if($users){
                $response=[
                    'status'=>'success',
                    'message'=>'Change password successfully.',
                ];
            }
        }else{
            $response=[
                'status'=>'success',
                'message'=>'Old Password not matched.',
            ];
        }
    }
    echo json_encode($response);
}
}