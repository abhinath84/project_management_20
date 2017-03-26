<?php
    /* include header file */
    require_once ('variables.inc.php');
    require_once ('navigator.php');
    require_once ('htmltable.php');
    require_once ('cipher.inc.php');
    require_once ('mysqldb.php');


    class Utility
    {
        private static $EOF_LINE = "\n";
        private static $cipher; //= new Cipher($key);

        public static function getArticleTitle($titleName)
        {
            $tag = '           <div class="titlebar">' . self::$EOF_LINE;
            $tag .= '                   <h1>' . $titleName . '</h1>' . self::$EOF_LINE;
            $tag .= '           </div>' . self::$EOF_LINE;

            return($tag);
        }

        public static function encode($val)
        {
            global $key;
            $cipher = new Cipher($key);

            if($val === "")
                return("");
            else
                return($cipher->encrypt($val));
        }

        public static function decode($val)
        {
            global $key;
            $cipher = new Cipher($key);

            if($val === "")
                return("");
            else
                return($cipher->decrypt($val));
        }

        public static function getRetroButton($title, $id, $class, $event, $prop = '')
        {
            $tag = '';

            if(($title != "") && ($event != ""))
            {
                $tag .= '<button '.(($id != '') ? 'id="'.$id.'"' : '').' class="retro-style '. $class .'" type="button" '. $event . $prop. '>' . self::$EOF_LINE;
                $tag .= '   <span>'. $title .'</span>' . self::$EOF_LINE;
                $tag .= '</button>' . self::$EOF_LINE;
            }

            return($tag);
        }

        public static function getRetroSelect($selectId, $selectOptions, $selectedItem, $selectEvent, $selectClass, $containerClass)
        {
            $tag ='';

            if(
                (($selectId != null) && ($selectId != '')) &&
                (($selectOptions != null) && (count($selectOptions) > 0)) &&
                (($selectedItem != null) && ($selectedItem != ''))
              )
            {
                $tag .= '<div class="retro-style-select-container '. $containerClass .'">' . self::$EOF_LINE;
                $tag .= '   <select id="'. $selectId .'" class="retro-style '. $selectClass .'" '. $selectEvent .'>' . self::$EOF_LINE;

                foreach($selectOptions as $item)
                {
                    $tag .= '   <option value="'. $item[0] .'" '. (($item[0] == $selectedItem) ? 'selected' : '') .'>'. $item[0] .'</option>' . self::$EOF_LINE;
                }

                $tag .= '   </select>' . self::$EOF_LINE;
                $tag .= '</div>' . self::$EOF_LINE;
            }

            return($tag);
        }

        public static function getQuickActionBtn($id, $val, $class, $event, $key, $dropdownId)
        {
            $tag = '<div id="'. $id .'" class="quick-action-btn '. $class .'">' . EOF_LINE;
            $tag .= '    <span id="quick-action-btn-key-span" style="display: none;">'. $key .'</span>' . EOF_LINE;
            $tag .= '    <a class="quick-action-text" '. $event .'>' . $val .'</a>' . EOF_LINE;
            $tag .= '    <a id="quick-action-arrow" class="quick-action-arrow" onclick="showHideEditMenu(\'show\', \''. $id .'\', \''. $dropdownId .'\')" onblur="showHideEditMenu(\'hide\', \''. $id .'\', \''. $dropdownId .'\')">' . EOF_LINE;
            $tag .= '        <span>' . EOF_LINE;
            $tag .= '            <svg width="10px" height="10px" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 451.847 451.847;" xml:space="preserve">' . EOF_LINE;
            $tag .= '                <g>' . EOF_LINE;
            $tag .= '                    <path d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/>' . EOF_LINE;
            $tag .= '                </g>' . EOF_LINE;
            $tag .= '            </svg>' . EOF_LINE;
            $tag .= '        </span>' . EOF_LINE;
            $tag .= '    </a>' . EOF_LINE;
            $tag .= '</div>' . EOF_LINE;

            return($tag);
        }

        public static function getQuickActionBtnDropdown($dropdownId, $lists)
        {
            $tag = '';

            if((($dropdownId != null) && ($dropdownId != "")) &&
                (($lists != null) && (count($lists) > 0)))
            {
                $tag .= '<div id="'. $dropdownId .'" class="dropdown-content">' . self::$EOF_LINE;
                $tag .= '   <span id="quick-action-btn-key-span" style="display: none;"></span>' . self::$EOF_LINE;

                foreach($lists as $child)
                    $tag .= '   <a '. $child[1] .'>'. $child[0] .'</a>' . self::$EOF_LINE;

                $tag .= '</div>' . self::$EOF_LINE;
            }

            return($tag);
        }

        public static function getWidgetBox($title, $widgetboxId, $widgetboxClass, $widgetboxAttribute, $contentClass, $content)
        {
            $tag = '';

            if(
                (($title != null) && ($title != "")) &&
                (($widgetboxId != null) && ($widgetboxId != "")) &&
                (($content != null) && ($content != ""))
              )
            {
                $tag .= '<div id="'. $widgetboxId .'" class="widgetbox '. $widgetboxClass .'">' . self::$EOF_LINE;
                $tag .=     self::getArticleTitle($title);
                $tag .= '   <div class="content '. $contentClass .'">' . self::$EOF_LINE;
                $tag .=         $content;
                $tag .= '   </div>' . self::$EOF_LINE;
                $tag .= '</div>' . self::$EOF_LINE;
            }

            return($tag);
        }

        // Check passing user have admin privilage or not.
        static function isAdmin($user)
        {
            $status = false;

            // check privillage of the member.
            $qry = "SELECT member_id FROM scrum_member WHERE (member_id = $user) AND (privilage='System Admin' OR privilage='Project Admin' OR privilage='Member Admin')";

            $mysqlDBObj = new mysqlDB('scrum_member');
            $rows = $mysqlDBObj->select($qry);
            if(!empty($rows))
            {
                // if not having admin provilage then, check project_member table.
                $qry = "SELECT `project_title` FROM scrum_project_member WHERE (member_id = $user) AND (privilage='System Admin' OR privilage='Project Admin' OR privilage='Member Admin')";

                $mysqlDBObj = new mysqlDB('scrum_project_member');
                $rows = $mysqlDBObj->select($qry);
                if(!empty($rows))
                    $status = true;
            }
            else
                $status = true;

            return($status);
        }

        // Check passing user have System Admin privilage or not.
        static function isSystemAdmin($user)
        {
            $status = false;

            // check privillage of the member.
            $qry = "SELECT member_id FROM scrum_member WHERE (member_id = $user) AND (privilage = 'System Admin')";

            $mysqlDBObj = new mysqlDB('scrum_member');
            $rows = $mysqlDBObj->select($qry);
            if(!empty($rows))
            {
                // if not having admin provilage then, check project_member table.
                $qry = "SELECT `project_title` FROM scrum_project_member WHERE (member_id = $user) AND (privilage = 'System Admin')";

                $mysqlDBObj = new mysqlDB('scrum_project_member');
                $rows = $mysqlDBObj->select($qry);
                if(!empty($rows))
                    $status = true;
            }
            else
                $status = true;

            return($status);
        }

        // Check passing user have Project Admin privilage or not.
        static function isProjectAdmin($user)
        {
            $status = false;

            // check privillage of the member.
            $qry = "SELECT member_id FROM scrum_member WHERE (member_id = $user) AND (privilage = 'Project Admin')";

            $mysqlDBObj = new mysqlDB('scrum_member');
            $rows = $mysqlDBObj->select($qry);
            if(!empty($rows))
            {
                // if not having admin provilage then, check project_member table.
                $qry = "SELECT `project_title` FROM scrum_project_member WHERE (member_id = $user) AND (privilage = 'Project Admin')";

                $mysqlDBObj = new mysqlDB('scrum_project_member');
                $rows = $mysqlDBObj->select($qry);
                if(!empty($rows))
                    $status = true;
            }
            else
                $status = true;

            return($status);
        }

        // Check passing user have Member Admin privilage or not.
        static function isMemberAdmin($user)
        {
            $status = false;

            // check privillage of the member.
            $qry = "SELECT member_id FROM scrum_member WHERE (member_id = $user) AND (privilage = 'Member Admin')";

            $mysqlDBObj = new mysqlDB('scrum_member');
            $rows = $mysqlDBObj->select($qry);
            if(!empty($rows))
            {
                // if not having admin provilage then, check project_member table.
                $qry = "SELECT `project_title` FROM scrum_project_member WHERE (member_id = $user) AND (privilage = 'Member Admin')";

                $mysqlDBObj = new mysqlDB('scrum_project_member');
                $rows = $mysqlDBObj->select($qry);
                if(!empty($rows))
                    $status = true;
            }
            else
                $status = true;

            return($status);
        }
    }
?>
