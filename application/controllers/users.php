<?php
class users extends CI_Controller{
    public function register()
    {
        $this->form_validation->set_rules('first_name','First Name','trim|required');
        $this->form_validation->set_rules('last_name','Last Name','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('username','Username','trim|required|max_length[16]|min_length[4]');
        $this->form_validation->set_rules('password','Password','trim|required|max_length[50]|min_length[4]');
        $this->form_validation->set_rules('password2','Confirm Password','trim|required|matches[password]');
        
        if($this->form_validation->run()==FALSE)
        {
            $data['main_content']='register';
            $this->load->view('layouts/main',$data);
        }
        else
        {
            if($this->User_model->register())
            {
              $this->session->set_flashdata('registered','You are registered and can login') ;
              redirect('home');            
            }
            else
            {
                redirect('users/register');
            }
        }
    }
    public function login()
    {
         $this->form_validation->set_rules('username','Username','trim|required|max_length[16]|min_length[4]');
        $this->form_validation->set_rules('password','Password','trim|required|max_length[50]|min_length[4]');
        
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));
        
        $user_id = $this->User_model->login($username,$password);
        
        if($user_id)
        {
            $data = array(
                'logged_in' => true ,
                'username' => $username ,
                'user_id' => $user_id
            );
            
            $this->session->set_userdata($data);
            $this->session->set_flashdata('pass_login','You are logged in');
            redirect('home');
        }
        else
        {
            $this->session->set_flashdata('fail_login','Sorry your login info is incorrect');
            redirect('home');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->sess_destroy();
        redirect('home');
    }
}