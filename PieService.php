<?php
        require_once 'settings.php';
        require_once 'database.php';
        require_once 'tplink.php';   
     try{ 
        //Handle Request
        $body = file_get_contents('php://input');
        if($body!=null)
        {
            $req = json_decode($body,true);
            if($req<>null){
                //var_dump($req);
                //Handle Request
                $voice=$req["originalRequest"]["data"]["inputs"][0]["raw_inputs"][0]["query"];
                $action=$req["result"]["action"];
                $light=$req["result"]["parameters"]["light"];
                $speach='There was a problem processing your request.';
                switch($action){
                    case "TurnOnLight":
                        TurnOnLight($light,true,$db);
                        $speach=$light . " was turned on.";
                        break;
                    case "TurnOffLight":
                        TurnOnLight($light,false,$db);
                        $speach=$light . " was turned off.";
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
                $db->nonQueryParm("insert into request(voiceQuery,response) values('@voice','@response');",$parms); 
                
            }
        }
        else 
        {
          if($debug)  echo 'No Body is home.';
        }
     } catch (Exception $e)
    {
        if($debug) echo $e;
        $parms = array(new dbParameter('@error',$e));
        $db->nonQueryParm("insert into error_log(error) values('@error')",$parms);
        if($debug)echo 'Caught exception: '.  $e->getMessage();
        }
?>