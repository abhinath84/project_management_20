<?php

/* include header file */
require_once ('utility.php');
require_once ('htmltable.php');
require_once ('mysql_functions.inc.php');

class GrippyTable
{
    protected $table    = null;
    protected $inx      = 1;

    /* ctor/dtor */
    public function __construct($name, $class)
    {
        $this->table    = new HTMLTable($name, $class);
        $this->inx     = 1;
    }

    /* public methods */
    public function fillHead($theadName, $thList)
    {
        if(
            (($theadName != null) && ($theadName != '')) &&
            (($thList != null) && (count($thList)) > 0)
          )
        {
            $this->table->thead($theadName);

            foreach($thList as $th)
                $this->table->th($th[0], $th[1], $th[2], $th[3], $th[4]);
        }
    }

    public function fillBody($tbodyName, $qry, $callback)
    {
        global $conn;

        if(($tbodyName != null) && ($tbodyName != ''))
        {
            // add Table body
            $this->table->tbody($tbodyName);

            if(
                (($qry != null) && ($qry != '')) &&
                ($callback != null)
            )
            {
                $rows = $conn->result_fetch_array($qry);
                if(!empty($rows))
                {
                    // loop over the result and fill the rows
                    $this->inx = 1;
                    foreach($rows as $row)
                    {
                        call_user_func($callback, $this->table, $row, $this->inx);
                        //$this->addBodyRow($row);

                        $this->inx++;
                    }
                }
                else
                {
                    $this->table->tr(null, null, null, "align=\"center\"");
                        //$this->table->td("<p>No result !!!</p>", "no-result", null, null, null);
                }
            }
            else
            {
                $this->table->tr(null, null, null, "align=\"center\"");
                    //$this->table->td("<p>No result !!!</p>", "no-result", null, null, null);
            }
        }
    }

    public function toHTML()
    {
        return(utf8_encode($this->table->toHTML()));
    }

    public function getBodyElement()
    {
        return(utf8_encode($this->table->getTBodyElementHTML()));
    }

    /* protected methods */
    //abstract protected function addBodyRow($row);
}

/*class ProjectGrippyTable extends GrippyTable
{
    public function __construct($name = null, $class = null)
    {
        parent::__construct("project-table", "grippy-table");
    }

    protected function addBodyRow($row, $inx)
    {
        if(($row != null) && (count($row) > 0))
        {
            if($row[0] != 'System(All Projects)')
            {
                $this->table->tr(null, null, null, "align=\"center\"");
                    $this->table->td(getGreppyDotTag(), "1-greppy", "hasGrippy", "text-align:center;", "width=\"1%\"");
                    $this->table->td("{$row[0]}", "{$inx}-title", "project-title-td", null, "width=\"30%\"");
                    $this->table->td(Utility::decode($row[1]), "{$inx}-owner", null, null, "width=\"18%\"");
                    $this->table->td("{$row[2]}", "{$inx}-begin_date", null, null, "width=\"10%\"");
                    $this->table->td("{$row[3]}", "{$inx}-end_date", null, null, "width=\"10%\"");
                    $this->table->td("{$row[4]}", "{$inx}-sprint_schedule", null, null, "width=\"25%\"");

                    $this->table->td("{$row[5]}", "{$inx}-parent", null, "display: none;");
                    $this->table->td("{$row[6]}", "{$inx}-description", null, "display: none;");
                    $this->table->td("{$row[7]}", "{$inx}-status", null, "display: none;");
                    $this->table->td("{$row[8]}", "{$inx}-target_estimate", null, "display: none;");
                    $this->table->td("{$row[9]}", "{$inx}-test_suit", null, "display: none;");
                    $this->table->td("{$row[10]}", "{$inx}-target_swag", null, "display: none;");
                    $this->table->td("{$row[11]}", "{$inx}-reference", null, "display: none;");

                    $this->table->td(Utility::getQuickActionBtn("{$inx}", "Edit", "project-td-btn", "onclick=\"shieldProject.openEditDialog('{$inx}', 'project-tbody', false)\"", "{$inx}", "project-table-dropdown"), "project-edit", null, null, "width=\"5%\"");
            }
        }
    }
}*/

?>
