<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/REST_Controller.php');

class Contact extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('contact_model');
    }

    function all_get()
    {
        $contacts = $this->contact_model->get_all();
        $this->response(array('success' => true, 'data' => $contacts));
    }

    function index_get()
    {
        $contact = $this->contact_model->get($this->get('id'));
        $this->response(array('success' => true, 'data' => $contact));
    }

    function index_put()
    {
        $form_data = $this->put();
        $this->form_validation->set_data($form_data);

        if (isset($form_data['picture']) && $form_data['picture'] != "") {
            $form_data['picture'] = $this->generate_save_image($form_data['picture']);
        }

        if ($this->form_validation->run('validate_contact') === FALSE) {
            $this->response(array('success' => false, 'msg' => validation_errors()));
        } else {
            $result = $this->contact_model->add($form_data);
            if ($result === FALSE) {
                $this->response(array('success' => false, 'msg' => $this->config->item('CF-')));
            } else {
                $this->response(array('success' => true, 'msg' => $this->config->item('CF+')));
            }
        }
    }

    function index_post()
    {
        $form_data = $this->post();
        $this->form_validation->set_data($form_data);

        if (isset($form_data['picture']) && $form_data['picture'] != "") {
            $form_data['picture'] = $this->generate_save_image($form_data['picture']);
        }

        if ($this->form_validation->run('validate_contact') === FALSE) {
            $this->response(array('success' => false, 'msg' => validation_errors()));
        } else {
            $result = $this->contact_model->update($this->get('id'), $form_data);
            if ($result === FALSE) {
                $this->response(array('success' => false, 'msg' => $this->config->item('CFE-')));
            } else {
                $this->response(array('success' => true, 'msg' => $this->config->item('CFE+')));
            }
        }
    }

    function delete_post()
    {
        $result = $this->contact_model->delete($this->post('id'));
        if ($result === FALSE) {
            $this->response(array('success' => false, 'msg' => $this->config->item('CFD-')));
        } else {
            $this->response(array('success' => true, 'msg' => $this->config->item('CFD+')));
        }
    }

    function generate_save_image($base_string)
    {
        if ($base_string == "")
            return $base_string;

        $pos = strpos($base_string, ';');
        $type = explode('/', explode(':', substr($base_string, 0, $pos))[1])[1];
        $filepath = 'uploads/' . time() . '.' . $type;
        file_put_contents($filepath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base_string)));
        return base_url($filepath);
    }
}
