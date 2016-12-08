<!DOCTYPE html>
<html>
  <head>
    <title>TSSP Simple Map</title>
   <link rel="stylesheet" href="styles/style.css" type="text/css">

</head>
  <body style="margin:20px 40px">
<h1>Upload Image</h1>
<form name="upload1" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
   
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
  </body>
</html>	
