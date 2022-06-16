<?php
defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;

$weekStart = date_create()->modify('Last Sunday')->format('Y-m-d');
$username = Factory::getUser()->username;
$db = JFactory::getDbo();


$query = $db->getQuery(true);
$query->select('data')->from('reports')->where("username = '$username'")->where("weekStart = '$weekStart'");
$db->setQuery($query);
$existingData = $db->loadRow();

if (empty($existingData)) {
    $data = json_encode($_POST);
} else {
    $data = json_decode($existingData[0], true);
    $nextInd = substr(array_key_last($data), -1);
    $postLastInd = substr(array_key_last($_POST), -1);
    for ($i = 1; $i <= $postLastInd; $i++) {
        $nextInd++;
        $data["dateTime-$nextInd"] = $_POST["dateTime-$i"];
        $data["minutesSpent-$nextInd"] = $_POST["minutesSpent-$i"];
        $data["activityType-$nextInd"] = $_POST["activityType-$i"];
        $data["description-$nextInd"] = $_POST["description-$i"];
    }
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





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>