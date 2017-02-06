<?php
include 'database.php';
function TurnOnLight($name){
    $parms = array(new dbParameter("@name",$name));
    $lightDT = $db->getTableParm("select top 1 ip from lights where name='@name'",$parms);
    $cmd = "sh hs110 " + $lightDT[0]["ip"] + " 9999 on";
    system($cmd);
}
?>