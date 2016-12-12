<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
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
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
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
<html>
  <head>  
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css"/>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
		body { margin: 10px 32px;}
		label { float:left; text-align: center; }
		#edit { width:auto; min-height:400px; }
		#opacity { width: 200px; margin:14px; }
		#rotate {  width: 200px; margin:14px; }
		#height {  width: 200px; margin:14px; }
		#width {  width: 200px; margin:14px; }
		#controls { background:skyblue; float:left; margin:25px 30px; float:left; padding:10px; border:medium blue solid;}
		#output { width:auto; float:left; padding:10px;  }
		.slabel {font-size: 12px; font-weight:500; color:white; }
		.slide-bg { background:blue; color:navy; text-align:center; }
    </style>
</head>

<body>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw"></script>
	<script type="text/javascript">
 
	$(document).ready(function() {

		var overlay;
		USGSOverlay.prototype = new google.maps.OverlayView();

		function initMap() {
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 11,
				center: {lat: 62.323907, lng: -150.109291},
				mapTypeId: 'satellite'
			});

			var bounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(62.281819, -150.287132),
				new google.maps.LatLng(62.400471, -150.005608));

			var srcImage = '<?php echo $target_file; ?>';
			overlay = new USGSOverlay(bounds, srcImage, map);
		}

		/** @constructor */
		function USGSOverlay(bounds, image, map) {

			this.bounds_ = bounds;
			this.image_ = image;
			this.map_ = map;
			this.div_ = null;
			this.setMap(map);
		}

		USGSOverlay.prototype.onAdd = function() {

			var div = document.createElement('div');
			div.style.borderStyle = 'none';
			div.style.borderWidth = '0px';
			div.style.position = 'absolute';

			var img = document.createElement('img');
			img.src = this.image_;
			img.style.width = '100%';
			img.style.height = '100%';
			img.style.position = 'absolute';
			div.appendChild(img);

			this.div_ = div;
			var panes = this.getPanes();
			panes.overlayLayer.appendChild(div);

			//slider for opacity
			$('#opacity').slider({
				min: 0,
				max: 1,
				step: 0.1,
				value: 1,
				orientation: "horizontal",
				slide: function(e,ui){
					$(img).css('opacity', ui.value)
				}
			})

			//slider for rotation  
			$('#rotate').slider({
				min: 0,
				max: 360,
				step: 0.1,
				value: 1,
				orientation: "horizontal",
				slide: function(e,ui){
					$(img).rotate(ui.value)
				}
			})
			
			//slider for height
			$('#height').slider({
				min: 0,
				max: 1500,
				step: 0.1,
				value: 1,
				orientation: "horizontal",
				slide: function(e,ui){
					$(img).css('height', ui.value)
				}
			})
		
			//slider for width
			$('#width').slider({
				min: 0,
				max: 1500,
				step: 0.1,
				value: 1,
				orientation: "horizontal",
				slide: function(e,ui){
					$(img).css('width', ui.value)
				}
			})
		};

		USGSOverlay.prototype.draw = function() {

			// We use the south-west and north-east
			// coordinates of the overlay to peg it to the correct position and size.
			// To do this, we need to retrieve the projection from the overlay.
			var overlayProjection = this.getProjection();

			// Retrieve the south-west and north-east coordinates of this overlay
			// in LatLngs and convert them to pixel coordinates.
			// We'll use these coordinates to resize the div.
			var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
			var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

			// Resize the image's div to fit the indicated dimensions.
			var div = this.div_;
			div.style.left = sw.x + 'px';
			div.style.top = ne.y + 'px';
			div.style.width = (ne.x - sw.x) + 'px';
			div.style.height = (sw.y - ne.y) + 'px';
		};

		// The onRemove() method will be called automatically from the API if
		// we ever set the overlay's map property to 'null'.
		USGSOverlay.prototype.onRemove = function() {
			this.div_.parentNode.removeChild(this.div_);
			this.div_ = null;
		};

		google.maps.event.addDomListener(window, 'load', initMap);
	});
	</script>

	<div class="container">
		<div id="controls">
			<div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
			<div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
			<div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
			<div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div>
		</div>
		<div id="output"></div>
	</div>
	<div id="map"></div>
</body>
</html>
