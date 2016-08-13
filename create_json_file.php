<?php
//http://stackoverflow.com/questions/3775281/save-current-page-as-html-to-server
// Start the buffering //
ob_start();
?>
Your page content bla bla bla bla ...

<?php
//http://stackoverflow.com/questions/3775281/save-current-page-as-html-to-server
echo '1';

// Get the content that is in the buffer and put it in your file //
file_put_contents('yourpage.html', ob_get_contents());
?>


<?php
// http://stackoverflow.com/questions/1697484/a-button-to-start-php-script-how
  if (!empty($_GET['act'])) {
    echo "Hello world!"; //Your code here
  } else {
?>
(.. your html ..)
<form action="index.php" method="get">
  <input type="hidden" name="act" value="run">
  <input type="submit" value="Run me now!">
</form>
<?php
  }
?>