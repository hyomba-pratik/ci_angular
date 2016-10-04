<?php
/**
 * Created by IntelliJ IDEA.
 * User: tsanzol
 * Date: 9/29/16
 * Time: 8:34 PM
 */
$config = array(
    'validate_contact' => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required'
        ),
        array(
            'field' => 'contact',
            'label' => 'Contact',
            'rules' => 'required|is_natural|min_length[7]|max_length[10]'
        ),
        array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'required'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        )
    ),
);