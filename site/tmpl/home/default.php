<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;

$weekStart = date_create()->modify('Monday this week')->format('Y-m-d');
$id = Factory::getUser()->id;
$db = JFactory::getDbo();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $query = $db->getQuery(true);
  $query->select('data')->from('reports')->where("id = '$id'")->where("weekStart = '$weekStart'");
  $db->setQuery($query);
  $existingData = $db->loadRow();

  if (empty($existingData)) {
    $data = $_POST;
  } else {
    $query = $db->getQuery(true);
    $query->delete('reports')->where("id = '$id'")->where("weekStart = '$weekStart'");
    $db->setQuery($query)->execute();

    $data = json_decode($existingData[0], true);
    $nextInd = count($data) / 4;
    $postLastInd = count($_POST) / 4;
    for ($i = 1; $i <= $postLastInd; $i++) {
      $nextInd++;
      $data["dateTime-$nextInd"] = $_POST["dateTime-$i"];
      $data["minutesSpent-$nextInd"] = $_POST["minutesSpent-$i"];
      $data["activityType-$nextInd"] = $_POST["activityType-$i"];
      $data["description-$nextInd"] = $_POST["description-$i"];
    }
  }

  $newReport = new stdClass();
  $newReport->id = $id;
  $newReport->weekStart = $weekStart;
  $newReport->data = json_encode($data);
  $db->insertObject('reports', $newReport);
}


$query = $db->getQuery(true);
$query->select(array('weekStart', 'data'))->from('reports')->where("id = '$id'");
$db->setQuery($query);
$pastSubs = $db->loadRowList();

?>
<style>
    textarea.form-control {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    width: 100%;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<div class="mx-auto" style="width: 250px;margin-bottom: 20px">
  <button type="button" class="btn btn-primary btn-lg" onclick="window.location.href='index.php?option=com_reports&view=create'">New Weekly Report</button>
</div>

<div class="accordion accordion-flush" id="accordionFlushExample">
  <?php
  $reportNum = 1;
  foreach ($pastSubs as $prevReport) {
    $date = DateTime::createFromFormat('Y-m-d', $prevReport[0]);
    $date = $date->format('n/j/Y');
    $data = json_decode($prevReport[1], true);

    echo  '<div class="accordion-item">';

    echo "<h2 class='accordion-header' id='flush-heading$reportNum'> <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-collapse$reportNum' aria-expanded='false' aria-controls='flush-collapse$reportNum'> Week of $date Report</button> </h2>";
    echo "<div class='accordion-collapse collapse' id='flush-collapse$reportNum' aria-labelledby='flush-heading$reportNum' data-bs-parent='#accordionFlushExample'> <div class='accordion-body'>";

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
      echo "<td><textarea rows='1' cols='65' class='form-control' disabled>$description</textarea></td>";

      echo '</tr>';
    }

    echo '</tbody> </table>';
    echo '</div> </div> </div>';

    $reportNum++;
  }
  ?>

</div>