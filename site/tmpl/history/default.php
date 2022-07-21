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

// If they search for a certain week, add the parameter and redirect
if(array_key_exists('search', $_POST)) {
  $app = Factory::getApplication();
  $input = $app->input;
  $app->redirect(JRoute::_(JURI::current() . "?view=history&date=" . $input->get('date_param')));
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
</style>

<h1>Reports of the Week</h1>
<h2><?php echo "$displayWeekStart - $displayWeekEnd";?></h2>

<form method="post" class="filters"> 
    <div class="search-bar input-group"> 
        <input name="date_param" class="form-control" value="<?php echo $displayWeekStart ?>" type="date"/>
        <input type="submit" name="search" value="Search" class="btn btn-secondary" />
        <a class="btn btn-danger" href="<?php echo JURI::current(); ?>?view=history">Clear</a>
    </div>
    <div>
        <a class="btn btn-success" href="<?php echo JURI::current(); ?>?view=week&date=<?= $search_date ?>">Simple View</a>
    </div>
</form>

<h3><?php if(count($weekReports) == 0) echo "No reports found"?></h3>

<table class="table table-striped" id="table">
    <thread class="thread-dark">
        <tr>
            <th>Student</th>
            <th>Date/Time</th>
            <th>Minutes Spent</th>
            <th>Type</th>
            <th>Description</th>
        </tr>
    </thread>
    <?php foreach($weekReports as $studentReports): ?>
        <?php
            $data = json_decode($studentReports[0], true);
            $studentName = Factory::getUser($studentReports[1])->name;
            $reportIndex = 0;
        ?>
        <?php while(array_key_exists("dateTime-" . ++$reportIndex, $data)): ?>
            <?php
                $dateTime = date_create($data["dateTime-$reportIndex"])->format('n/j/Y');
                $minutesSpent = $data["minutesSpent-$reportIndex"];
                $activityType =  $data["activityType-$reportIndex"];
                $description =  $data["description-$reportIndex"];
            ?>
            <tr>
                <td><?= $studentName; ?></td>
                <td><?= $dateTime; ?></td>
                <td><?= $minutesSpent; ?></td>
                <td><?= $activityType; ?></td>
                <td><?= $description; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php endforeach; ?>
</table>