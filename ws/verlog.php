<?php
header( "refresh:5;url=verlog.php" );
if (isset($_GET['cleanlog'])) {
    unlink('ws-errors.log');
}
echo '<h3> Last call '.date('d-m-Y H:i:s').'</h3>';

if (file_exists("ws-errors.log")) {   
    echo '<pre>';
    readfile("ws-errors.log");
    echo '</pre>';
}
else
{
    echo '<h1>No log file!</h1>';
}
    

