<?php
defined('_JEXEC') or die('Restricted Access');

$weekStart = date_create()->modify('Monday this week')->format('Y-m-d');
$weekEnd = date_create()->modify('Sunday this week')->format('Y-m-d');
?>

<style>
    label {
        margin-top: 10px;
    }

    .d1 {
        text-align: center;
        vertical-align: middle;
        margin: auto;
        width: 70%;
        max-width: 825px;
    }

    textarea {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    width: 100%;
    }
</style>

<script>
    function setFields() {
        let rowCnt = document.getElementById("setNumber").value
        var tbl = document.getElementById("tbl");

        for (var i = tbl.rows.length + 1; i <= rowCnt; i++) {
            row = tbl.insertRow(-1)

            row.insertCell(-1).outerHTML = `<th scope="row">${i}</th>`
            row.insertCell(-1).outerHTML = `<td style="max-width: 300px;text-align: center"><input style="text-align: center" type="datetime-local" min="<?php echo $weekStart ?>T00:00" max="<?php echo $weekEnd ?>T23:59" class="form-control" name="${"dateTime-" + i}" required/></td >`
            row.insertCell(-1).outerHTML = `<td style="width: 15px;"><input type="number" min="1" max="1000" value="1" class="form-control" name="${"minutesSpent-" + i}" /></td>`
            row.insertCell(-1).outerHTML = `<td style="width: 20px;"><input type="text" maxlength="21" class="form-control" required placeholder="Enter Type Here" name="${"activityType-" + i}" /></td>`
            row.insertCell(-1).outerHTML = `<td> <textarea rows="1" cols="83" placeholder="Enter Description Here" class="form-control" name="${"description-" + i}"></textarea></td>`
        }
    }

    function disableSet() {
        document.getElementById('setButton').disabled = true
        document.getElementById('setNumber').disabled = true
    }
</script>

<body onload="setFields()"></body>

<div style="text-align: center;">
    <h3>Add Activities to the Week of <?php echo date_create()->modify('Monday this week')->format('m/d/Y') ?></h3>
</div>
<div style="margin-bottom:10px;display:flex; flex-direction: row; justify-content: center; align-items: center">
    <input type='number' min='1' max='20' value='1' class="form-control" style="margin-right:10px;max-width: fit-content;float: left" id="setNumber">
    <button type="button" class="btn btn-success" onclick="setFields(); disableSet()" id="setButton">Set</button>
    <button type="button" class="btn btn-danger" onclick="window.location.reload()">Reset</button>
</div>

<form method="post" action='index.php?option=com_reports&view=home'>
    <table class="table table-striped" onload="setFields()">
        <thead>
            <tr>
                <th style="width: 50px;" scope="col">#</th>
                <th scope="col">Date/Time</th>
                <th scope="col">Minutes Spent</th>
                <th scope="col">Type</th>
                <th scope="col">Description</th>
            </tr>
        </thead>
        <tbody id="tbl">
        </tbody>
    </table>

    <div class="d1">
        <button type="submit" class="btn btn-primary btn-lg" style="width: 50%;margin: auto;">Submit</button>
    </div>
</form>
