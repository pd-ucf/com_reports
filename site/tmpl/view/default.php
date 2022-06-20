<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;

$weekStart = date_create()->modify('Last Sunday')->format('Y-m-d');
$id = Factory::getUser()->id;
$db = JFactory::getDbo();


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

    $newReport = new stdClass();
    $newReport->id = $id;
    $newReport->weekStart = $weekStart;
    $newReport->data = json_encode($data);
    $db->insertObject('reports', $newReport);
}

?>
<pre>
    <?php
    print_r($existingData);
    ?>
</pre>
<pre>
    <?php
    print_r($data);
    ?>
</pre>
<pre>
    <?php
    print_r($_POST);
    ?>
</pre>
<pre>
    <?php
    print_r($postLastInd);
    print_r($nextInd);
    ?>
</pre>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>