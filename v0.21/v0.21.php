<?php
$target_dir = "uploads/";
$target_file = ($_FILES["fileToUpload"]["name"]) ? $target_dir . basename($_FILES["fileToUpload"]["name"]) : null;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
       // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats when image is provided
if ($target_file != null){
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    //echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
       // echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<?php $timestamp = date("YmdHis");?>
<html>
  <head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js"></script>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css"/>
	<style type="text/css">.gm-style .gm-style-mtc label,.gm-style .gm-style-mtc div{font-weight:400}</style>
    <style type="text/css">.gm-style-pbc{transition:opacity ease-in-out;background-color:black;text-align:center}.gm-style-pbt{font-size:22px;color:white;font-family:Roboto,Arial,sans-serif;position:relative;margin:0;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}</style>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <style type="text/css">.gm-style .gm-style-cc span,.gm-style .gm-style-cc a,.gm-style .gm-style-mtc div{font-size:10px}</style>
    <style type="text/css">@media print {  .gm-style .gmnoprint, .gmnoprint {    display:none  }}@media screen {  .gm-style .gmnoscreen, .gmnoscreen {    display:none  }}</style>
    <style type="text/css">.gm-style{font-family:Roboto,Arial,sans-serif;font-size:11px;font-weight:400;text-decoration:none}.gm-style img{max-width:none}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
	<style>
		html, body { height: 100%; margin: 0; padding: 0; }
		#map { height: 100%; }
		body { margin: 10px 32px;}
		label { float:left; text-align: center; }
		#edit { width:auto; min-height:400px; }
		#opacity { width: 200px; margin:14px; }
		#rotate {  width: 200px; margin:14px; }
		#height {  width: 200px; margin:14px; }
		#width {  width: 200px; margin:14px; }
		#controls { background:skyblue; float:left; margin:25px 30px; float:left; padding:10px; }
		#output { width:auto; float:left; padding:10px;  }
		.slabel {font-size: 12px; font-weight:500; color:white; }
		.slide-bg { background:blue; color:navy; text-align:center; }
	</style>
    <title>Simple GeoJSON Editor</title>
    <style type="text/css">
	  #delete-button {
		margin-top: 5px;
		position: fixed;
        z-index: 5000;
        left: 55%;
      }
	  #input-form {
        position: fixed;
        background: white;
        z-index: 5000;
        top: 0;
        right: 0;
      }
      html,
      body {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
      }
      body {
      font-family: "Arial", "Helvetica", sans-serif;
      color: #222;
      font-size: 13px;
      -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
      overflow-y: hidden;
      }
      /* Map styles. */
      #map-container {
      width: 74.9%;
      height: 100%;
      margin-left: auto;
      margin-right: auto;
      position: relative;
      float: right;
      z-index: 0;
      }
      #map-holder {
      height: 100%;
      width: 100%;
      }
      #drop-container {
      display: none;
      height: 100%;
      width: 100%;
      position: absolute;
      z-index: 1;
      top: 0px;
      left: 0px;
      /* padding: 20px;*/
      background-color: rgba(100, 100, 100, 0.5);
      }
      #drop-container.visible {
      display: block;
      }
      /* Panel styles. */
      #panel {
      float: left;
      width: 25%;
	  min-width: 250px;
 
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
      outline: 1px solid rgba(0, 0, 0, 0.2);
      z-index: 100;
      }
      #panel-content {
      padding: 8.5px;
      }
      #geojson-controls {
      padding-bottom: 8px;
      }
      #geojson-input {
      width: 100%;
      -webkit-box-sizing: border-box;
      /* <=iOS4, <= Android  2.3 */
      -moz-box-sizing: border-box;
      /* FF1+ */
      box-sizing: border-box;
      /* Chrome, IE8, Opera, Safari 5.1*/
      border: none;
      resize: none;
      background-color: #F1F1F1;
      }
      #panel-title {
      font-size: 20px;
      line-height: 24px;
      color: #DD4B39;
      }
      .subtitle {
      font-size: 16px;
      color: #222222;
      }
      hr {
      color: #ebebeb;
      }
      #geojson-input {
      background-color: #F1F1F1;
      }
      #geojson-input.invalid {
      background-color: #FAC6C0;
      }
    </style>
  </head>

  <body>
	
	<form class="input-form" id="input-form">
		<p><table border="0">
				<tr><td>Latitude:</td><td><input type="text" name="lat" id="lat-input" value="40.7116" /></td></tr>
				<tr><td>Longitude:</td><td><input type="text" name="lon" id="lon-input" value="-74.0132" /></td></tr>
				<tr><td>Zoom Level:</td><td><input type="text" name="zoom" id="zoom-input" value="18" /></td></tr>
				<tr><td>&nbsp;</td>
					<td style="text-align:right; padding:4px 12px">
					<input type="submit" value="Center Map" />
					</td>
				</tr>
			</table>
			
		</p>
		
		<hr>
		<p style="padding:0px 20px">
		<input type="checkbox" name="image" value="Image"> Image Overlay<br>
		<input type="checkbox" name="linestring" value="Linestring" checked="checked"> Property Boundary<br>
		<input type="checkbox" name="geojson" value="GeoJSON" checked="checked"> Drawing Layer<br>
		</p>
	
	</form>	
    <div id="panel">		
      <div id="panel-content">
		<div class="container">
			<div id="controls">
				
				<div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
				<div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
				<div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
				<div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div><br>
			<h2>Upload Image</h2>
				<form action="v0.21.php" method="post" enctype="multipart/form-data">
					Select image to upload:<br>
					<input type="file" name="fileToUpload" id="fileToUpload"><br>
					<input type="submit" value="Upload File" name="submit">
				</form><br>
				</div>
			<div id="output"></div>
		</div>
		 
        <div id="panel-title">Simple GeoJSON Editor</div>
        <div id="geojson-controls">
          <button onclick="document.getElementById('geojson-input').select();">Select All</button>
          <a id="download-link" href="data:;base64," download="geojson.json">
          <button>Download</button>
          </a>
          <form action="v0.21_geo_form.php" method="POST" id="geoform">
            <input type="submit" name="submit" value="Save GeoJSON">
          </form>
		 
        </div>
        <textarea name="geofield1" form="geoform" id="geojson-input" placeholder="Drag and drop GeoJSON onto the map or paste it here to begin editing." style="height: 611.5px;"></textarea>
      </div>
    </div>
    <div><button id="delete-button">Delete Selected Shape</button></div>
    <div id="map-container">
      <div id="map-holder" style="position: relative; overflow: hidden; background-color: rgb(229, 227, 223);"></div>
      <div id="drop-container" class=""></div>
    </div>

	<script type='text/javascript' src='//code.jquery.com/jquery-1.11.0.js'></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw&libraries=drawing"></script>
	<script src="v0.21_geoeditor.js?t=<?php echo $timestamp; ?>" type="text/javascript"
		src_image="<?php echo $ret = ($target_file ==null) ? "NOIMAGE" : $target_file; ?>"></script>

    <script type='text/javascript'>
		document.getElementById('input-form').addEventListener('submit', function (e) {
			e.preventDefault();
			lat = parseFloat(document.getElementById('lat-input').value);
			lon = parseFloat(document.getElementById('lon-input').value);
			zoom = parseFloat(document.getElementById('zoom-input').value);
			var point = new google.maps.LatLng(lat, lon);
			map.setCenter(point);
			map.setZoom(zoom);
			return true; // do not submit form
		}, true);

		jsonURL = 'maps/geo_form.json?t='+<?php echo $timestamp; ?>;
		$(window).load(function() {
			$.ajax({
				url : jsonURL,
				dataType: "text",
				success : function () {
					map.data.loadGeoJson(jsonURL);
				}
			});
		});
	</script>
 </body>
</html>
