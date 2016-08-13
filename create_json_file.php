<?php
// Start the buffering //
ob_start();
?>
Your page content bla bla bla bla ...

<?php
echo '1';

// Get the content that is in the buffer and put it in your file //
file_put_contents('yourpage.html', ob_get_contents());
?>