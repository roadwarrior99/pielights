<?php
        include 'database.php';
        
     try{
        //Handle Request
        $db = new db();
        if($_POST("body")<>null)
        {
            $req = json_decode($_POST("body"),true);
            if($req!=null){
                //Handle Request                       
            }
        }
     } catch (Exception $e)
      {
        $db.nonQuery("insert into error_log(error) values('"+ $e + "')");
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
?>