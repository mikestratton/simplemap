<!DOCTYPE html>
<?php $timestamp = date("YmdHis");?>
<html>
  <head>
  	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Maps & GIS Web Applications with Google Maps JavaScript API</title>
    
	
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css"/>
	<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="stylesheet" href="styles/googlemaps.css" type="text/css"/>
    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <link href="styles/style.css" rel="stylesheet">
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js"></script>
	  
  </head>

  <body>


    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3">
				<div class="logo">
	  <p><a href="http://mikestratton.net"><img src="http://www.mikestratton.net/assets/text_logo_174x40.png"></a></p>
	  <p><a href="http://mikestratton.net">Geographical Information Systems<br />
	  Built Using Google Maps Javascript API</a></p>
	 
	  </div>
				</div>
				<div class="col-md-3">
				<div id="controls">
								<p>Image Controls</p>
				<div class="row">
					<div class="col-md-6">
						<div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
					</div>
					<div class="col-md-6">
						<div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
					</div>
					<div class="col-md-6">
						<div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div>
					</div>
				</div>
				
				
				
			
				<form action="final.php" method="post" enctype="multipart/form-data">
					
					
					<!-- @version 0.27 bugfix code below: form to upload image little modifications -->
					<input id="upload_image" type="hidden"  name="submit">
					<input type="hidden" name="clat" id="clatID" value="">
					<input type="hidden" name="clng" id="clngID" value="">
				</form>
				</div>
				</div>
				<div class="col-md-3">
				<div id="panel">		
      <div id="panel-content">
		<div class="container">
		
			
			<div id="output"></div>
		</div>
		 
        
        
        <textarea name="geofield1" form="geoform" id="geojson-input" placeholder="Drag and drop GeoJSON onto the map or paste it here to begin editing." ></textarea>
      <div id="geojson-controls">
          <button onclick="document.getElementById('geojson-input').select();">Select All</button>
          <a id="download-link" href="data:;base64," download="geojson.json">
          <button>Download</button>
          </a>
        </div>
		</div>
    </div>
				</div>
				<div class="col-md-3">
					<form class="input-form" id="input-form">
		<p><table style="margin-left:5%" border="0">
				<tr><td>Latitude:&nbsp;&nbsp; </td><td style="text-align:left;"><input type="text" name="lat" id="lat-input" value="40.7116" /></td></tr>
				<tr><td>Longitude:&nbsp;&nbsp; </td><td style="text-align:left;"><input type="text" name="lon" id="lon-input" value="-74.0132" /></td></tr>
				<tr><td>Zoom Level:&nbsp;&nbsp; </td><td style="text-align:left;"><input type="text" name="zoom" id="zoom-input" value="18" /></td></tr>
				<tr><td>&nbsp;</td>
					<td style="text-align:right; padding:3px 12px">
					<input type="submit" value="Center Map" />
					</td>
				</tr>
			</table>			
		</p>	
	</form>	
				</div>
			</div>
			
			
		</div>
	</div>
</div>


  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
<a href="https://github.com/mikestratton/simplemap"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://camo.githubusercontent.com/567c3a48d796e2fc06ea80409cc9dd82bf714434/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png"></a>

    
    <div><button id="delete-button">Delete</button></div>
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
	    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
 </body>
</html>

