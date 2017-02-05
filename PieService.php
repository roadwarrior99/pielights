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
                        

                $voice=$req->originalRequest->data->text;
               //Build response
                $resObj = array("","");
                //Start Response---------------------------->
                header("Content-Type: application/json");
                $resJson = json_encode($resObj);
                echo $resJson;
                
                //Log request and response
                $resp=$resJson;
                $parms = array(new dbParameter("@voice",$voice),new dbParameter("@response",$resp));
                $db->nonQueryParm("insert into request(voiceQuery,response) values('@voice','@response');"); 
                
            }
        }
     } catch (Exception $e)
      {
        $parms = array(new dbParameter('@error',$e));
        $db->nonQueryParm("insert into error_log(error) values('@error')",$parms);
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

?>