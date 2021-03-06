<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller {
 
    protected $data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('Utility_model');

        if ($this->ion_auth->logged_in()) {
            $this->load->model('User_model');
            $this->user = $this->ion_auth->user()->row();
            // in case profile hasn't been created.
            // This is only needed to deal with already created users, so it shoukd be useless in a bit.
            $this->User_model->create_profile($this->user->id);
            $this->user->profile = $this->User_model->get_profile($this->user->id)->row();
            $this->user->profile->picture_url = $this->User_model->profile_picture_url($this->user->id);
            $this->user->full_name = trim($this->user->first_name." ".$this->user->last_name);
            $this->data['user'] = $this->user;
        }
        else {
            $user = FALSE;
        }

        
        $this->data['page_title'] = 'Cudi Trader';
        $this->data['page_description'] = 'Cudi Trader';
        $this->data['before_closing_head'] = '';
        $this->data['before_closing_body'] = '';

        $this->data['site_settings'] = $this->get_all_settings();
    }
 
    protected function render($the_view = NULL, $template = 'pages_template')
    {
        if($template == 'json' || $this->input->is_ajax_request())
        {
            header('Content-Type: application/json');
            echo json_encode($this->data);
        }
        elseif(is_null($template))
        {
            $this->load->view($the_view, $this->data);
        }
        else
        {
            $this->data['page_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
            $this->data['current_user'] = $this->ion_auth->user()->row();
            $this->load->view('templates/' . $template . '', $this->data);
        }
    }

    /* creates an array of all items in a table, indexed by a specified $key_field. For use in a form_dropdown()

    $table_name: the name of the table to select items from
    $value_fields: a string of fields to be selected from the table; each separated from the next by a comma
    $key_field: the field to use as the key. Used for the value attribute of the corresponding <item> in the eventual <select> element.

    */

    protected function compose_array($table_name, $value_fields=FALSE, $key_field='id') {
        if ($value_fields !== FALSE) {
            $this->db->select($key_field.",".$value_fields);
        }
        else {
            $this->db->select($value_fields);
        }
        $query = $this->db->get($table_name);
        
        $result_array = $query->result_array();

        foreach ($result_array as $item) {
            $result[$item[$key_field]] = $item;
        }

        return $result;
    }

    /*
    Creates items for use in a dropdown (to be used in form_dropdown())

    $table_name: the name of the table
    $key_field: the key
    $value_field: user friendly display data
    $blank_first: make the first item in the dropdown blank
    $order_by: order by
    $direction: direction for order by

    MODIFY this: no accessing database layer directly, _nauw_.
    */
    protected function prep_select($table_name, $key_field, $value_field, $blank_first=FALSE, $order_by="", $direction="ASC") {
        // prepopulate select
        if ($blank_first) {
            $result = array(""=>"");
        }
        else {
            $result = array();
        }
        
        $result_array = $this->Utility_model->prep_select_data($table_name, $key_field, $value_field, $order_by, $direction);

        foreach ($result_array as $item) {
            $result[$item[$key_field]] = $item[$value_field];
        }


        return $result;
    }

    protected function get_setting($setting=FALSE) {
        if (!$setting) {
            return "";
        }
        return $this->Utility_model->get_setting($setting);
    }
    
    protected function get_all_settings() {
        // return $this->Utility_model->get_all_settings();
        return $this->prep_select("settings", "setting", "value");
    }
}

class Auth_Controller extends MY_Controller {
    // var $user;
    function __construct() {
        parent::__construct();
        if ($this->ion_auth->logged_in() === FALSE) {
            redirect('user/login');
        }
        // else {
        //     // $this->load->model('User_model');
        //     // $this->user = $this->ion_auth->user()->row();
        //     // $this->user->profile = $this->User_model->get_profile($this->user->id)->row();
        //     // $this->user->profile->picture_url = $this->User_model->profile_picture_url($this->user->id);
        //     // $this->user->full_name = $this->user->first_name." ".$this->user->last_name;
        //     // $this->data['user'] = $this->user;
        //     if (!$this->user->active && $this->uri->uri_string()!='user/profile') {
        //         // $this->ion_auth->logout();
        //         redirect('user/profile');
        //     }
        // }
    }

    protected function render($the_view = NULL, $template = 'pages_template') {
        parent::render($the_view, $template);
    }

    // protected function prep_select($table_name, $key_field, $value_field) {
    //     parent::prep_selects($table_name, $key_field, $value_field);
    // }
}

class User_Controller extends Auth_Controller {
    // var $user;
    function __construct() {
        parent::__construct();

        if (!$this->user->active 
            && $this->uri->uri_string()!='user/profile' 
            && $this->uri->uri_string()!='user/logout') {
                // $this->ion_auth->logout();
            $_SESSION['message'] = "Please, verify your email to proceed";
            $this->session->mark_as_flash('message');
            redirect('user/profile');
        }
    }

    protected function render($the_view = NULL, $template = 'user_template') {
        parent::render($the_view, $template);
    }

    // protected function prep_select($table_name, $key_field, $value_field) {
    //     parent::prep_selects($table_name, $key_field, $value_field);
    // }
}
