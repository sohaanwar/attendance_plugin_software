<?php
//defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');
require_once($CFG->dirroot.'/message/lib.php');


//@package   mod_attendance
 //* @copyright  2016 Dan Marsden http://danmarsden.com
 //* @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

class alert{




public $studentstoalert=array();


//@param stdClass $dbrecord Attandance instance data from {attendance} table


public function findstudents()
{   $records=array();
    global $DB;
    $sql="SELECT s.student,s.course,SUM(s.numberofabsences) AS absences FROM {number_of_student_absences} s GROUP BY s.student,s.course";
    $records=$DB->get_records_sql($sql);
     require_sesskey();
    $namefields = get_all_user_name_fields(true);
    foreach ($records as $r) {
    	if($r->absences>2)
    	{            $course=$DB->get_record('course', array('id'=>$r->course));
    		       
    		        $user=$DB->get_record('user', array('id'=>$r->student));
    		         $message="hello ".$user->firstname." ".$user->lastname." this is attendance warning for your attendance in ".$course->fullname."course  ,you were absent more than  2 times,so please try to attend next course sessions ,GOOD LUCK";
                    $this->studentstoalert[$r->student][0]= $user;
                    $this->studentstoalert[$r->student][1]=$message;
                   

    	}
    }
    
    
    return $this->sendalerts();

}

public function sendalerts()
{
	global $DB;
	global $USER;
	
	// $userfrom = $DB->get_record('user', array('id'=>2));
	foreach ( $this->studentstoalert as $user) {
                message_post_message($USER, $user[0],$user[1] ,FORMAT_HTML);
                	
}
}


}
?>