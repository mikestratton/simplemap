<?php

	if(isset($_POST["submit"])) {
        $target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$img = "<img id=\"img\" src=\"" . $target_file . "\">";   
    } 
	else {
		$img = 'Your uploaded image will appear here';
	}
	
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TSSP Simple Map</title>
  
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


	<div class="container">

		<h1>Upload Image</h1>
		
		<form method="post" enctype="multipart/form-data">
			Select image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Upload Image" name="submit">
		</form>

		<hr>

		<div id="controls">
		 
			 <div id="opacity" class="slide-bg" data-wjs-element="edit"><span class="slabel">OPACITY</span></div>
			 <div id="rotate" class="slide-bg" data-wjs-element="edit"><span class="slabel">ROTATE</span></div>
			 <div id="height" class="slide-bg" data-wjs-element="hw"><span class="slabel">HEIGHT</span></div>
			 <div id="width" class="slide-bg" data-wjs-element="hw"><span class="slabel">WIDTH</span></div>
		
		</div>
		
		<div id="output">
		
			<div id="edit">
			
				<p><?php echo $img; ?></p>
				
			</div>
		</div>
		
	</div>
  </body>
</html>	
