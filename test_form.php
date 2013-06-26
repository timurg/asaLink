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

class mod_asalink_test_mod_form extends moodleform {//moodleform_mod{//
    //put your code here
    protected $userselectBeginAttestation = false;
    protected $userselectAbortAttestation = false;
    
    protected $testItem = NULL;
    
    protected $url = NULL;
    
    public function is_user_press_begin_attestation(){
        return $this->userselectBeginAttestation;
    }
    
    public function is_user_press_abort_attestation(){
        return $this->userselectAbortAttestation;
    }
    
    public function __construct($test_item,
            $aurl, $customdata=null, $method='post', 
            $target='', $attributes=null, $editable=true) {
       $this->testItem = $test_item;
       $this->url = $aurl;
       parent::__construct($this->url->out(), $customdata, $method, $target, $attributes, 
               $editable);
       $mform =& $this->_form;
       
       
       
       $mform->disable_form_change_checker();
       //if ($data = $this->get_data())
       //{
       //}
    } 
    
    public function RenderCloseSingleTest($q)
    {
        $attributes = "";
        $radioarray=array();
        
        foreach ($q->get_variants() as $value) {
            $radioarray[] =& $this->_form->createElement('radio', 'radioar', 
                '', $value->get_item_text(), $value->get_order_num(), $attributes);
        }
        $this->_form->addGroup($radioarray, 'radioar', '', array(' '), false);
        foreach ($q->get_response() as $item)
        {
            if ($item->get_value() == "1")
            {
                $this->_form->setDefault('radioar', $item->get_answer_order());
            }
        }
    }
    
    public function RenderCloseMultiTest($q)
    {
        $attributes = "";
        $radioarray=array();
        
        foreach ($q->get_variants() as $value) {
            $this->_form->addElement('checkbox', 'ClosedMulti'.$value->get_order_num(), '', 
                    $value->get_item_text());
        }
    }
    
    public function RenderQuest($q)
    {
        $quest_type = $q->get_unit_type();
        $this->_form->addElement('html', $q->get_content());
        if ($quest_type == 'closed_single')
        {
            $this->RenderCloseSingleTest($q);
            //$this->RenderCloseMiltiTest($q);
        }
        else if ($quest_type = 'closed_multi')
        {
            $this->RenderCloseMiltiTest($q);
        }
    }
    
    
    
    function definition() {

        global $COURSE;
        $mform =& $this->_form;
        $this->RenderQuest($this->testItem);
    }
}

function RenderSelectQuestionGreed(asaAttestationInfo $info, $url)
{
               // output.Write("<hr/>");
    $ProtocolPresent = TRUE;
                if ($ProtocolPresent) {
                        $row_per_line = 20;
                        $rw = 100 / $row_per_line;
                        $QC = $info->get_item_count();
                        $row_count = $QC / $row_per_line;
                        $row_max_value = $row_per_line - 1;
                        if ($QC > $row_per_line) $row_count++;
                        $from = 0;
                        $to = 0;
                        
                        //str_nav = Page.GetPostBackClientHyperlink(this, "QuestNo=" + GetPredNum().ToString());
                        $bgcolor = "#C0C0C0";
                        echo html_writer::start_tag("p", array('align' => 'center'));
                        //output.WriteAttribute("align", "center");
                        //output.Write('>');
 
                        //echo html_writer::start_tag("a");
                        //output.WriteAttribute("href", str_nav);
                        //output.Write(">");
                        //output.Write("&#8592 Предыдущий вопрос");
                        //echo html_writer::end_tag("a");
                        //output.Write("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
 
                        //str_nav = Page.GetPostBackClientHyperlink(this, "QuestNo=" + GetNextNum().ToString());
                        //echo html_writer::start_tag("a");
                        //output.WriteAttribute("href", str_nav);
                        //output.Write(">");
                        //output.Write("Следующий вопрос &#8594");
                        //echo html_writer::end_tag("a");
 
                        //echo html_writer::end_tag("p");
 
                        //echo html_writer::start_tag("p");
                        //output.WriteAttribute("align", "center");
                        //output.Write('>');
 
                        echo html_writer::start_tag("table");
                        for ($i = 0; $i <= $row_count; $i++) {
 
                                echo html_writer::start_tag("tr", array('width' => $rw));
                                $from = $i * $row_per_line;
                                $to = $from + $row_max_value;
                                if ($to > $QC) $to = $QC - 1;
                                for ($n = $from; $n <= $to; $n++) {
                                        if ($n < $QC) {
                                                $staus = 1;
                                                //asaResponse res = GetResponse(n);
                                                //switch (res.Passed) {
                                                //        case 0:
                                                //        case 1:
                                                //                staus = 2;
                                                //                break;
                                                //}
                                                //if (RenderType == TestBoxRenderType.single) {
                                                //        staus = n == ToValue ? 3 : staus;
                                                //}
                                                //switch (staus) {
                                                //        case 0://не видел
                                                //                $bgcolor = "white";
                                                //                break;
                                                //        case 1: //видел но не ответил
                                                //                $bgcolor = "#E6E6E6";
                                                //                break;
                                                //        case 2://ответил
                                                //                $bgcolor = "#6699FF";
                                                //                break;
                                                 //       case 3://текущий
                                                 //               $bgcolor = "#FF9933";
                                                 //               break;
                                               // }
 
                                                echo html_writer::start_tag("td", array('bgcolor' => $bgcolor));
                                                $url->param("qn", $n);
                                                //echo html_writer::start_tag("a", array('Href' => $url->out()));
                                                echo '<a href="'.$url->out().'">';
                                                echo $n + 1;
                                                echo html_writer::end_tag("a");
                                                echo html_writer::end_tag("td");
                                        }
                                }
                                echo html_writer::end_tag("tr");
                        }
                        echo html_writer::end_tag("table");
 
                        echo html_writer::end_tag("p");
                }
        }

?>
