<?php
        include 'database.php';
        include 'tplink.php';
        
     try{
        //Handle Request
        $db = new db();
        if($_POST("body")<>null)
        {
            $req = json_decode($_POST("body"),true);
            if($req!=null){
                //Handle Request
                        

                $voice=$req->originalRequest->data->text;
                $action=$req->result->action;
                $light=$req->result->contexts->parameters->light;
                switch($action){
                    case "TurnOnLight":
                        TurnLight($light,true);
                        break;
                    case "TurnOfLight":
                        TurnLight($light,false);
                }
                
                
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