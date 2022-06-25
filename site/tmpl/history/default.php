<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$uri = Uri::getInstance();
$date_param = date_create($uri->getVar('date')) ?? date_create();
$date_param->modify('Last Sunday');

$weekStart = $date_param->format('Y-m-d');
$displayWeek = $date_param->format('n/j/Y');
$db = JFactory::getDbo();

$query = $db->getQuery(true);
$query->select(array('data', 'id'))->from('reports')->where("weekStart = '$weekStart'");
$db->setQuery($query);
$pastSubs = $db->loadRowList();

if(array_key_exists('search', $_POST)) {
  $app = Factory::getApplication();
  $input = $app->input;
  $app->redirect(JRoute::_(JURI::current() . "?view=history&date=" . $input->get('date_param')));
}

?>
<h1>Reports of the Week - <?= $displayWeek;?></h1>

<style>
    .filters {
        margin-top: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-bar {
        width: 310px;
    }
</style>

<form method="post" class="filters"> 
    <div class="search-bar input-group"> 
        <input name="date_param" class="form-control" value="<?php echo $displayWeek ?>" type="date"/>
        <input type="submit" name="search" value="Search" class="btn btn-secondary" />
        <a class="btn btn-danger" href="<?php echo JURI::current(); ?>?view=history">Clear</a>
    </div>
</form>

<h3><?php if(count($pastSubs) == 0) echo "No reports found"?></h3>

<div class="accordion accordion-flush" id="accordionFlushExample">
  <?php
  $accordionNum = 1;
  foreach ($pastSubs as $prevReport) {
    $data = json_decode($prevReport[0], true);
    $name = Factory::getUser($prevReport[1])->name;
    $numReports = count($data) / 4;
    $reportMessage = $numReports == 1 ? "$numReports Report" : "$numReports Reports";

    echo  '<div class="accordion-item">';

    echo "<h2 class='accordion-header' id='flush-heading$accordionNum'> <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-collapse$accordionNum' aria-expanded='false' aria-controls='flush-collapse$accordionNum'> $name - $reportMessage</button> </h2>";
    echo "<div class='accordion-collapse collapse' id='flush-collapse$accordionNum' aria-labelledby='flush-heading$accordionNum' data-bs-parent='#accordionFlushExample'> <div class='accordion-body'>";

    echo "<table class='table table-striped'> <thead> <tr> <th style='width: 50px;' scope='col'>#</th> <th scope='col'>Date/Time</th> <th scope='col'>Minutes Spent</th> <th scope='col'>Type</th> <th scope='col'>Description</th> </tr> </thead> <tbody>";

    $i = 0;
    while (array_key_exists("dateTime-" . ++$i, $data)) {
      $dateTime = $data["dateTime-$i"];
      $minutesSpent = $data["minutesSpent-$i"];
      $activityType =  $data["activityType-$i"];
      $description =  $data["description-$i"];

      echo '<tr>';

      echo "<th scope='row'>$i</th>";
      echo "<td style='max-width: 300px;text-align: center'><input style='text-align: center' type='datetime-local' value='$dateTime' disabled class='form-control'/></td>";
      echo "<td style='width: 15px;'><input type='number' min='1' max='1000' value='$minutesSpent' class='form-control' disabled /></td>";
      echo "<td style='width: 20px;'><input type='text' maxlength='21' class='form-control' value='$activityType' disabled/></td>";
      echo "<td><textarea rows='1' cols='72' class='form-control' disabled>$description</textarea></td>";

      echo '</tr>';
    }

    echo '</tbody> </table>';
    echo '</div> </div> </div>';

    $accordionNum++;
  }
  ?>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>