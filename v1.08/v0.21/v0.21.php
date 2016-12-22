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
	<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="stylesheet" href="styles/googlemaps.css" type="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
	<link rel="stylesheet" href="styles/style.css" type="text/css"/>
    <title>Simple GeoJSON Editor</title>
   
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
		<input type="checkbox" id="LAYER_LOAD_IMAGE" name="image" value="Image"> Image Overlay<br>
		<input type="checkbox" id="LAYER_LINESTRING" name="linestring" value="Linestring" checked="checked"> Property Boundary<br>
		<input type="checkbox" id="LAYER_DRAWN_JSON" name="geojson" value="GeoJSON" checked="checked"> Drawing Layer<br>
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

		jsonUrl_LineString = 'linestring/property01.json?t='+<?php echo $timestamp; ?>;
		jsonUrl_Drawn_Json = 'maps/geo_form.json?t='+<?php echo $timestamp; ?>;

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

		document.getElementById('LAYER_LOAD_IMAGE').addEventListener('click', function(e) {
			if(document.getElementById('LAYER_LOAD_IMAGE').checked){
				$.ajax({
					dataType: "text",
					success : function () {
						hidePicture(false);
					}
				});
			}else{
				$.ajax({
					dataType: "text",
					success : function () {
						map.data.forEach(function (feature) {
							map.data.remove(feature);
						});
						if(document.getElementById('LAYER_DRAWN_JSON').checked) map.data.loadGeoJson(jsonUrl_Drawn_Json);
						if(document.getElementById('LAYER_LINESTRING').checked) map.data.loadGeoJson(jsonUrl_LineString);
						hidePicture(true);
					}
				});
			}
		});

		document.getElementById('LAYER_LINESTRING').addEventListener('click', function(e) {
			if(document.getElementById('LAYER_LINESTRING').checked){
				$.ajax({
					dataType: "text",
					success : function () {
						map.data.loadGeoJson(jsonUrl_LineString);
					}
				});
			}else{
				$.ajax({
					dataType: "text",
					success : function () {
						map.data.forEach(function (feature) {
							map.data.remove(feature);
						});
						if(document.getElementById('LAYER_DRAWN_JSON').checked) map.data.loadGeoJson(jsonUrl_Drawn_Json);
						if(document.getElementById('LAYER_LINESTRING').checked) map.data.loadGeoJson(jsonUrl_LineString);
					}
				});
			}
		});

		document.getElementById('LAYER_DRAWN_JSON').addEventListener('click', function(e) {
			if(document.getElementById('LAYER_DRAWN_JSON').checked){
				$.ajax({
					dataType: "text",
					success : function () {
						map.data.loadGeoJson(jsonUrl_Drawn_Json);
					}
				});
			}else{
				$.ajax({
					dataType: "text",
					success : function () {
						map.data.forEach(function (feature) {
							map.data.remove(feature);
						});
						if(document.getElementById('LAYER_DRAWN_JSON').checked) map.data.loadGeoJson(jsonUrl_Drawn_Json);
						if(document.getElementById('LAYER_LINESTRING').checked) map.data.loadGeoJson(jsonUrl_LineString);
					}
				});
			}
		});

		$(window).load(function() {
			$.ajax({
				dataType: "text",
				success : function () {					
					if(document.getElementById('LAYER_DRAWN_JSON').checked) map.data.loadGeoJson(jsonUrl_Drawn_Json);
					if(document.getElementById('LAYER_LINESTRING').checked) map.data.loadGeoJson(jsonUrl_LineString);
				}
			});
		});
	</script>
 </body>
</html>

