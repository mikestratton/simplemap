var map;
var dropContainer;
var panel;
var geoJsonInput;
var downloadLink;
var selectedFeature = null;
var img;
/* @version 0.27 bugfix code below: reserved default lat lng as constants */
var defaultLat = 38.869256523;
var defaultLng = -77.0535764524;

var scripts = document.getElementsByTagName('script');
var lastScript = scripts[scripts.length-1];
var srcImage = lastScript.getAttribute('src_image');
/* @version 0.27 bugfix code below: getting data attributes lat lng */
var cLat = lastScript.getAttribute('centerLat');
var cLng = lastScript.getAttribute('centerLng');

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

function DraggableOverlay(map,position,image){
	this.setValues({
		position:position,
		container:null,
		image:image,
		map:map
	});
	this.image_ = image;
	this.map_ = map;
	this.container_ = null;
	this.setMap(map);
}

DraggableOverlay.prototype = new google.maps.OverlayView();

DraggableOverlay.prototype.onAdd = function() {

	var container = document.createElement('div');
	container.style.borderStyle = 'none';
	container.style.borderWidth = '0px';
	container.style.position = 'absolute';
	that=this;

	img = document.createElement('img');
	img.src = this.image_;
	img.style.width = '100%';
	img.style.height = '100%';
	img.style.position = 'absolute';

	container.draggable=true;
	google.maps.event.addDomListener(this.get('map').getDiv(), 'mouseleave', function(){
		google.maps.event.trigger(container,'mouseup');
	});

	google.maps.event.addDomListener(container, 'mousedown', function(e){
		this.style.cursor='move';
		that.map.set('draggable',false);
		that.set('origin',e);
		that.moveHandler  = google.maps.event.addDomListener(that.get('map').getDiv(), 'mousemove', function(e){
			var origin = that.get('origin'),
			left = origin.clientX-e.clientX,
			top  = origin.clientY-e.clientY,
			pos  = that.getProjection().fromLatLngToDivPixel(that.get('position')),
			latLng = that.getProjection().fromDivPixelToLatLng(new google.maps.Point(pos.x-left, pos.y-top));
			that.set('origin',e);
			that.set('position',latLng);
			that.draw();
		});
	});

	google.maps.event.addDomListener(container,'mouseup',function(){
		that.map.set('draggable',true);
		this.style.cursor='default';
		google.maps.event.removeListener(that.moveHandler);
	});

	container.appendChild(img);
	this.container_ = container;
	this.getPanes().floatPane.appendChild(container);

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
		max: 800,
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
		max: 800,
		step: 0.1,
		value: 1,
		orientation: "horizontal",
		slide: function(e,ui){
			$(img).css('width', ui.value)
		}
	})	

	$(img).css('width', 300);
	$(img).css('height', 300);
	/* @version 0.25 bugfix code below: upon uploading image opacity is 50% so user can see gmap features behind it */
	$(img).css('opacity', 0.5);
};

function hidePicture(par){
	if (par == true ) $(img).css('opacity', 0);
	if (par == false) $(img).css('opacity', 1);
}

DraggableOverlay.prototype.draw = function() {
	var pos = this.getProjection().fromLatLngToDivPixel(this.get('position'));
	var container = this.container_;
	container.style.left = pos.x -100 + 'px';
	container.style.top = pos.y -100 + 'px';
};

DraggableOverlay.prototype.onRemove = function() {
	this.container_.parentNode.removeChild(this.container_);
	this.container_ = null;
};

function init() {
  
	/*@version 0.27 bugfix code below: map is centered correctly in case lat lng were passed as data attributes */
	initCenterLat = (cLat === "DEFAULT_CENTER") ? defaultLat : parseFloat(cLat);
	initCenterLng = (cLng === "DEFAULT_CENTER") ? defaultLng : parseFloat(cLng);
	// Initialise the map.
	map = new google.maps.Map(document.getElementById('map-holder'), {
		zoom: 16,
		center: {
			lat: initCenterLat,
			lng: initCenterLng
		}
		//mapTypeId: 'satellite'
	});

	if (srcImage !== 'NOIMAGE'){
		overlay = new DraggableOverlay(map, map.getCenter(), srcImage);
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

function clearGeoInputJson(){
	geoJsonInput.value = "";
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
