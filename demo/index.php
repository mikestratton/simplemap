<?php
$target_dir = "uploads/";
$target_file = null;
/* @version 0.28 bugfix code below: php undefined index notices */
if (isset($_FILES["fileToUpload"])){
	$imageFileName = (isset($_FILES["fileToUpload"]["name"])) ? $_FILES["fileToUpload"]["name"] : null;
	$imageFileSize = (isset($_FILES["fileToUpload"]["size"])) ? $_FILES["fileToUpload"]["size"] : null;
	$imageFileTmpName = (isset($_FILES["fileToUpload"]["tmp_name"])) ? $_FILES["fileToUpload"]["tmp_name"] : null;
	$target_file = $target_dir . basename($imageFileName);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($imageFileTmpName);
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
	if ($imageFileSize > 50000000) {
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
		if (move_uploaded_file($imageFileTmpName, $target_file)) {
		//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			// echo "Sorry, there was an error uploading your file.";
		}
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
<a href="https://github.com/mikestratton/simplemap"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://camo.githubusercontent.com/567c3a48d796e2fc06ea80409cc9dd82bf714434/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png"></a>
	<form class="input-form" id="input-form">
		<p><table border="0">
				<tr><td>Latitude:</td><td><input type="text" name="lat" id="lat-input" value="40.7116" /></td></tr>
				<tr><td>Longitude:</td><td><input type="text" name="lon" id="lon-input" value="-74.0132" /></td></tr>
				<tr><td>Zoom Level:</td><td><input type="text" name="zoom" id="zoom-input" value="18" /></td></tr>
				<tr><td>&nbsp;</td>
					<td style="text-align:right; padding:3px 12px">
					<input type="submit" value="Center Map" />
					</td>
				</tr>
			</table>			
		</p>	
	</form>	
    <div id="panel">		
      <div id="panel-content">
		<div class="container">
		<div class="logo">
	  <p><a href="http://mikestratton.net"><img src="http://www.mikestratton.net/assets/text_logo_174x40.png"></a></p>
	  <h3><a href="http://mikestratton.net">Geographical Information Systems for the Web<br />
	  Built With Care Using Google Maps Javascript API</a></h3>
	  <p><a href="http://mikestratton.net"><img src="uploads/globe_by_atlantis.png" width="80px" height="80px" title="Image courtesy of Atlantis https://openclipart.org/user-detail/atlantis"></a>
	  </p>
	  </div>
			<div id="controls">
								<h2 style="color:white;text-align:center;background:navy;border:double thick white;padding:4px;">Image Controls</h2>
				<div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
				<div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
				<div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
				<div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div><br>
			
				<form action="final.php" method="post" enctype="multipart/form-data">
					
					
					<!-- @version 0.27 bugfix code below: form to upload image little modifications -->
					<input id="upload_image" type="hidden"  name="submit">
					<input type="hidden" name="clat" id="clatID" value="">
					<input type="hidden" name="clng" id="clngID" value="">
				</form>
				</div>
			<div id="output"></div>
		</div>
		 
        
        
        <textarea name="geofield1" form="geoform" id="geojson-input" placeholder="Drag and drop GeoJSON onto the map or paste it here to begin editing." style="height: 611.5px;"></textarea>
      <div id="geojson-controls">
          <button onclick="document.getElementById('geojson-input').select();">Select All</button>
          <a id="download-link" href="data:;base64," download="geojson.json">
          <button>Download</button>
          </a>
        </div>
		</div>
    </div>
    <div><button id="delete-button">Delete Selected Shape</button></div>
    <div id="map-container">
      <div id="map-holder" style="position: relative; overflow: hidden; background-color: rgb(229, 227, 223);"></div>
      <div id="drop-container" class=""></div>
    </div>

	<script type='text/javascript' src='//code.jquery.com/jquery-1.11.0.js'></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw&libraries=drawing"></script>
	<!-- @version 0.27 bugfix code below: passing coordinates data attributes in case page is reloaded upon user image upload attempt -->
	<script src="final_geoeditor.js?t=<?php echo $timestamp; ?>" type="text/javascript"
		src_image="uploads/pentagon.gif"
		centerLat = "<?php if (isset($_POST['clat'])){ echo $_POST['clat'] ;}else{echo "DEFAULT_CENTER";} ?>"
		centerLng = "<?php if (isset($_POST['clng'])){ echo $_POST['clng'] ;}else{echo "DEFAULT_CENTER";} ?>"
	></script>

    <script type='text/javascript'>



		jsonUrl_LineString = 'linestring/property01.json';
	

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




	</script>
 </body>
</html>

