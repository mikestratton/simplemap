<?php
if(isset($_POST['geofield1'])) {
    $data = $_POST['geofield1'] . "\n";
    $ret = file_put_contents('maps/geo_form.json', $data);
    if($ret === false) {
        die('There was an error writing this file');
    }
    else {
        echo "$ret bytes written to file <br />";
        echo "View the data here: <a href=\"create_json_file.php\">Saved Map</a>";
    }
}
else {
   die('no post data to process');
}