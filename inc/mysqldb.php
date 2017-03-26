<?php
    require_once ('mysql_conn.inc.php');
    require_once ('db.inc.php');
    require_once ('variables.inc.php');
    require_once ('cipher.inc.php');
    require_once ('mysql_functions.inc.php');

    class mysqlDB
    {
        private $conn;
        private $tableName;
        private $data;
        private $clause;

        public function __construct($tableName=null, $data=null, $clause=null)
        {
            // Intialize member variables.
            $this->conn = new mysql_conn(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, DB_NAME);
            $this->tableName = "";
            $this->data = array();
            $this->clause = array();

            // assign value to member variables according to the inputs.
            $this->setTableName($tableName);
            $this->setData($data);
            $this->setClause($clause);
        }

        public function __destruct()
        {
            /*if($this->conn->isConnected())
                $this->conn->close_connection();*/
        }

       // methods
        public function setTableName($tableName)
        {
            if(($tableName != null) && ($tableName != ""))
                $this->tableName = $tableName;
        }

        public function setData($data)
        {
            if(($data != null) && ($data != ""))
                $this->data = $data;
        }

        public function unsetData()
        {
            if(count($this->data) > 0)
                array_splice($this->data, 0);
        }

        public function setClause($clause)
        {
            if(($clause != null) && ($clause != ""))
                $this->clause = $clause;
        }

        public function unsetClause()
        {
            if(count($this->clause) > 0)
                array_splice($this->clause, 0);
        }

        public function appendData($key, $val)
        {
            if((($key != null) && ($key != "")) && ($val != null))
            {
                if($this->data == null)
                    $this->data = array();

                $this->data[$key] = $val;
            }
        }

        public function appendClause($val)
        {
            if(($val != null) && ($val != ""))
            {
                if($this->clause == null)
                    $this->clause = array();

                array_push($this->clause, $val);
            }
        }

        public function insert()
        {
           if(($this->tableName != "") &&
                (($this->data != null) && (count($this->data) > 0)) &&
                ($this->conn->isConnected()))
           {
               $keys = '(';
               $vals = '(';

               $inx = 1;
               $dataCount = count($this->data);
               foreach($this->data as $key => $val)
               {
                    $keys .= $key;
                    $vals .= "'{$val}'";

                    if($inx < $dataCount)
                    {
                        $keys .= ', ';
                        $vals .= ', ';
                    }

                    $inx++;
               }

               $keys .= ')';
               $vals .= ')';

               $qry = 'INSERT INTO ' . $this->tableName . ' ' . $keys . ' VALUES ' . $vals;
               if($this->conn->execute_query($qry))
                   return(true);
               else
                   return(false);
           }
        }

        public function update()
        {
           if(($this->tableName != "") &&
                (($this->data != null) && (count($this->data) > 0)) &&
                (($this->clause != null) && (count($this->clause) > 0)) &&
                ($this->conn->isConnected()))
            {
                $sets = '';
                $clause = '';

                $inx = 1;
                $dataCount = count($this->data);
                foreach($this->data as $key => $val)
                {
                    $sets .= $key . " = '".$val."'";
                    if($inx < $dataCount)
                        $sets .= ', ';

                    $inx++;
                }

                foreach($this->clause as $each)
                    $clause .= $each;

               $qry = "UPDATE ".$this->tableName." SET ". $sets ." WHERE ". $clause;
               echo $qry;

               if($this->conn->execute_query($qry))
                   return(true);
               else
                   return(false);
            }
        }

        public function delete()
        {
            if(($this->tableName != "") &&
                 ($this->conn->isConnected()))
            {
                $clause = '';
                foreach($this->clause as $each)
                    $clause .= " {$each}";

                $qry = "DELETE FROM " . $this->tableName;
                if(!empty($clause))
                    $qry .= " WHERE ". $clause;

                if($this->conn->execute_query($qry))
                    return(true);
                else
                    return(false);
            }
        }

        public function select($qry)
        {
            $rows = array();
            if(($qry != null) && ($qry != ""))
                $rows = $this->conn->result_fetch_array($qry);

            return($rows);
        }
    }
?>
