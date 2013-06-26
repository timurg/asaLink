<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mod_asalink_mod_form
 *
 * @author Timur
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_asalink_mod_form extends moodleform {//moodleform_mod{//
    //put your code here
    protected $userselectBeginAttestation = false;
    protected $userselectAbortAttestation = false;
    
    public function is_user_press_begin_attestation(){
        return $this->userselectBeginAttestation;
    }
    
    public function is_user_press_abort_attestation(){
        return $this->userselectAbortAttestation;
    }
    
    public function __construct($action=null, $customdata=null, $method='post', 
            $target='', $attributes=null, $editable=true) {
       parent::__construct($action, $customdata, $method, $target, $attributes, 
               $editable);
       
       if ($data = $this->get_data())
       {
           $this->userselectBeginAttestation = isset($data->intro);
           $this->userselectAbortAttestation = isset($data->outro);
       }
    } 
    
    function definition() {

        global $COURSE;
        $mform =& $this->_form;

        //$mform->addElement('header', 'general', get_string('general', 'form'));
        
        //$mform->addElement('button', 'intro', "Начать аттестацию");
        $mform->addElement('submit', 'intro', 'Начать аттестацию');
       // $mform->addElement('submit', 'outro', 'Отменить аттестацию');

        //$mform->addElement('filepicker', 'packagefile', get_string('uploadpackage', 'scormcloud'), null, array('accepted_types' => '*.zip'));
        //$mform->addElement('static', 'uploadinfo', null, get_string('uploadinfo', 'scormcloud'));

        //$this->standard_coursemodule_elements();
        //$this->add_action_buttons();

    }
}

?>
