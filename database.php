<?php
    class xpieDB{
        //DB related
        public $dbServer = "localhost";
        public $dbUser = "pieservice";
        public $dbName ="pieservice";
        public $conn = mysqli;
        public $tables = array("light","error_log","request");
        public $missingTables = array();
       public function __construct($dbPassword){
             $this->conn = new mysqli($this->dbServer, $this->dbUser, $dbPassword,$this->dbName);
                if ($this->conn->connect_error) {
                        die("Connection failed: " . $this->conn->connect_error);
                } 
                if($this->IsServiceInstalled() <> true) $this->InstallService();
        }//new
        function nonQuery($sql){
            $sql = $this->conn->real_escape_string($sql);
            $this->conn->query($sql); 
        }

        private function parmReplace($sql,$parms){
            $sqlWParms = $sql;
            foreach($parms as $parm)
            {
                $sqlWParms = str_replace($parm->name,str_replace("'","''",$this->conn->real_escape_string($parm->value)),$sqlWParms);
            }
            return $sqlWParms;
        }
        function NonQueryParm($sql,$parms){
            $sqlWParms=$this->parmReplace($sql,$parms);
            $this->nonQuery($sqlWParms);
            if($this->conn->error!=null){
                echo $sql;
                echo $sqlWParms;
                echo $this->conn->error;
            }
        }
        //this needs to be refactored
        function getTableParm($sql,$parms){
            $sqlWParms = $this->parmReplace($sql,$parms);
            $this->getTable($sqlWParms);
        }
        function getTable($sql){
            $sql = $this->conn->real_escape_string($sql);
            $result = $this->conn->query($sql);
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
            foreach($this->tables as $table)
            {
                if($this->getTableColumns($table)==null){
                //echo 'Table error_log is not installed<br/>';
                    $installed=false;
                    array_push($this->missingTables,$table);
                }
            }
            return $installed;
        }
        function InstallService(){
            echo 'Install Service called</br>';
            $success = true;//All table installs successful.
            if($this->missingTables==null)$this->IsServiceInstalled();//If install service is called before list of missing tables is built.
            foreach($this->missingTables as $table)
            {
                if($this->getTableColumns($table)==null){
                    $sql = $this->GetCreateTableSQL($table);
                    $result=$this->conn->query($sql);
                    if($this->conn->error!=null){
                         echo 'Table:' . $table . ' ' . $this->conn->error . '<br/>';
                         $success= false;
                    }
                }
            } 
            return $success;
        }
        function GetCreateTableSQL($TableName)
        {
            $sql="";
            switch($TableName)
            {
                case "error_log":
                $sql = <<<SQL
                    CREATE TABLE `pieservice`.`error_log` ( `id` INT NOT NULL AUTO_INCREMENT, `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `error_message` TEXT NOT NULL , `json` JSON NULL , PRIMARY KEY (`id`) USING BTREE) ENGINE = InnoDB; 
SQL;
                break;
                case "request":
                    $sql = <<<SQL
                    create table `pieservice`.`request` ( `id` int not null AUTO_INCREMENT, `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `input` JSON NULL, `voiceQuery` text,`response` JSON NULL, PRIMARY KEY (`id`) USING BTREE) ENGINE = InnoDB;
SQL;
                break;
                case "light":
                    $sql = <<<SQL
                    create table `pieservice`.`light` (`id` int not null AUTO_INCREMENT, `name` text, `ip` text);
SQL;
                break;
            }
            return $sql;
        }
    }//class db
    ///
    ///Deprecated and unecessary
    //This is now stock mysqli
    ///
    class dbParameter{
        public $name="";
        public $value="";
        function __construct($nm,$vl){
            $this->name=$nm;
            $this->value=$vl;
        }
    }
?>