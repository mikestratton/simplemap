<!DOCTYPE html>
<?php $timestamp = date("YmdHis");?>
<html>
  <head>
  
	<style type="text/css">.gm-style .gm-style-mtc label,.gm-style .gm-style-mtc div{font-weight:400}</style>
    <style type="text/css">.gm-style-pbc{transition:opacity ease-in-out;background-color:black;text-align:center}.gm-style-pbt{font-size:22px;color:white;font-family:Roboto,Arial,sans-serif;position:relative;margin:0;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}</style>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <style type="text/css">.gm-style .gm-style-cc span,.gm-style .gm-style-cc a,.gm-style .gm-style-mtc div{font-size:10px}</style>
    <style type="text/css">@media print {  .gm-style .gmnoprint, .gmnoprint {    display:none  }}@media screen {  .gm-style .gmnoscreen, .gmnoscreen {    display:none  }}</style>
    <style type="text/css">.gm-style{font-family:Roboto,Arial,sans-serif;font-size:11px;font-weight:400;text-decoration:none}.gm-style img{max-width:none}</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple GeoJSON Editor</title>
    <link href="styles/style.css" title="compact" rel="stylesheet" type="text/css">
    <style type="text/css">
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
      width: 64.9%;
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
      width: 35%;
      height: 100%;
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

    <div id="panel">
      <div id="panel-content">
        <div id="panel-title">Simple GeoJSON Editor</div>
        <div id="geojson-controls">
          <button onclick="document.getElementById('geojson-input').select();">Select All</button>
          <a id="download-link" href="data:;base64," download="geojson.json">
          <button>Download</button>
          </a>
          <form action="geo_form.php" method="POST" id="geoform">
            <input type="submit" name="submit" value="Save GeoJSON">
          </form>
        </div>
        <textarea name="geofield1" form="geoform" id="geojson-input" placeholder="Drag and drop GeoJSON onto the map or paste it here to begin editing." style="height: 611.5px;"></textarea>
      </div>
    </div>
    <div id="map-container">
      <div id="map-holder" style="position: relative; overflow: hidden; background-color: rgb(229, 227, 223);"></div>
      <div id="drop-container" class=""></div>
    </div>

	<script type='text/javascript' src='//code.jquery.com/jquery-1.11.0.js'></script>
	<script type='text/javascript'>
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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDs-xOsEgkg1mfUEDCVNQMnd-Fw2oEnADw&libraries=drawing?t=<?php echo $timestamp; ?>"></script>
	<script src="https://google-developers.appspot.com/maps/documentation/utils/geojson/editor.js?t=<?php echo $timestamp; ?>"></script>

 </body>
</html>
