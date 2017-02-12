<?php
    class xpieDB{
        //DB related
        public $dbServer = "localhost";
        public $dbUser = "pieservice";
        public $dbName ="pieservice";
        public $dbPassword = "";
        public $conn = mysqli;
       public function __construct(){
             $this->conn = new mysqli($this->dbServer, $this->dbUser, $this->dbPassword,$this->dbName);
                if ($this->conn->connect_error) {
                        die("Connection failed: " . $this->conn->connect_error);
                } 
                if($this->IsServiceInstalled() <> true) $this->InstallService();
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
        //this needs to be refactored
        function getTableParm($sql,$parms){
            $sqlWParms = parmReplace($sql,$parms);
            getTable($sqlWParms);
        }
        function getTable($sql){
            $result = mysql_db_query($dbName,$sql,$conn);
            return $result;
        } 
        function getTableColumns($name){
            echo 'checking on table ' + $name;
            $exists = false;
            $smt = <<<SQL
             select column_name from 'pieservice'.information_schema.columns where table_name='$name';
SQL;
            echo 'table col check:' + $smt;
            $result = $this->conn->query($smt);
            if($result && $result->num_rows > 0 ){
                $exists=true;
                echo 'Table: ' + $name + ' exists.';
            } else 'cry cry cry ' + $name;
            return $exists;
        }  
        function IsServiceInstalled(){
            $installed = true;
            if($this->getTableColumns('error_log')==null){
               echo 'Table error_log is not installed<br/>';
                $installed=false;
            }
            if($this->getTableColumns('request')==null)
            {
                echo 'Table request is not installed <br/>';
                $installed=false;
            }
            if($this->getTableColumns('light')==null){
                echo 'Table light is not installed.<br/>';
                $installed=false;
                }
            return $installed;
        }
        ///
        ///Does not work. Doesn't error but does not work.
        ///
        function InstallService(){
            echo 'Install Service called</br>';
            $sql = <<<SQL
CREATE TABLE `pieservice`.`error_log` ( `id` INT NOT NULL , `timestamp` TIMESTAMP NOT NULL , `error_message` TEXT NOT NULL , `json` JSON NULL , PRIMARY KEY (`id`) USING BTREE) ENGINE = InnoDB;

create table `pieservice`.`request` (`id` int not null, `timestamp` timestamp NOT NULL,`request` JSON NULL, `voiceQuery` text,`response` JSON NULL);

create table 'pieservice'.'light' (`id` int not null, `name` text, `ip` text);
SQL;
                $result=$this->conn->query($sql);
                echo $this->conn->error + '<br/>';
               // echo $result; 
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
    //$db->new();
?>