<?php
$PAGETITLE = "All Users";
//: check online status
if(isset($_POST["onlineCheck"]) && confirm_url_id(1, 'onlineCheck')) {
    print 1;
    exit;
}
?>