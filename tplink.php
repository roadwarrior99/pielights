<?php
//require 'database.php';
function TurnOnLight($name, $on){
    $parms = array(new dbParameter("@name",$name));
    $lightDT = $db->getTableParm("select top 1 ip from lights where name='@name'",$parms);
    $switch = "on";
    if($on=false) $switch="off";
    $cmd = "sh hs110 " + $lightDT[0]["ip"] + " 9999 " + $switch;
    system($cmd);
}
?>