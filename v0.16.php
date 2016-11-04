<?php

	if(isset($_POST["submit"])) {
        $target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);  
    } 
	else {
		$target_file = null;
	}
	
	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Adding a Custom Overlay</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw"></script>
    <script>
      // This example creates a custom overlay called USGSOverlay, containing
      // a U.S. Geological Survey (USGS) image of the relevant area on the map.

      // Set the custom overlay object's prototype to a new instance
      // of OverlayView. In effect, this will subclass the overlay class therefore
      // it's simpler to load the API synchronously, using
      // google.maps.event.addDomListener().
      // Note that we set the prototype to an instance, rather than the
      // parent class itself, because we do not wish to modify the parent class.

      var overlay;
      USGSOverlay.prototype = new google.maps.OverlayView();

      // Initialize the map and the custom overlay.

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: {lat: 62.323907, lng: -150.109291},
          mapTypeId: 'satellite'
        });

        var bounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(62.281819, -150.287132),
            new google.maps.LatLng(62.400471, -150.005608));

        // The photograph is courtesy of the U.S. Geological Survey.
        var srcImage = '<?php echo $target_file; ?>';

        // The custom USGSOverlay object contains the USGS image,
        // the bounds of the image, and a reference to the map.
        overlay = new USGSOverlay(bounds, srcImage, map);
      }

      /** @constructor */
      function USGSOverlay(bounds, image, map) {

        // Initialize all properties.
        this.bounds_ = bounds;
        this.image_ = image;
        this.map_ = map;

        // Define a property to hold the image's div. We'll
        // actually create this div upon receipt of the onAdd()
        // method so we'll leave it null for now.
        this.div_ = null;

        // Explicitly call setMap on this overlay.
        this.setMap(map);
      }

      /**
       * onAdd is called when the map's panes are ready and the overlay has been
       * added to the map.
       */
      USGSOverlay.prototype.onAdd = function() {

        var div = document.createElement('div');
        div.style.borderStyle = 'none';
        div.style.borderWidth = '0px';
        div.style.position = 'absolute';

        // Create the img element and attach it to the div.
        var img = document.createElement('img');
        img.src = this.image_;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.position = 'absolute';
        div.appendChild(img);

        this.div_ = div;

        // Add the element to the "overlayLayer" pane.
        var panes = this.getPanes();
        panes.overlayLayer.appendChild(div);
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
    </script>
	
	 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css"/>
    <script type="text/javascript">
    $(document).ready(function() {
        //slider for opacity
        $('#opacity').slider({ 
        min: 0, 
        max: 1, 
        step: 0.1, 
        value: 1,
        orientation: "horizontal",
             slide: function(e,ui){
                     $('#edit').css('opacity', ui.value)

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
                     $('#img').rotate(ui.value)

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
                     $('#img').css('height', ui.value)

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
                     $('#img').css('width', ui.value)

             }                
        })
		
    });
</script>
    <style type="text/css">
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
  <h1>Upload Image</h1>
  <form method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<div id="controls">
		 
			 <div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
			 <div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
			 <div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
			 <div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div>
		
		</div>
		
		<div id="output">
		
			<div id="edit">
			
				<p><img id="img" src="<?php echo $target_file; ?>"></p>
				
			</div>
		</div>
    <div id="map"></div>
  </body>
</html>