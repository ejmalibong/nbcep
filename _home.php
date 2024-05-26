<?php
    $title = "Home";
    ob_start(); // start output buffering
?>

<div>
    <p>this is the home page</p>
</div>

<?php
    $content = ob_get_clean(); // capture the buffer into a variable and clean the buffer
    require('master.php');
?>