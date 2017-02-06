<?php
        require 'database.php';
        require 'tplink.php';
        
     try{
        //Handle Request
        if($_POST("body")<>null)
        {
            $req = json_decode($_POST("body"),true);
            if($req!=null){
                //Handle Request
                        

                $voice=$req->originalRequest->data->text;
                $action=$req->result->action;
                $light=$req->result->contexts->parameters->light;
                $speach='There was a problem processing your request.';
                switch($action){
                    case "TurnOnLight":
                        TurnLight($light,true);
                        $speach=$light + " was turned on.";
                        break;
                    case "TurnOfLight":
                        TurnLight($light,false);
                        $speach=$light + " was turned off.";
                }
                
                
               //Build response
                
                $resObj = array('speach'=> $speach
                ,'displayText'=>$speach
                ,'data'=>''
                ,'contextOut'=>''
                ,'source'=>'pielights');

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