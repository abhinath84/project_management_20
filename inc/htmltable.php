<?php
    /**
    * @file      htmltable.php
    * @author    Abhishek Nath
    * @date      05-Oct-2016
    * @version   1.0
    *
    * @section DESCRIPTION
    * Class to create HTML table with passing information.
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
    05-Oct-16   V1-01-00   abhishek   $$1   Created.
--*/


    //const EOF_LINE = "\n";

    /**
     * @abstract    TComponent
     * @author      Abhishek Nath
     * @version     1.0
     *
     * @section DESCRIPTION
     *
     * .
     *
     */
    class TComponent
    {
        private $_id             = null;
        private $_classes         = null;
        private $_style            = null;
        private $_attributes    = null;
        private $_events         = null;

        public function __construct($id = null, $class = null, $style = null,
                                    $attribute = null, $event = null)
        {
            $this->_id             = $id;
            $this->_classes     = $class;
            $this->_style        = $style;
            $this->_attributes    = $attribute;
            $this->_events         = $event;
        }

        private function append($src, $seperator, $val)
        {
            if($val != null)
            {
                if($src == null)
                    $src = $val;
                else
                    $src .="{$seperator}{$val}";
            }
        }

        private function format($attr, $val)
        {
            $str = "";

            if(($val != null) && ($val != ""))
            {
                if(($attr != null) && ($attr != ""))
                    $str .= "{$attr} = \"";

                $str .= "{$val}";

                if(($attr != null) && ($attr != ""))
                    $str .= "\"";
            }

            return ($str);
        }

        private function padding($val)
        {
            return(($val == "") ? "" : " ");
        }

        public function setId($id)                      { $this->_id = $id;                             }
        public function appendClasses($class)           { append($this->_classes, " ", $class);         }
        public function appendStyles($style)            { append($this->_style, "; ", $style);          }
        public function appendAttribute($attribute)     { append($this->_attributes, " ", $attribute);  }
        public function appendEvent($event)             { append($this->_events, " ", $event);          }

        public function getId()             { return $this->_id;            }
        public function getClasses()        { return $this->_classes;       }
        public function getAttributes()     { return $this->_attributes;    }
        public function getEvents()         { return $this->_events;        }

        public function toString()
        {
            $str = "";

            // add ID
            $str .= "{$this->padding($str)}{$this->format('id', $this->_id)}";

            // add Classes
            $str .= "{$this->padding($str)}{$this->format('class', $this->_classes)}";

            // add Styles
            $str .= "{$this->padding($str)}{$this->format('style', $this->_style)}";

            // add Attributes
            $str .= "{$this->padding($str)}{$this->format('', $this->_attributes)}";

            // add Events
            $str .= "{$this->padding($str)}{$this->format('', $this->_events)}";

            return $str;
        }
    }

    class TD
    {
        private $_comp    = null;    // TComponent
        private $_val    = null;

        public function __construct($val = null, $id = null, $class = null, $style = null,
                                    $attribute = null, $event = null)
        {
            $this->_comp    = new TComponent($id, $class, $style, $attribute, $event);
            $this->_val     = $val;
        }

        public function setId($id)                    { $this->_comp->setId($id); }
        public function setValue($val)                { $this->_val = $val; }

        public function setComponent($comp)            { if($comp != null) $this->_comp = $comp; }
        public function appendClass($class)            { $this->_comp->appendClass($class); }
        public function appendAttribute($attribute)    { $this->_comp->appendAttribute($attribute); }
        public function appendEvent($event)            { $this->_comp->appendEvent($event); }

        public function getComponent()  { return $this->_comp; }
        public function Id()            { return $this->_comp->getId(); }
        public function Classes()       { return $this->_comp->getClasses(); }
        public function Attribute()     { return $this->_comp->getAttributes(); }
        public function Events()        { return $this->_comp->getEvents(); }
        public function Value()         { return $this->_val; }

        public function toString()
        {
            $str = "    <td {$this->_comp->toString()}>" . EOF_LINE;
            $str .= "            {$this->_val}" . EOF_LINE;
            $str .= "        </td>" . EOF_LINE;

            return $str;
        }
    }

    class TR
    {
        private $_comp        = null;            // TComponent
        private $_td         = array();        // TD
        private $_node_id     = 0;

        public function __construct($id = null, $class = null, $style = null,
                                    $attribute = null, $event = null)
        {
            $_comp        = new TComponent($id, $class, $style, $attribute, $event);
            $_td         = array();
            $_node_id     = 0;
        }

        public function setComponent($comp)    { if($comp != null) $this->_comp = $comp; }
        public function getComponent()         { return($this->_comp); }

        public function addCell($val=null, $id=null, $class=null, $attribute=null, $event=null)
        {
            $this->_td[$this->_node_id++] = new TD($val, $id, $class, $attribute, $event);
        }

        public function getCell($i) { return ((count($this->_td) > $i) ? $this->_td[$i] : null); }

        public function size()    { return (count($this->_td)); }

        public function toString()
        {
            $str = "";

            if(($this->_td != null) && (count($this->_td) > 0))
            {
                // start tr
                $str .= "<tr>" . EOF_LINE; // need to add  '{$this->_comp->toString()}'

                // add all td
                foreach ($this->_td as &$td)
                {
                    $str .= "    {$td->toString()}" . EOF_LINE;
                }

                // end tr
                $str .= "    </tr>" . EOF_LINE;
            }

            return ($str);
        }
    }

    class Tbody
    {
        private $_comp         = null;
        private $_tr        = array();
        private $_row_id    = 0;

        public function __construct($id = null, $class = null, $style = null, $attribute = null)
        {
            $this->_comp     = new TComponent($id, $class, $style, $attribute);
            $this->_tr        = array();
            $this->_row_id    = 0;
        }

        public function setComponent($comp) { if($comp != null) $this->_comp = $comp; }
        public function getComponent()        { return($this->_comp); }

        public function addRow($id = null, $class = null, $attribute = null, $event = null, $row = null)
        {
            $this->_tr[$this->_row_id] = new TR($id, $class, $attribute, $event);

            if($row != null)
            {
                foreach($row as &$td)
                    $this->_tr[$this->_row_id]->addCell($td);
            }

            $this->_row_id++;
        }

        public function getRow($i) { return ((count($this->_tr) > $i) ? $this->_tr[$i] : null); }

        public function addCell($val=null, $id=null, $class=null, $attribute=null, $event=null)
        {
            $i = $this->_row_id -1 ;
            $this->_tr[$i]->addCell($val, $id, $class, $attribute, $event);
        }

        public function getCell($r, $c) { return((count() > $r) ? $this->_tr[$r]->getCell($c) : null); }

        public function size() { return (count($this->_tr)); }

        public function toString()
        {
            $str = "";

            if(($this->_tr != null) && (count($this->_tr) > 0))
            {
                // start tbody
                $str .= "<tbody {$this->_comp->toString()}>" . EOF_LINE;

                // add all td
                $i = 0;
                foreach ($this->_tr as &$tr)
                {
                    $i++;
                    $str .= "    {$tr->toString()}";
                }

                // end tbody
                $str .= "    </tbody>" . EOF_LINE;
            }

            return($str);
        }
    }

    class Thead
    {
        private $_comp        = null;
        private $_th        = array();
        private $_node_id    = 0;

        public function __construct($id = null, $class = null, $style = null, $attribute = null)
        {
            $this->_comp    = new TComponent($id, $class, $style, $attribute);
            $this->_th      = array();
            $this->_node_id = 0;
        }

        public function th($val, $id = null, $class = null, $style=null, $attribute = null)
        {
            $this->_th[$this->_node_id++] = new TD($val, $id, $class, $style, $attribute);
        }

        public function setComponent($comp)
        {
            if($comp != null)
                $this->_comp = $comp;
        }

        public function getComponent()
        {
            return($this->_comp);
        }

        public function toString()
        {
            $str = "";

            if(($this->_th != null) && (count($this->_th) > 0))
            {
                // start thead
                $str .= "<thead {$this->_comp->toString()}>" . EOF_LINE;
                $str .= "        <tr>" . EOF_LINE;

                // add all td
                foreach ($this->_th as &$th)
                {
                    if(($th != null) && ($th != ""))
                    {
                        $str .= "            <th {$th->getComponent()->toString()}>{$th->Value()}</th>" . EOF_LINE;
                    }
                }

                // end tbody
                $str .= "        </tr>" . EOF_LINE;
                $str .= "    </thead>" . EOF_LINE;
            }

            return($str);
        }
    }

    class HTMLTable
    {
        private $_comp      = null;
        private $_thead     = null;
        private $_tbody     = null;

        public function __construct($id = null, $class = null, $style = null, $attribute = null)
        {
            $this->_comp     = new TComponent($id, $class, $style, $attribute);
            $this->_thead    = null;
            $this->_tbody    = null;
        }

        public function setComponent($comp) { if($comp != null) $this->_comp = $comp; }
        public function getComponent()        { return ($this->_comp); }


        public function thead($id = null, $class = null, $style = null, $attribute = null)
        {
            $this->_thead = new Thead($id, $class, $style, $attribute);
        }

        public function th($val, $id = null, $class = null, $style = null, $attribute = null)
        {
            if($this->_thead != null)
                $this->_thead->th($val, $id, $class, $style, $attribute);
        }

        public function tbody($id = null, $class = null, $style = null, $attribute = null)
        {
            $this->_tbody = new Tbody($id, $class, $style, $attribute);
        }

        public function tr($id = null, $class = null, $style = null, $attribute = null, $event = null)
        {
            if($this->_tbody != null)
                $this->_tbody->addRow($id, $class, $style, $attribute, $event);
        }

        public function td($val = null, $id = null, $class = null, $style = null, $attribute = null, $event = null)
        {
            if($this->_tbody != null)
                $this->_tbody->addCell($val, $id, $class, $style, $attribute, $event);
        }

        public function toHTML()
        {
            $str = "";

            //echo "thead : {$this->_thead}";

            if(($this->_thead != null) && ($this->_tbody != null))
            {
                $str .= EOF_LINE;
                $str .= "<table {$this->_comp->toString()}>" . EOF_LINE;

                $str .= "    {$this->_thead->toString()}";
                $str .= "    {$this->_tbody->toString()}";

                $str .= "</table>" . EOF_LINE;
            }

            return(utf8_encode($str));
        }
    }
?>
