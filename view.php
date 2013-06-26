<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of asalink
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage asalink
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace asalink with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir .'/asaService/asaFactory.php');
require_once(dirname(__FILE__).'/submit_form.php');
require_once(dirname(__FILE__).'/test_form.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // asalink instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('asalink', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $asalink  = $DB->get_record('asalink', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $asalink  = $DB->get_record('asalink', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $asalink->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('asalink', $asalink->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'asalink', 'view', "view.php?id={$cm->id}", $asalink->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/asalink/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($asalink->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('asalink-'.$somevar);

// Output starts here
echo $OUTPUT->header();

if ($asalink->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('asalink', $asalink, $cm->id), 'generalbox mod_introbox', 'asalinkintro');
}

// Replace the following lines with you own code
//echo $OUTPUT->heading('Привет '.$USER->username);


$att = asaFactory::get_attestation_info($USER->username, $asalink->testname);

if ($att->in_error())
{
    echo $OUTPUT->heading('Ошибка! '.$att->get_error_message());
}
else
{
    echo $OUTPUT->heading($att->get_subject_name().', '.
            $att->get_type_testing().', '.$att->get_semester().'-й семестр');
    if ($att->get_serial_number()>0)
    {
        echo $OUTPUT->heading("Контрольная точка №".$att->get_serial_number());
    }
    if ($att->get_id()==empty_guid)
    {
        echo $OUTPUT->heading("Промежуточная аттестация");
    }
    
    if ($att->get_form_attestation() == form_attestation_test)
    {
        echo $OUTPUT->heading("Аттестация проводится в форме тестирования. Количество тестовых заданий - ".$att->get_item_count().'.');
    }
    if ($att->get_form_attestation() == form_attestation_writen_work)
    {
        echo $OUTPUT->heading('Дисциплина сдаётся в форме письменной аттестационной работы.');
    }

    if (($att->get_id() != empty_guid) && ($att->get_can_testing())){
        //Тестирование началось. Рендер задания
        
        $QuestNo  = optional_param('qn', 0, PARAM_INT);
        
        
        
        $curent_quest = optional_param('q', 0, PARAM_INT);
        echo $OUTPUT->heading('Оставшееся время:'.$att->get_time_to_test());
        $test_item = asaFactory::get_attestation_item($USER->username, 
                $att->get_id(), $QuestNo);
        
        
        echo $QuestNo.', '.$att->get_id().', ';
        
        $mform = new mod_asalink_test_mod_form($test_item, $PAGE->url);
        $mform->display();
        
        RenderSelectQuestionGreed($att, $PAGE->url);
        
        $response_set = asaFactory::get_all_responses($USER->username, 
                $att->get_id());
        echo $response_set->get_count();
        //$p = asaFactory::create_response_as_param($response_set->get_response(1));
        
        //$response = $test_item->create_editable_response();
        //$response->get_response_item(1)->set_value("1");
        //asaFactory::set_responses($USER->username, 
        //        $att->get_id(), $response);
        //var_dump($response);
    }
    else
    {
        if ($att->get_id() != empty_guid)
        {
            echo html_writer::start_tag("p");
            echo "Отправлен запрос на аттестацию.";
            echo html_writer::end_tag("p");
        }
        
        $mform = new mod_asalink_mod_form($PAGE->url->out());
        if ($mform->is_user_press_begin_attestation())
        {
            $att = asaFactory::begin_attestation($USER->username, $asalink->testname);
        }
    
        if ($mform->is_user_press_abort_attestation())
        {
            echo "<BR/>Abort att ";
        }
        $mform->display();
    }
}
echo $OUTPUT->footer();
