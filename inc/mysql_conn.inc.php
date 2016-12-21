<?php
    /**
    * @file      mysql_conn.inc.php
    * @author    Abhishek Nath
    * @date      01-Jan-2015
    * @version   1.0
    *
    * @section DESCRIPTION
    * mysql_conn class connect with mysql server and execute all kind of queries.
    *
    * @section LICENSE
    *
    * This program is free software; you can redistribute it and/or
    * modify it under the terms of the GNU General Public License as
    * published by the Free Software Foundation; either version 2 of
    * the License, or (at your option) any later version.
    *
    * This program is distributed in the hope that it will be useful, but
    * WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
    * General Public License for more details at
    * http://www.gnu.org/copyleft/gpl.html
    *
    *
    *** Basic Coding Standard :
    *** https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
    *** http://www.php-fig.org/psr/psr-2/
    *
    */

    /*--
        01-Jan-15   V1-01-00   abhishek   $$1   Created.
        17-Jul-15   V1-01-00   abhishek   $$2   File header comment added.
    --*/

    /**
     * @abstract    mysql_conn
     * @author      Abhishek Nath <abhi.ece.sit@gmail.com>
     * @version     1.0
     *
     * @section LICENSE
     *
     * This program is free software; you can redistribute it and/or
     * modify it under the terms of the GNU General Public License as
     * published by the Free Software Foundation; either version 2 of
     * the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but
     * WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
     * General Public License for more details at
     * http://www.gnu.org/copyleft/gpl.html
     *
     * @section DESCRIPTION
     *
     * mysql_conn class connect with mysql server and execute all kind of queries.
     *
     *
     *** Basic Coding Standard :
     *** https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
     *** http://www.php-fig.org/psr/psr-2/
     *
     */

    class mysql_conn
    {
        private $server;
        private $username;
        private $password;
        private $new_link;
        private $client_flags;
        private $link;
        private $db_name;
        private $db;

        public function __construct($server, $username, $password, $db_name=null, $new_link=FALSE, $client_flags=0)
        {
            $this->server = $server;
            $this->username = $username;
            $this->password = $password;
            $this->new_link = $new_link;
            $this->client_flags = $client_flags;
            $this->db_name = $db_name;
            $this->db = null;

            $this->link = mysqli_connect($this->server, $this->username, $this->password, $this->new_link, $this->client_flags);
            if(!$this->link)
                die('Connection failed: ' . mysqli_error());
        }

        public function __destruct()
        {
            mysqli_close($this->link);
        }

        public function connect()
        {
            $this->link = mysqli_connect($this->server, $this->username, $this->password, $this->new_link, $this->client_flags);
            if(!$this->link)
                die('Connection failed: ' . mysqli_error());
        }

        public function close_connection()
        {
            mysqli_close($this->link);
        }

        public function isConnected()
        {
            if(isset($this->link))
                return true;
            else
                return false;
        }

        private function select_db()
        {
            if(!$this->db && $this->db_name)
            {
                $this->db = mysqli_select_db($this->link, $this->db_name);
                if(!$this->db)
                    die('Connection failed: ' . mysqli_error());
            }
        }

        public function set_db($db_name)
        {
            $this->db_name = $db_name;
            if($this->isDBSelected())
            {
                $this->db = null;
                $this->select_db();
            }
        }

        public function create_db($db_name)
        {
            $qry = 'CREATE DATABASE '.$db_name;
            if (mysqli_query($this->link,$qry))
                return true;
            else
                return false;
        }

        public function isDBSelected()
        {
            if(isset($this->db))
                return true;
            else
                return false;
        }

        public function execute_query($qry)
        {
            $this->select_db();

            if(mysqli_query($this->link, $qry))
                return true;
            else
            {
                throw new Exception('Invalid query: ' . mysqli_error($this->link));
                return false;
            }
        }

        public function result_fetch_array($qry)
        {
            return ($this->fatch_result_accrd_type($qry, 'mysqli_fetch_array'));
        }

        public function result_fetch_row($qry)
        {
            return ($this->fatch_result_accrd_type($qry, 'mysqli_fetch_row'));
        }

        private function fatch_result_accrd_type($qry, $funcs)
        {
            $result_fatch = array();
            $inx = 0;

            $this->select_db();

            // Create and execute a MySQL query
            $result = mysqli_query($this->link, $qry);
            If($result)
            {
                while($row = $funcs($result))
                {
                    $result_fatch[$inx] = $row;
                    $inx++;
                }
                mysqli_free_result($result);
            }

            return $result_fatch;
        }
    }
?>
