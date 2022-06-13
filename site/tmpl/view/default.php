<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;

$username = Factory::getUser()->username;
$db = JFactory::getDbo();

$query = $db->getQuery(true);
$query->select(array('weekStart', 'data'))->from('reports')->where("username = '" . $username . "'");
$db->setQuery($query);
$results = $db->loadRowList();
?>

<pre>
    <?php
    print_r($results);
    ?>
</pre>


<div class="accordion accordion-flush" id="accordionFlushExample">
    <?php
    $reportNum = 1;
    foreach ($results as $prevReport) {
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
            $description =  $data["activityType-$i"];

            echo '<tr>';

            echo "<th scope='row'>$i</th>";
            echo "<td style='max-width: 300px;text-align: center'><input style='text-align: center' type='datetime-local' value='$dateTime' disabled class='form-control'/></td>";
            echo "<td style='width: 15px;'><input type='number' min='1' max='1000' value='$minutesSpent' class='form-control' disabled /></td>";
            echo "<td style='width: 20px;'><input type='text' maxlength='21' class='form-control' value='$activityType' disabled/></td>";
            echo "<td><textarea rows='1' cols='70' class='form-control' disabled>$description</textarea></td>";

            echo '</tr>';
        }

        echo '</tbody> </table>';
        echo '</div> </div> </div>';

        $reportNum++;
    }
    ?>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>