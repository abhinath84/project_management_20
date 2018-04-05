<?php
  // required headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");

  // include database and object files
  require_once ('../../inc/variables.inc.php');
  require_once ('../../inc/db.inc.php');
  require_once ('../../inc/cipher.inc.php');
  require_once ('../../inc/mysqldb.php');

  /**
   * @param string $str
   * @return string
   */
    function sanitizeForJSON($str)
    {
        // Strip all slashes:
        $str = stripslashes($str);

        // Only escape backslashes:
        $str = str_replace('"', '\"', $str);

        return $str;
    }

    /**
    * Alternative to json_encode() to handle big arrays
    * Regular json_encode would return NULL due to memory issues.
    * @param $arr
    * @return string
    */
    function jsonEncode($arr) {
        $str = '{';
        $count = count($arr);
        $current = 0;

        foreach ($arr as $key => $value) {
            $str .= sprintf('"%s":', sanitizeForJSON($key));

            if (is_array($value)) {
                $str .= '[';
                foreach ($value as &$val) {
                    $val = sanitizeForJSON($val);
                }
                $str .= '"' . implode('","', $value) . '"';
                $str .= ']';
            } else {
                $str .= sprintf('"%s"', sanitizeForJSON($value));
            }

            $current ++;
            if ($current < $count) {
                $str .= ',';
            }
        }

        $str.= '}';

        return $str;
    }

  // set ID property of product to be edited
  if(isset($_GET['user'])) {
    // cipher & db conn objects.
    $cipherObj = new Cipher($key);
    $conn = new mysql_conn(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, DB_NAME);

    $userName = $cipherObj->encrypt($_GET['user']);
    $qry = "";

    if( !isset($_GET['qryType']) )
    {
        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session  FROM `spr_tracking` WHERE user_name = '". $userName ."'/* AND session='2018'*/";
    }
    else if($_GET['qryType'] == "rbd")
    {
        /*$date = date('Y-m-d');

        // date after next 1 month
        list($year, $month, $day) = explode('-', $date);
        if($month == '12')
        {
            $year = $year + 1;
            $month = 1;
        }
        else
            $month = $month + 1;

        if($month < 10)
        $month = '0'.$month;

        $next_date = $year . '-' . $month . '-' . $day;*/

        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM `spr_tracking` WHERE user_name =  '". $userName ."' AND (TYPE =  'SPR' OR TYPE =  'INTEGRITY SPR') AND (STATUS <>  'NOT AN ISSUE' AND STATUS <>  'RESOLVED' AND STATUS <> 'CLOSED' AND STATUS <> 'SUBMITTED' AND STATUS <> 'NEED MORE INFO' AND STATUS <> 'PASS TO CORRESPONDING GROUP') ORDER BY respond_by_date DESC";

        // AND respond_by_date BETWEEN  '".$date."' AND  '".$next_date."'
    }
    else if($_GET['qryType'] == "cb")
    {
        $qry = "SELECT spr_no, type, status, build_version, commit_build, respond_by_date, comment, session FROM  spr_tracking WHERE user_name =  '". $userName ."' AND (type = 'SPR' OR type = 'INTEGRITY SPR') AND (status <> 'NOT AN ISSUE' AND status <> 'RESOLVED' AND status <> 'CLOSED' AND status <> 'PASS TO CORRESPONDING GROUP') ORDER BY commit_build DESC";
    }

    //echo json_encode($qry);

    $rows = $conn->result_fetch_array($qry);
    if(!empty($rows)) {

        // SPRs array
        //$sprs=array();

        $prefix = '';
        echo '[';

        foreach($rows as $row) {
            // extract row
            // this will make $row['name'] to just $name only
            extract($row);

            $spr=array(
                "number" => $spr_no,
                "type" => $type,
                "status" => $status,
                "build_version" => $build_version,
                "commit_build" => $commit_build,
                "respond_by_date" => $respond_by_date,
                "comment" => html_entity_decode($comment),
                "session" => $session
            );

            //array_push($sprs, $spr);
            echo $prefix, json_encode($spr);  $prefix = ',';
        }

        echo ']';

        //echo json_encode($sprs);
    } else {
        echo json_encode(
            array("errorMessages" => "No SPR found.")
        );
    }

    //echo json_encode($qry);
  } else {
    echo json_encode(
        array("errorMessages" => "user is not provided.")
    );
  }
?>
