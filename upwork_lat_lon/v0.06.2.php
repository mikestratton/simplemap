<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0" />
    <title>Drag and Drop GeoJSON with Drawing tools</title>
    <link rel="stylesheet" href="../style.css" type="text/css">
    <style>
     .position-form {
        float: left;
        text-align: right;
        padding: 0 15px;
      }
    </style>
</head>
  <body>
    <form method="post" class="position-form">
      <p>
        <table border="0">
          <tr>
            <td>Latitude:</td>
            <td><input type="text" name="lat" /></td>
          </tr>
          <tr>
            <td>Longitude:</td>
            <td><input type="text" name="lon" /></td>
          </tr>
          <tr>
            <td>Zoom Level:</td>
            <td><input type="text" name="zoom" /></td>
          </tr>
        </table>
      </p>
      <p><input type="submit" /></p>
    </form>
    <?php if (isset($_POST['lat'])) { ?>
    <div id="map"></div>
    <div id="drop-container"><div id="drop-silhouette"></div></div>
    
      <script>
      /* Map functions */
      var map;
	  
	  /* User Input for Lat/Lon */
	  var lat = "41";
	  var lon = "81";
	  
      function initMap() {
        // set up the map
        map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(<?php echo $_POST['lat'];?>, <?php echo $_POST['lon'];?>),
          zoom: <?php echo $_POST['zoom'];?>,
		  mapTypeId: google.maps.MapTypeId.SATELLITE,
        });
		
		var drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.MARKER,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['marker', 'polygon', 'polyline']
          },
           circleOptions: {
            fillColor: '#ffff00',
            fillOpacity: 1,
            strokeWeight: 5,
            clickable: false,
            editable: true,
            zIndex: 1
          }
        });
        drawingManager.setMap(map);
      }

      function loadGeoJsonString(geoString) {
        var geojson = JSON.parse(geoString);
        map.data.addGeoJson(geojson);
        zoom(map);
      }

      /**
       * Update a map's viewport to fit each geometry in a dataset
       * @param {google.maps.Map} map The map to adjust
       */
      function zoom(map) {
        var bounds = new google.maps.LatLngBounds();
        map.data.forEach(function(feature) {
          processPoints(feature.getGeometry(), bounds.extend, bounds);
        });
        map.fitBounds(bounds);
      }

      /**
       * Process each point in a Geometry, regardless of how deep the points may lie.
       * @param {google.maps.Data.Geometry} geometry The structure to process
       * @param {function(google.maps.LatLng)} callback A function to call on each
       *     LatLng point encountered (e.g. Array.push)
       * @param {Object} thisArg The value of 'this' as provided to 'callback' (e.g.
       *     myArray)
       */
      function processPoints(geometry, callback, thisArg) {
        if (geometry instanceof google.maps.LatLng) {
          callback.call(thisArg, geometry);
        } else if (geometry instanceof google.maps.Data.Point) {
          callback.call(thisArg, geometry.get());
        } else {
          geometry.getArray().forEach(function(g) {
            processPoints(g, callback, thisArg);
          });
        }
      }


      /* DOM (drag/drop) functions */

      function initEvents() {
        // set up the drag & drop events
        var mapContainer = document.getElementById('map');
        var dropContainer = document.getElementById('drop-container');

        // map-specific events
        mapContainer.addEventListener('dragenter', showPanel, false);

        // overlay specific events (since it only appears once drag starts)
        dropContainer.addEventListener('dragover', showPanel, false);
        dropContainer.addEventListener('drop', handleDrop, false);
        dropContainer.addEventListener('dragleave', hidePanel, false);
      }

      function showPanel(e) {
        e.stopPropagation();
        e.preventDefault();
        document.getElementById('drop-container').style.display = 'block';
        return false;
      }

      function hidePanel(e) {
        document.getElementById('drop-container').style.display = 'none';
      }

      function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        hidePanel(e);

        var files = e.dataTransfer.files;
        if (files.length) {
          // process file(s) being dropped
          // grab the file data from each file
          for (var i = 0, file; file = files[i]; i++) {
            var reader = new FileReader();
            reader.onload = function(e) {
              loadGeoJsonString(e.target.result);
            };
            reader.onerror = function(e) {
              console.error('reading failed');
            };
            reader.readAsText(file);
          }
        } else {
          // process non-file (e.g. text or html) content being dropped
          // grab the plain text version of the data
          var plainText = e.dataTransfer.getData('text/plain');
          if (plainText) {
            loadGeoJsonString(plainText);
          }
        }

        // prevent drag event from bubbling further
        return false;
      }

      function initialize() {
        initMap();
        initEvents();
      }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw&libraries=drawing&callback=initialize"></script>
    <?php } ?>
  </body>
</html>
