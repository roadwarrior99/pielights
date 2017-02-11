<?php
    class xpieDB{ 
        //DB related
        function new(){
                $dbServer = "localhost";
                $dbUser = "pieService";
                $dbName ="pieService";
                $dbPassword = "";

                // Check connection
                $conn = new mysqli($dbServer, $dbUser, $dbPassword);
                if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                } 
                mysql_connect($dbServer,$dbUser,$dbPassword,$conn);
                if(isServiceInstalled != true && $install_tables) InstallService();
        }//new
        function nonQuery($sql){
            mysql_query($sql,$conn);
        }

        private function parmReplace($sql,$parms){
            $sqlWParms = $sql.str_replace("'","''");
            foreach($parms as $parm)
            {
                $sqlWParms = $sqlWParms.str_replace($parm.name,$parm.value);
            }
            return $sqlWParms;
        }
        function NonQueryParm($sql,$parms){
            $sqlWParms=parmReplace($sql,$parms);
            nonQuery($sqlWParms);
        }
        function getTableParm($sql,$parms){
            $sqlWParms = parmReplace($sql,$parms);
            getTable($sqlWParms);
        }
        function getTable($sql){
            $result = mysql_db_query($dbName,$sql,$conn);
            return $result;
        } 
        function getTableColumns($name){
            $result = mysql_db_query($dbName,"select column_name from information_schema.columns where table_name='" + $name + "'");
            if($result!=null)
            {
                return $result;
            }else return null;
        }
        function IsServiceInstalled(){
            $installed = true;
            if(getTableColumns('error_log')==null)$installed=false;
            if(getTableColumns('request')==null)$installed=false;
            return $installed;
        }
        function InstallService(){
            $sql = 'create table error_log(id identity, occcured timestamp,error text);
                create table request(id identity, occured timestamp, voiceQuery text,response text);
                create table light(id identity, name text, ip text);';
                mysql_db_query($dbName,$sql,$conn);
        }
    }//class db
    class dbParameter{
        public $name="";
        public $value="";
        function new($nm,$vl){
            $name=$nm;
            $value=$vl;
        }
    }
    $db = new xpieDB;
    $db->new();
?>