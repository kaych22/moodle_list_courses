<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.3.5/css/autoFill.dataTables.min.css">
<style>
        /* add some css here */
</style>

<?php
require_once('config.php');
require_once('locallib.php');
require_once($CFG->dirroot . '/course/modlib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');
require_once($CFG->libdir . '/tablelib.php');
require_login(0, false);
$context = context_system::instance();
$url = '/local/class_teacher/list_courses.php';
$PAGE->set_url('/local/class_teacher/list_courses.php');
global $DB, $COURSE, $SESSION;
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Moodle List Courses');
$PAGE->set_heading('Moodle List Courses');
$PAGE->navbar->add('Moodle List Courses');
//$cid =90;
$libobj  = new local_class_teacher();
$libobj1  = new core_course_external();
echo $OUTPUT->header();
$courses = array_reverse($libobj->get_courses());
$reccount = count($courses);
if ($courses) {
        $strquestion   = 'Edit Course';
        $strquestion1   = 'Delete Course';
        $strquestion2   = 'Enrol Course';
        $table = new html_table();
        $table->head = array();
        // $table->head[] = '<INPUT type="checkbox" onchange="checkAll(this)" name="" />';
        $table->head[] = 'Select';
        $table->head[] = 'Course Name';
        $table->head[] = 'Created Date';
        $table->head[] = 'Actions';
        $table->tablealign = "center";
        $table->align = array('left', 'centre');
        $table->width = '100%';
        $table->id = "classname";
        $table->attributes['class'] = 'generaltable profilefield';
        // Array of arrays or html_table_row objects containing the data.
        $table->data = array();

        foreach ($courses as $m) {
                $c_name = $m->fullname;
                $courseid = $m->id;
                $c_name = html_writer::link(new moodle_url('/course/view.php?id=' . $courseid), $m->fullname, array('title' => 'course'));

                $created_date = date('F d, Y ', $m->timecreated);
                $modified_date = date('F d, Y ', $m->timemodified);
                $class_duration = $m->class_duration;
                $my_learning_hours = $m->my_learning_hours;

                // for editing course
                $edit_link = html_writer::link(new moodle_url('/local/class_teacher/class_activity.php?id=' . $courseid), html_writer::empty_tag('img', array('src' => $OUTPUT->image_url('i/edit'), 'alt' => $strquestion, 'class' => 'iconsmall')), array('title' => $strquestion));

                // for deleting course
                $delete_link = html_writer::link(new moodle_url('/course/delete.php?id=' . $courseid . '&fromcustom2=1'), html_writer::empty_tag('img', array('src' => $OUTPUT->image_url('i/trash'), 'alt' => $strquestion1, 'class' => 'fa fa-trash-o')), array('title' => $strquestion1));

                // for course enrolment
                $enrol_link = html_writer::link(new moodle_url('/local/class_teacher/moodle_list_students.php?id=' . $courseid), html_writer::empty_tag('img', array('src' => $OUTPUT->image_url('t/enrolusers'), 'alt' => $strquestion2, 'class' => 'fa fa-user-plus')), array('title' => $strquestion2));

                // call top most row names
                // $row[]='<a href="'.$CFG->wwwroot.'/local/class_teacher/view.php?courseid='.$courseid.'"></a>';
                // $table->data[] = array($c_name, $created_date, $edit_link . $delete_link . $enrol_link);

                //type="checkbox" onchange="checkAll(this)" name="chk[]"
                // $toggle = html_writer::checkbox('status', $m->id, false, '', array('id' => 'mark_course', 'class' => 'switch'));

                $checkbox = html_writer::checkbox('multiselect', $m->id, false, '', array('id' => 'select_course'));
                $table->data[] = array($checkbox, $c_name, $created_date, $edit_link . ' ' . $delete_link . ' ' . $enrol_link);
        }
        echo html_writer::start_tag('div', array('class' => 'report', 'id' => 'report1'));

        //echo $block_content = "<b><button class='blocklink'>Delete Selected Course(s)</button></b><br> ";

        echo $block_content = "<b><a class='blocklink1' href=$CFG->wwwroot/local/class_teacher/add_extra_activity.php>Create Course</a></b><br> ";

        echo html_writer::end_tag('div');
        echo html_writer::start_tag('div', array('class' => 'report', 'id' => 'report'));
        echo html_writer::table($table);
        echo html_writer::end_tag('div');
} else {
        echo html_writer::start_tag('div', array('class' => 'report', 'id' => 'report1', 'style' => 'float:right'));
        echo $block_content = "<b><a class='blocklink' href=$CFG->wwwroot/local/class_teacher/add_extra_activity.php>Create Course</a></b><br> ";
        echo html_writer::end_tag('div');
        echo html_writer::start_tag('div', array('class' => 'report', 'id' => 'report'));
        echo '<div class="error-message"> No Courses </div>';
        echo html_writer::end_tag('div');
}
if (!empty($table->data)) {
        echo html_writer::end_tag('div');
} else {
        echo '<h2>No Course Found<h2>';
}

echo '
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/autofill/2.3.5/js/dataTables.autoFill.min.js"></script>
        ';
echo $OUTPUT->footer();
?>
<script type="text/javascript">
        $(document).ready(function() {
                $("#classname").DataTable();
                $('#search-datatable').keyup(function() {
                        oTable.search($(this).val()).draw();
                });
        });
</script>
