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
        //Gimme a list of columns for given table.
        //I think there is a native mysqli function that does this.
        //My intent was just to check and see if the table existed and grabbing the columns seemed like it might be handy.
        function getTableColumns($name){
            //echo 'checking on table ' . $name . '</br>';
            $exists = false;
            $smt = "select column_name from information_schema.columns where table_name='". $name . "';";
           // echo 'table col check:' . $smt;
            $result = $this->conn->query($smt);
            if($this->conn->error !=null) echo $this->conn->error . '<br/>';
            if($result!=null && $result->num_rows > 0 ){
                $exists=true;
               // echo 'Table: ' . $name . ' exists.';
            }
            
            return $exists;
        }  
        function IsServiceInstalled(){
            $installed = true;
            if($this->getTableColumns('error_log')==null){
               //echo 'Table error_log is not installed<br/>';
                $installed=false;
            }
            if($this->getTableColumns('request')==null)
            {
                //echo 'Table request is not installed <br/>';
                $installed=false;
            }
            if($this->getTableColumns('light')==null){
                //echo 'Table light is not installed.<br/>';
                $installed=false;
                }
            return $installed;
        }
        ///
        ///Does not work. Doesn't error but does not work.
        //At some point should have the option to just install missing table.
        ///
        function InstallService(){
            echo 'Install Service called</br>';
            $sql = <<<SQL
            CREATE TABLE `pieservice`.`error_log` ( `id` INT NOT NULL , `timestamp` TIMESTAMP NOT NULL , `error_message` TEXT NOT NULL , `json` JSON NULL , PRIMARY KEY (`id`) USING BTREE) ENGINE = InnoDB; 
SQL;
            $result=$this->conn->query($sql);
            if($this->conn->error!=null) echo $this->conn->error . '<br/>';
               // echo $result; 
/*
create table `pieservice`.`request` ( `id` int not null, `timestamp` TIMESTAMP , `input` JSON NULL, `voiceQuery` text,`response` JSON NULL);
create table 'pieservice'.'light' (`id` int not null, `name` text, `ip` text);

*/
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