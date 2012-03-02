<?php

include('../loader.php');
global $mysqli;

$query = "select * from tags";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()){
    $entry['value'] = urlencode(str_replace("'","",$row['value']));
    //$entry['value'] = $row['value'];
    $val = addslashes($val);
    $entry['id'] = $row['id'];
    $res[] = $entry;

}



?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript">
    window.tags = JSON.parse('<?php echo json_encode($res); ?>');
    console.log(window.tags);
</script>

