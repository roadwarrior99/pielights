<?php
    class db{
        public $dbServer = "localhost";
        public $dbUser = "pieService";
        public $dbName ="pieService";
        private $dbPassword = "";
        private $conn;
        function new(){
              $conn = new mysqli($servername, $username, $password);
                // Check connection
                if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                } 
                mysql_connect($dbServer,$dbUser,$dbPassword,$conn);
                if(isServiceInstalled != true) InstallService();
        }//new
        function nonQuery($sql){
            mysql_query($sql,$conn);
        }

        private function parmReplace($sql,$parms){
                        $sqlWParms = $sql;
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
?>