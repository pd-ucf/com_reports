<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

// See if the user searched for a week, otherwise search the current week
$uri = Uri::getInstance();
$date_param = date_create($uri->getVar('date')) ?? date_create();
$search_date = $date_param->format('Y-m-d');
$date_param->modify('Monday this week');
$weekStart = $date_param->format('Y-m-d');
$displayWeekStart = $date_param->format('n/j/Y');
$displayWeekEnd = $date_param->modify('Sunday this week')->format('n/j/Y');

// Search the database for all reports of a certain week
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select(array('data', 'id'))->from('reports')->where("weekStart = '$weekStart'");
$db->setQuery($query);
$weekReports = $db->loadRowList();

// Get all student ids (Group 11)
$group_id = 11;
$access = new JAccess();
$allStudentsIDs = $access->getUsersByGroup($group_id);

// Get the student ids of all students who sent reports this week
// Also get the number of reports they sent that week
$reportStudentIDs = [];
$numStudentReports = [];
foreach ($weekReports as $report) {
  array_push($reportStudentIDs, $report[1]);

  $data = json_decode($report[0], true);
  $numReports = count($data) / 4;
  array_push($numStudentReports, $numReports);
}

// Deduce the students who did not send reports this week
$noReportStudentIDs = array_diff($allStudentsIDs, $reportStudentIDs);

// If they search for a certain week, add the parameter and redirect
if(array_key_exists('search', $_POST)) {
  $app = Factory::getApplication();
  $input = $app->input;
  $app->redirect(JRoute::_(JURI::current() . "?view=week&date=" . $input->get('date_param')));
}

?>
<style>
    .filters {
        margin-top: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .details {
        padding: 5px;
        display:inline-block
    }

    .search-bar {
        width: 310px;
    }

    table {
        border-radius: 5px;
    }
</style>

<h1>Reports of the Week</h1>
<h2><?php echo "$displayWeekStart - $displayWeekEnd";?></h2>

<form method="post" class="filters"> 
    <div class="search-bar input-group"> 
        <input name="date_param" class="form-control" value="<?php echo $displayWeekStart ?>" type="date"/>
        <input type="submit" name="search" value="Search" class="btn btn-secondary" />
        <a class="btn btn-danger" href="<?php echo JURI::current(); ?>?view=week">Clear</a>
    </div>
    <div>
        <a class="btn btn-warning" href="<?php echo JURI::current(); ?>?view=history&date=<?= $search_date ?>">Normal View</a>
    </div>
</form>

<table class="table table-striped" id="table">
    <thread class="thread-dark">
        <tr>
            <th>Student</th>
            <th>Reports Sent</th>
        </tr>
    <thread>
    <?php for($index = 0; $index < count($reportStudentIDs); $index++): ?>
        <tr class="table-success">
            <td><?= Factory::getUser($reportStudentIDs[$index])->name ?></td>
            <td><?= $numStudentReports[$index] ?></td>
        </tr>
    <?php endfor; ?>
    <?php foreach($noReportStudentIDs as $noReportStudentID): ?>
        <tr class="table-danger">
            <td><?= Factory::getUser($noReportStudentID)->name ?></td>
            <td>0</td>
        </tr>
    <?php endforeach; ?>

</table>