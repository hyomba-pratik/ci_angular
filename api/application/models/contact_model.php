<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class contact_model extends CI_Model
{
    public $table = "contacts";

    function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}