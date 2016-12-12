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
if ($_FILES["fileToUpload"]["size"] > 500000) {
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
<h1>Pass assigned variable to image editing tool</h1>
<hr>
<h2>jQuery image editing: transparency, rotate, resize</h2>

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
	


</div>
  </body>
</html>	
