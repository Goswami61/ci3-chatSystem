<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

	public function register($data)
	{
		return $this->db->insert('chat_users',$data);
	}

    public function login($field,$value,$password)
	{
		$query = $this->db->where($field,$value)->get('chat_users');

        if($query->num_rows()==1){
            $users =  $query->row();
           
            // print_r(password_verify($password,$users->password));die;
            if(password_verify($password,$users->password)){
                return $users;
             }

        }
	}

    public function list()
	{	
	

		// $this->db->select('DISTINCT(chat_users.id), chat_users.*, request.status as req_status,request.sender_id,request.reciever_id');
		// $this->db->from('chat_users');
		// $this->db->where('chat_users.id!=',$_SESSION['users']->id);
		// $this->db->join('request','request.sender_id = chat_users.id OR request.reciever_id = chat_users.id','left');
		// $this->db->group_by('chat_users.id');
		// return $this->db->get()->result_array();

		return $this->db->where('id!=',$_SESSION['users']->id)->get('chat_users')->result_array();
	}

	public function active_user()
	{	
		return $this->db->where('id',$_SESSION['users']->id)->get('chat_users')->row_array();
	}
    public function get_user($user_id)
	{
		return $this->db->where('id',$user_id)->get('chat_users')->row_array();
	}

    public function sendMessage($data)
	{
		return $this->db->insert('messages',$data);
	}

	public function get_msg($sender_id, $user_id)
	{
		// return $this->db->where("('sender_id'= {$sender_id} AND 'reciever_id'={$user_id}) OR ('sender_id'= {$user_id} AND 'reciever_id'={$sender_id})")->get('messages')->result_array();

		return $this->db
        ->where("(sender_id = {$sender_id} AND reciever_id = {$user_id}) OR (sender_id = {$user_id} AND reciever_id = {$sender_id})")
        ->order_by('created_at', 'ASC')
        ->get('messages')
        ->result_array();
	}

	public function status_update($user_id, $status)
	{
		return $this->db->where('id',$user_id)->update('chat_users',['status'=>$status]);
	}

	public function req_status($data)
	{
		return $this->db->insert('request',$data);
	}

	public function update_req_status($sender_id, $user_id, $status)
	{
		return $this->db
        ->where("(sender_id = {$sender_id} AND reciever_id = {$user_id}) OR (sender_id = {$user_id} AND reciever_id = {$sender_id})")
        ->update('request',['status'=>$status]);
	}
}
