<?php

class SessionHook 
{
    public function update_status_on_expiry(){

        $CI = &get_instance();

        $user_id = $CI->session->userdata('users')->id;
        log_message('debug','session hooks exicuted . User id: '. $user_id);
        if(!$CI->session->userdata('users')){
            log_message('debug','session expiry . User stats will be updated');
            $CI->Chat_model->status_update($user_id,0);
        }
    }
}

?>