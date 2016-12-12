<?php
if(isset($_POST['geofield1'])) {
    $data = $_POST['geofield1'] . "\n";
    $ret = file_put_contents('maps/geo_form.json', $data);
    if($ret === false) {
        die('There was an error writing this file');
    }
    else {
        echo "$ret bytes written to file <br />";
	$timestamp = date("YmdHis");
	echo "View the data here: <a href=\"v0.13.php?".$timestamp."\">geo_form.json</a>";
    }
}
else {
   die('no post data to process');
}
