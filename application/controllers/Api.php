<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Chat_model');
	}

	public function index()
	{

		/*
		username:hanni
		email:hanni12@gmail.com
		password:1234567
		cpassword:1234567
		gender:male
		address:agar
		mobile:9876542501
*/
		$image= null;
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|min_length[10]|max_length[12]|is_unique[chat_users.mobile]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[chat_users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[8]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('gender', 'Gender', 'required');
		$this->form_validation->set_rules('img', 'Profile pic', 'callback_img_error');
		$this->form_validation->set_rules('address', 'Address', 'required');

		if($this->form_validation->run()==false){
			$response = [
				'status'=>'error',
				'error'=>$this->form_validation->error_array(),
			];
		}else{

			$token = bin2hex(random_bytes(32));
			$data = array(
				'name'=>$this->security->xss_clean($this->input->post('username')),
				'mobile'=>$this->security->xss_clean($this->input->post('mobile')),
				'email'=>$this->security->xss_clean($this->input->post('email')),
				'password'=>password_hash($this->security->xss_clean($this->input->post('password')),PASSWORD_BCRYPT),
				'gender'=>$this->security->xss_clean($this->input->post('gender')),
				'address'=>$this->security->xss_clean($this->input->post('address')),
				'img'=>$this->image,
				'token'=> $token 
			);
			
			// print_r($data);die;
			$this->Chat_model->register($data);
			$response = [
				'status'=>'success',
				'message' => 'User register successfully',
				'token'=>$token
			];
		}
		echo json_encode($response);
		
	}

	public function img_error()
	{
		if(!empty($_FILES['img']['name'])){
			$config['upload_path']='./uploads/';
			$config['allowed_types']='jpg|png|jpeg';
			$config['max_size']=2048;
			$config['file_name']=time().'_'.$_FILES['img']['name'];
			$this->load->library('upload',$config);
			if($this->upload->do_upload('img')){
				$upload_data = $this->upload->data();
				$this->image = "uploads/".$upload_data['file_name'];
				return true;
			}else{
				$this->form_validation->set_message('img_error',$this->upload->display_errors("",""));
				return false;
			}
		}else{
			$this->form_validation->set_message('img_error',"Please select image");
			return false;
		}
	}

	
	public function login()
	{
		/*
		email:sonam@gmail.com
		password:12345678
		*/
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[8]');
		if($this->form_validation->run()==false){
			$response = [
				'status'=>'error',
				'error'=>$this->form_validation->error_array(),
			];
		}else{
			$email = $this->security->xss_clean($this->input->post('email'));
			$password = $this->security->xss_clean($this->input->post('password'));
			$users = $this->Chat_model->login('email',$email,$password);
			// print_r($users);die;
			if(!empty($users)){
				$response = [
					'status'=>'success',
					'message' => 'User Login successfully',
					'data'=>$users,
					// 'token'=>$token
				];
			}else{
				$response = [
					'status'=>'unauthorized',
					'message' => 'Credential Not Matched.',
				];
			}
			
		}
		echo json_encode($response);
		
	}


public function dashboard(){

	/*user_id:9
limit:1
offset:2
short:id
order:desc
search:
*/

	$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';

// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}
	$user_id = $this->input->get('user_id');

	$limit = $this->input->get('limit')?? 10;
	$offset = $this->input->get('offset')?? 0;
	$search = $this->input->get('search');
	$short = $this->input->get('short')?? id;
	$order = $this->input->get('order')?? 'desc';
	$this->db->select('id,img,name')->from('chat_users');
if($search){
	$this->db->like('name',$search);
	$this->db->or_like('email',$search);
}
$this->db->order_by($short,$order);
$this->db->limit($limit,$offset);
	$data = $this->db->where('id!=',$user_id)->get()->result_array();

	if(!empty($data)){
		$response = [
			'status' => 'success',
			'data'=> $data,
		];
	}else{
		$response = [
			'status' => 'error',
			'message'=> 'Data not found',
		];
	}
	echo json_encode($response);
}





public function get_message(){

	/*
	short:id
	order:desc
	search:
	sender_id:9
	reciever_id:1
	*/

	$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';

// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}
	$sender_id = $this->input->get('sender_id');
	$reciever_id = $this->input->get('reciever_id');
	$search = $this->input->get('search');
	$short = $this->input->get('short')?? 'id';
	$order = $this->input->get('order')?? 'desc';
	$this->db->select('*')->from('messages');
if($search){
	$this->db->like('message',$search);
}
$this->db->order_by($short,$order);
	$data = $this->db->where("(`sender_id`= {$sender_id} AND `reciever_id`={$reciever_id}) OR (`sender_id`= {$reciever_id} AND `reciever_id`={$sender_id})")->get()->result_array();
	if(!empty($data)){
		$response = [
			'status' => 'success',
			'data'=> $data,
		];
	}else{
		$response = [
			'status' => 'error',
			'message'=> 'Data not found',
		];
	}
	echo json_encode($response);
}

public function send_message(){

	/*
	sender_id:9
	reciever_id:1
	message:Hello
	*/

	$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';
// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}

	$this->form_validation->set_rules('sender_id','Sender Id','required');
	$this->form_validation->set_rules('reciever_id','Reciever Id','required');
	$this->form_validation->set_rules('message','Message','required');
if($this->form_validation->run()==false){
	$response = [
		'status' => 'error',
		'error'=> $this->form_validation->error_array(),
	];
	echo json_encode($response);
	return;
}
	$sender_id = $this->input->post('sender_id');
	$reciever_id = $this->input->post('reciever_id');
	$message = $this->input->post('message');


	$data=array(
		'sender_id'=>$sender_id,
		'reciever_id'=>$reciever_id,
		'message'=> $message
	);
	$msg = $this->Chat_model->sendMessage($data);
	if(!empty($msg)){
		$response = [
			'status' => 'success',
			'message'=> 'Sent message',
		];
	}else{
		$response = [
			'status' => 'error',
			'message'=> 'Something went wrong',
		];
	}
	echo json_encode($response);
}

public function get_user(){

	/*
	user_id:1
*/

	$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';

// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}
	$user_id = $this->input->get('user_id');

	$data = $this->db->where('id',$user_id)->get('chat_users')->row_array();

	if(!empty($data)){
		$response = [
			'status' => 'success',
			'data'=> $data,
		];
	}else{
		$response = [
			'status' => 'error',
			'message'=> 'User not exist',
		];
	}
	echo json_encode($response);
}

public function update_profile()
{

	/*
	user_id:1
	username:hanni
	gender:male
	address:agar
	img:
*/

$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';
// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}


	$image= null;
	$this->form_validation->set_rules('username', 'Username', 'required');
	$this->form_validation->set_rules('gender', 'Gender', 'required');
	// $this->form_validation->set_rules('img', 'Profile pic', 'callback_img_error');
	$this->form_validation->set_rules('address', 'Address', 'required');

	$id = $this->security->xss_clean($this->input->post('user_id'));
	$user_img = $this->db->where('id',$id)->get('chat_users')->row_array();

	if(!empty($_FILES['img']['name'])){
		$this->form_validation->set_rules('img', 'Profile pic', 'callback_img_error');
	}else{
		$this->image = $user_img['img'];
	}

	if($this->form_validation->run()==false){
		$response = [
			'status'=>'error',
			'error'=>$this->form_validation->error_array(),
		];
	}else{

		$token = bin2hex(random_bytes(32));
		$data = array(
			'name'=>$this->security->xss_clean($this->input->post('username')),
			'gender'=>$this->security->xss_clean($this->input->post('gender')),
			'address'=>$this->security->xss_clean($this->input->post('address')),
			'img'=>$this->image,
		);
		
		// print_r($data);die;
		$this->Chat_model->update_profile($id,$data);
		$user = $this->db->where('id',$id)->get('chat_users')->row_array();
		$response = [
			'status'=>'success',
			'message' => 'Update Profile successfully',
			'data'=>$user
		];
	}
	echo json_encode($response);
	
}


public function change_password()
{

	/*
	user_id:1
	username:hanni
	gender:male
	address:agar
	img:
*/

$header = getallheaders();
	$token = isset($header['Authorization'])? trim(str_replace('Bearer','',$header['Authorization'])):'';
// print_r($token);die;
	$query = $this->db->get_where('chat_users',['token'=>$token]);

	$user = $query->row();
	if(!$user){
		$response = [
			'status' => 'error',
			'message'=> 'Unauthorized',
		];
		echo json_encode($response);
		return;
	}

	$this->form_validation->set_rules('old_password', 'Old Password', 'required|min_length[6]|max_length[8]');
	$this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]|max_length[8]');
	$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|min_length[6]|max_length[8]|matches[new_password]');

	if($this->form_validation->run()==false){
		$response = [
			'status'=>'error',
			'error'=>$this->form_validation->error_array(),
		];
	}else{

		$id = $this->security->xss_clean($this->input->post('user_id'));
		$user_pass = $this->db->where('id',$id)->get('chat_users')->row_array();

		$old_password = $this->security->xss_clean($this->input->post('old_password'));
		$new_password = $this->security->xss_clean($this->input->post('new_password'));
		
	if(password_verify($old_password,$user_pass['password'])){
	
		$data = array(
			'password'=>password_hash($new_password,PASSWORD_BCRYPT),
		);
		
		// print_r($data);die;
		$this->Chat_model->update_profile($id,$data);
		$response = [
			'status'=>'success',
			'message' => 'Password change successfully',
		];
	}else{
		$response = [
			'status'=>'error',
			'message' => 'Wrong old password',
		];
	}
}
	echo json_encode($response);
	
}
}