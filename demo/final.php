<?php
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
    <title>Maps & GIS Applications by Mike Stratton</title>
   
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
	
	
	</form>	
    <div id="panel">		
      <div id="panel-content">
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
				
				</div>
				
		<div class="container">

			<div id="output"></div>
		</div>
		 
       
        
        <textarea name="geofield1" form="geoform" id="geojson-input" placeholder="Drag and drop GeoJSON onto the map or paste it here to begin editing." style="height: 611.5px;"></textarea>
      </div>
	  <div id="geojson-controls">
          <button onclick="document.getElementById('geojson-input').select();">Select All</button>
          <a id="download-link" href="data:;base64," download="geojson.json">
          <button>Download</button>
          </a>
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

		/* @version 0.27 bugfix code below: event listener to get lat lng centered coordinates when user clicks on upload button */
		document.getElementById('upload_image').addEventListener('click', function(e){
			document.getElementById('clatID').setAttribute('value', map.getCenter().lat());
			document.getElementById('clngID').setAttribute('value', map.getCenter().lng());
		});

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

