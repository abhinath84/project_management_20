<?php
    /* include header file */
    require_once ('variables.inc.php');
    require_once ('navigator.php');
    require_once ('htmltable.php');
    require_once ('cipher.inc.php');


    class Utility
    {
        private static $EOF_LINE = "\n";
        private static $cipher; //= new Cipher($key);

        /*
            $lists = array(array(<val>, <href>), ...)
        */
        public static function getTabMenu($currentTab, $lists)
        {
            $tag = '<div class="main-article-nav-container display-table-row">' . self::$EOF_LINE;
            $tag .= '    <ul class="float-box-nav main-article-nav">' . self::$EOF_LINE;

            foreach($lists as $li)
            {
                $tag .= '        <li><a ' . (($currentTab === $li[0]) ? 'class="selected-tab"' : '') . 'href="'. $li[1] .'" target="_top">'. $li[0] .'</a></li>' . self::$EOF_LINE;
            }

            $tag .= '    </ul>' . self::$EOF_LINE;
            $tag .= '</div>' . self::$EOF_LINE;

            return($tag);
        }

        public static function getArticleTitle($titleName)
        {
            $tag = '           <div class="main-article-info-header">' . self::$EOF_LINE;
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
    }
?>
