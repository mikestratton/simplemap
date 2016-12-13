
// The Google Map.
var map;

// The HTML element that contains the drop container.
var dropContainer;
var panel;
var geoJsonInput;
var downloadLink;
var selectedFeature = null;

var scripts = document.getElementsByTagName('script');
var lastScript = scripts[scripts.length-1];
var srcImage = lastScript.getAttribute('src_image');

function loadScript(url, completeCallback) {
	var script = document.createElement('script'), done = false,
		head = document.getElementsByTagName("head")[0];
		script.src = url;
	script.onload = script.onreadystatechange = function(){
		if ( !done && (!this.readyState ||
		this.readyState == "loaded" || this.readyState == "complete") ) {
			done = true;
			completeCallback();
			// IE memory leak
			script.onload = script.onreadystatechange = null;
			head.removeChild( script );
		}
	};
	head.appendChild(script);
}

loadScript("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js", function (){ });
loadScript("http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js", function (){ });
loadScript("http://beneposto.pl/jqueryrotate/js/jQueryRotateCompressed.js", function (){ });

/** @constructor */
function USGSOverlay(bounds, image, map) {

	this.bounds_ = bounds;
	this.image_ = image;
	this.map_ = map;
	this.div_ = null;
	this.setMap(map);
}


var overlay;
USGSOverlay.prototype = new google.maps.OverlayView();

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


function init() {
  
	// Initialise the map.
	map = new google.maps.Map(document.getElementById('map-holder'), {
		zoom: 11,
		center: {lat: 62.323907, lng: -150.109291},
		//mapTypeId: 'satellite'
	});

	var bounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(62.281819, -150.287132),
		new google.maps.LatLng(62.400471, -150.005608));

	if (srcImage !== 'NOIMAGE'){
		overlay = new USGSOverlay(bounds, srcImage, map);
	}

	map.data.setControls(['Point', 'LineString', 'Polygon']);
	map.data.setStyle({
		editable: true,
		draggable: true
	});
	bindDataLayerListeners(map.data);

	// Retrieve HTML elements.
	dropContainer = document.getElementById('drop-container');
	panel = document.getElementById('panel');
	var mapContainer = document.getElementById('map-holder');
	geoJsonInput = document.getElementById('geojson-input');
	downloadLink = document.getElementById('download-link');
	deleteButton = document.getElementById('delete-button');

	// Resize the geoJsonInput textarea.
	resizeGeoJsonInput();

	// Set up the drag and drop events.
	// First on common events.
	[mapContainer, dropContainer].forEach(function(container) {
		google.maps.event.addDomListener(container, 'drop', handleDrop);
		google.maps.event.addDomListener(container, 'dragover', showPanel);
	});

	// Then map-specific events.
	google.maps.event.addDomListener(mapContainer, 'dragstart', showPanel);
	google.maps.event.addDomListener(mapContainer, 'dragenter', showPanel);

	// Then the overlay specific events (since it only appears once drag starts).
	google.maps.event.addDomListener(dropContainer, 'dragend', hidePanel);
	google.maps.event.addDomListener(dropContainer, 'dragleave', hidePanel);
	// Set up events for changing the geoJson input.
	google.maps.event.addDomListener(
		geoJsonInput,
		'input',
		refreshDataFromGeoJson);
	google.maps.event.addDomListener(
		geoJsonInput,
		'input',
		refreshDownloadLinkFromGeoJson);

	// Set up events for styling.
	google.maps.event.addDomListener(window, 'resize', resizeGeoJsonInput);

	map.data.addListener('click', function(event) {
		setSelection(event.feature);
	});

	google.maps.event.addDomListener(deleteButton, 'click', deleteSelection);
  
}

google.maps.event.addDomListener(window, 'load', init);

function setSelection(feature){
	selectedFeature = feature;
}

function deleteSelection(){
	if (selectedFeature !== null){
		map.data.remove(selectedFeature);
	}
}

// Refresh different components from other components.
function refreshGeoJsonFromData() {
	map.data.toGeoJson(function(geoJson) {
		geoJsonInput.value = JSON.stringify(geoJson, null, 2);
		refreshDownloadLinkFromGeoJson();
	});
}

// Replace the data layer with a new one based on the inputted geoJson.
function refreshDataFromGeoJson() {
	var newData = new google.maps.Data({
		map: map,
		style: map.data.getStyle(),
		controls: ['Point', 'LineString', 'Polygon']
	});
	try {
		var userObject = JSON.parse(geoJsonInput.value);
		var newFeatures = newData.addGeoJson(userObject);
	} catch (error) {
		newData.setMap(null);
	if (geoJsonInput.value !== "") {
		setGeoJsonValidity(false);
	} else {
		setGeoJsonValidity(true);
	}
	return;
	}
	// No error means GeoJSON was valid!
	map.data.setMap(null);
	map.data = newData;
	bindDataLayerListeners(newData);
	setGeoJsonValidity(true);
}

// Refresh download link.
function refreshDownloadLinkFromGeoJson() {
	downloadLink.href = "data:;base64," + btoa(geoJsonInput.value);
}

// Apply listeners to refresh the GeoJson display on a given data layer.
function bindDataLayerListeners(dataLayer) {
	dataLayer.addListener('addfeature', refreshGeoJsonFromData);
	dataLayer.addListener('removefeature', refreshGeoJsonFromData);
	dataLayer.addListener('setgeometry', refreshGeoJsonFromData);
}

// Display the validity of geoJson.
function setGeoJsonValidity(newVal) {
	if (!newVal) {
		geoJsonInput.className = 'invalid';
	} else {
		geoJsonInput.className = '';
	}
}

// Control the drag and drop panel. Adapted from this code sample:
// https://developers.google.com/maps/documentation/javascript/examples/layer-data-dragndrop
function showPanel(e) {
	e.stopPropagation();
	e.preventDefault();
	dropContainer.className = 'visible';
	return false;
}

function hidePanel() {
	dropContainer.className = '';
}

function handleDrop(e) {
	e.preventDefault();
	e.stopPropagation();
	hidePanel();

	var files = e.dataTransfer.files;
	if (files.length) {
		// process file(s) being dropped
		// grab the file data from each file
		for (var i = 0, file; file = files[i]; i++) {
			var reader = new FileReader();
			reader.onload = function(e) {
				map.data.addGeoJson(JSON.parse(e.target.result));
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
			map.data.addGeoJson(JSON.parse(plainText));
		}
	};
	// prevent drag event from bubbling further
	return false;
}

// Styling related functions.
function resizeGeoJsonInput() {
	var geoJsonInputRect = geoJsonInput.getBoundingClientRect();
	var panelRect = panel.getBoundingClientRect();
	geoJsonInput.style.height = panelRect.bottom - geoJsonInputRect.top - 8 + "px";
}
