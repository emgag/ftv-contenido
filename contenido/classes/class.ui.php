<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * CONTENIDO UI Classes
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend Classes
 * @version    1.5.3
 * @author     Timo A. Hummel
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 *
 * {@internal
 *  created 2003-05-20
 *   $Id$:
 * }}
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

/**
 * @deprecated This class shouldn't be used anymore. Please use templates instead.
 */
class UI_Left_Top
{
    var $link;
    var $javascripts;

    function UI_Left_Top ()
    {
        cDeprecated("This class shouldn't be used anymore. Please use templates instead.");
    }

    function setLink ($link)
    {
        $this->link = $link;
    }

    function setJS ($type, $script)
    {
        $this->javascripts[$type] = $script;
    }

    function render()
    {
        global $sess, $cfg;

        $tpl = new Template;

        $tpl->reset();
        $tpl->set('s', 'SESSID', $sess->id);

        $scripts = "";

        if (is_array($this->javascripts))
        {
            foreach ($this->javascripts as $script)
            {
                $scripts .= '<script language="javascript">'.$script.'</script>';
            }
        }

        if (is_object($this->link))
        {
            $tpl->set('s', 'LINK', $this->link->render() . $this->additional);
        } else {
            $tpl->set('s', 'LINK', '');
        }

        $tpl->set('s', 'JAVASCRIPTS', $scripts);
        $tpl->set('s', 'CAPTION', $this->caption);
        $tpl->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_left_top']);


    }

    function setAdditionalContent ($content)
    {
        $this->additional = $content;
    }

}

class UI_Form
{
    var $items;
    var $content;
    var $id;
    var $rownames;

    var $formname;
    var $formmethod;
    var $formaction;
    var $formvars;
    var $formtarget;
    var $formevent;

    var $tableid;
    var $tablebordercolor;

    var $header;

    function UI_Form ($name, $action = "", $method = "post", $target = "")
    {
        global $sess, $cfg;

        $this->formname = $name;

        if ($action == "")
        {
            $this->formaction = "main.php";
        } else {
            $this->formaction = $action;
        }

        $this->formmethod = $method;

        $this->formtarget = $target;

    }

    function setVar ($name, $value)
    {
        $this->formvars[$name] = $value;
    }

    function setEvent ($event, $jsCall)
    {
        $this->formevent = " on$event=\"$jsCall\"";
    }

    function add ($field, $content = "")
    {
        $this->id++;
        $this->items[$this->id] = $field;
        $this->content[$this->id] = $content;
    }

    function render ($return = true)
    {
        global $sess, $cfg;

        $content = "";

        $tpl = new Template;

        $form  = '<form style="margin:0px" name="'.$this->formname.'" method="'.$this->formmethod.'" action="'.$this->formaction.'" target="'.$this->formtarget.'" '.$this->formevent.'>'."\n";
        $this->formvars[$sess->name] = $sess->id;

        if (is_array($this->formvars))
        {
            foreach ($this->formvars as $key => $value)
            {
                 $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'."\n";
            }
        }

        $tpl->set('s', 'FORM', $form);

        if (is_array($this->items))
        {
            foreach ($this->items as $key => $value)
            {
                $content .= $this->content[$key];
            }
        }

        $tpl->set('s', 'CONTENT', $content);

        $rendered = $tpl->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_form'],true);

        if ($return == true)
        {
            return ($rendered);
        } else {
            echo $rendered;
        }
    }
}

/**
 *
 * @deprecated This class was replaced by cGuiPage. Please use it instead.
 */
class UI_Page
{
    var $scripts;
    var $content;
    var $margin;

    function UI_Page ()
    {
        cDeprecated("This class was replaced by cGuiPage. Please use that instead.");
        $this->margin = 10;
    }

    function setMargin ($margin)
    {
        $this->margin = $margin;
    }

    function addScript ($name, $script)
    {
        $this->scripts[$name] = $script;
    }

    function setReload ()
    {
        $this->scripts["__reload"] =
            '<script type="text/javascript">'.
            "parent.parent.frames['left'].frames['left_bottom'].location.reload();"
            ."</script>";
    }

    function setContent ($content)
    {
        $this->content = $content;
    }

    function setMessageBox ()
    {
        global $sess;
        $this->scripts["__msgbox"] =
           '<script type="text/javascript" src="scripts/messageBox.js.php?contenido='.$sess->id.'"></script>'.
           '<script type="text/javascript">
            /* Session-ID */
            var sid = "'.$sess->id.'";

            /* Create messageBox
               instance */
            box = new messageBox("", "", "", 0, 0);

           </script>';
    }

    function render ($print = true)
    {
        global $sess, $cfg;

        $tpl = new Template;

        $scripts = "";


        if (is_array($this->scripts))
        {
            foreach ($this->scripts as $key => $value)
            {
                $scripts .= $value;
            }
        }

        $tpl->set('s', 'SCRIPTS', $scripts);
        $tpl->set('s', 'CONTENT', $this->content);
        $tpl->set('s', 'MARGIN', $this->margin);
        $tpl->set('s', 'EXTRA', '');

        $rendered = $tpl->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_page'],false);

        if ($print == true)
        {
            echo $rendered;
        } else {
            return $rendered;
        }
    }
}

class Link
{
    var $link;
    var $title;
    var $targetarea;
    var $targetframe;
    var $targetaction;
    var $targetarea2;
    var $targetframe2;
    var $targetaction2;
    var $caption;
    var $javascripts;
    var $type;
    var $custom;
    var $content;
    var $attributes;
    var $img_width;
    var $img_height;
    var $img_type;
    var $img_attr;

    function setLink ($link)
    {
        $this->link = $link;
        $this->type = "link";
    }

    function setCLink ($targetarea, $targetframe, $targetaction)
    {
        $this->targetarea = $targetarea;
        $this->targetframe = $targetframe;
        $this->targetaction = $targetaction;
        $this->type = "clink";
    }

    function setMultiLink ($righttoparea, $righttopaction, $rightbottomarea, $rightbottomaction)
    {
        $this->targetarea = $righttoparea;
        $this->targetframe = 3;
        $this->targetaction = $righttopaction;
        $this->targetarea2 = $rightbottomarea;
        $this->targetframe2 = 4;
        $this->targetaction2 = $rightbottomaction;
        $this->type = "multilink";
    }

    function setAlt ($alt)
    {
        $this->alt = $alt;
    }

    function setCustom ($key, $value)
    {
        $this->custom[$key] = $value;
    }

    function setImage ($image)
    {
        $this->images = $image;
    }

    function setJavascript ($js)
    {
        $this->javascripts = $js;
    }

    function setContent ($content)
    {
        $this->content = $content;
    }

    function updateAttributes ($attributes)
    {
        $this->attributes = $attributes;
    }

    function render ()
    {
            global $sess, $cfg;

            if ($this->alt != "")
            {
                $alt = 'alt="'.$this->alt.'" title="'.$this->alt.'" ';
            } else {
                $alt = " ";
            }

            if (is_array($this->custom))
            {
                foreach ($this->custom as $key => $value)
                {
                    $custom .= "&$key=$value";
                }
            }

            if (is_array($this->attributes))
            {
                foreach ($this->attributes as $key => $value)
                {
                    $attributes .= " $key=\"$value\" ";
                }
            }

            switch ($this->targetframe)
            {
                case 1: $target = "left_top"; break;
                case 2: $target = "left_bottom"; break;
                case 3: $target = "right_top"; break;
                case 4: $target = "right_bottom"; break;
                default: $target = "";
            }

            switch ($this->type)
            {
                case "link":
                    $link =  '<a target="'.$target.'"'.$alt.'href="'.$this->link.'"'.$attributes.'>';
                    break;
                case "clink":

                    $link = '<a target="'.$target.'"'.$alt.'href="main.php?area='.$this->targetarea.
                                           '&frame='.$this->targetframe.
                                           '&action='.$this->targetaction.$custom."&contenido=".$sess->id.
                                           '"'.$attributes.'>';
                    break;
                case "multilink":
                    $tmp_mstr = '<a '.$alt.'href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')"'.$attributes.'>';
                    $mstr = sprintf($tmp_mstr, 'right_top',
                                       $sess->url("main.php?area=".$this->targetarea."&frame=".$this->targetframe."&action=".$this->targetaction.$custom),
                                       'right_bottom',
                                       $sess->url("main.php?area=".$this->targetarea2."&frame=".$this->targetframe2."&action=".$this->targetaction2.$custom));
                    $link = $mstr;
                    break;
            }

            if ($this->images=='') {
                return ($link.$this->content."</a>");
            } else {
                list($this->img_width,$this->img_height,$this->img_type,$this->img_attr) = getimagesize($cfg['path']['contenido'].$this->images);

                return ($link.'<img src="'.$this->images.'" border="0" width="'.$this->img_width.'" height="'.$this->img_height.'"/></a>');
            }
    }
}

class UI_List
{
    var $link;
    var $title;
    var $caption;
    var $javascripts;
    var $type;
    var $image;
    var $alt;
    var $actions;
    var $padding;
    var $imagewidth;
     var $extra;
     var $border;
     var $bgcolor;
     var $solid;
     var $width;

    function UI_List ()
    {
        $this->padding = 2;
        $this->border = 0;
    }

    function setWidth ($width)
    {
        $this->width = $width;
    }

    function setCellAlignment ($item, $cell, $alignment)
    {
        $this->cellalignment[$item][$cell] = $alignment;
    }

    function setCellVAlignment ($item, $cell, $alignment)
    {
        $this->cellvalignment[$item][$cell] = $alignment;
    }

    function setBgColor ($item, $color)
    {
        $this->bgcolor[$item] = $color;
    }

    function setCell ($item, $cell, $value)
    {
        $this->cells[$item][$cell] = $value;
        $this->cellalignment[$item][$cell] = "";
    }

    function setCellExtra ($item, $cell, $extra)
    {
        $this->extra[$item][$cell] = $extra;
    }

    function setPadding ($padding)
    {
        $this->padding = $padding;
    }

    function setBorder ($border)
    {
        $this->border = $border;
    }

    function setExtra ($item, $extra)
    {
        $this->extra[$item] = $extra;
    }

    function setSolidBorder ($solid)
    {
        $this->solid = $solid;
    }

    function render($print = false)
    {
        global $sess, $cfg;

        $tpl = new Template;
        $tpl2 = new Template;

        $tpl->reset();
        $tpl->set('s', 'SID', $sess->id);

        $colcount = 0;

        if (is_array($this->cells))
        {
            foreach ($this->cells as $row => $cells)
            {
                $thefont='';
                $unne='';

                $colcount++;

                $content = "";
                $count = 0;

                foreach ($cells as $key => $value)
                {
                    $count++;
                    $tpl2->reset();

                    if ($this->cellalignment[$row][$key] != "")
                    {
                        $tpl2->set('s', 'ALIGN', $this->cellalignment[$row][$key]);
                    } else {
                        $tpl2->set('s', 'ALIGN', 'left');
                    }

                    if ($this->cellvalignment[$row][$key] != "")
                    {
                        $tpl2->set('s', 'VALIGN', $this->cellvalignment[$row][$key]);
                    } else {
                        $tpl2->set('s', 'VALIGN', 'top');
                    }

                    $tpl2->set('s', 'CONTENT', $value);
                    if($colcount == 1) {
                        $content .= $tpl2->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_list_head'],true);
                    } else {
                        $content .= $tpl2->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_list_row'],true);
                    }
                }

                $tpl->set('d', 'ROWS', $content);
                $tpl->next();
            }
        }

        $rendered = $tpl->generate($cfg['path']['contenido'] . $cfg['path']['templates'] . $cfg['templates']['generic_list'],true);

        if ($print == true)
        {
            echo $rendered;
        } else {
            return $rendered;
        }
    }
}

/**
 * Class ScrollableList
 * Class for scrollable backend lists
 */
class cScrollList
{
    /**
     * Data container
     * @var array
     */
    var $data = Array();

    /**
     * Header container
     * @var array
     */
    var $header = Array();

    /**
     * Number of records displayed per page
     * @var string
     */
    var $resultsPerPage;

    /**
     * Start page
     * @var string
     */
    var $listStart;

    /**
     * sortable flag
     * @var string
     */
    var $sortable;

    /**
     * sortlink
     * @var string
     */
    var $sortlink;

    /**
     * Table item
     *
     */
    var $objTable;

    /**
     * Header row
     *
     */
    var $objHeaderRow;

    /**
     * Header item
     *
     */
    var $objHeaderItem;

    /**
     * Header item
     *
     */
    var $objRow;

    /**
     * Header item
     *
     */
    var $objItem;

    /* TODO: Shouldn't $area and $frame be parameters instead of global variables? */
    /**
     * Creates a new FrontendList object.
      *
     * @param $defaultstyle boolean use the default style for object initializing?
     */
    function cScrollList ($defaultstyle = true, $action = "")
    {
        global $cfg, $area, $frame;

        $this->resultsPerPage = 0;
        $this->listStart = 1;
        $this->sortable = false;

        $this->objTable = new cHTMLTable;
        if ($defaultstyle == true)
        {
            $this->objTable->setClass("generic");
            $this->objTable->updateAttributes(array("cellpadding" => "2"));
        }

        $this->objHeaderRow = new cHTMLTableRow;
        if ($defaultstyle == true)
        {

        }


        $this->objHeaderItem = new cHTMLTableHead;
        if ($defaultstyle == true)
        {

        }

        $this->objRow = new cHTMLTableRow;
        if ($defaultstyle == true)
        {

        }

        $this->objItem = new cHTMLTableData;
        if ($defaultstyle == true)
        {

        }


        $this->sortlink = new cHTMLLink;
        $this->sortlink->setStyle("color: #666666;");
        $this->sortlink->setCLink($area, $frame, $action);
    }

    /**
     * Sets the sortable flag for a specific row.
      *
     * $obj->setSortable(true);
     *
     * @param $sortable boolean true or false
     */
    function setSortable ($key, $sortable)
    {
        $this->sortable[$key] = $sortable;
    }

    /**
     * Sets the custom parameters for sortable links
      *
     * $obj->setCustom($key, $custom);
     *
     * @param $key Custom entry key
     * @param $custom Custom entry value
     */
    function setCustom ($key, $custom)
    {
        $this->sortlink->setCustom($key, $custom);
    }

    /**
     * Is called when a new row is rendered
      *
     * @param $row The current row which is being rendered
     */
    function onRenderRow ($row)
    {
        $this->objRow->setStyle("white-space:nowrap;");
    }

    /**
     * Is called when a new column is rendered
      *
     * @param $row The current column which is being rendered
     */
    function onRenderColumn ($column)
    {
    }

    /**
     * Sets header data.
      *
     * Note: This function eats as many parameters as you specify.
     *
     * Example:
     * $obj->setHeader("foo", "bar");
     *
     * Make sure that the amount of parameters stays the same for all
     * setData calls in a single object.
     *
     * @param $index    Numeric index
     * @param ...    Additional parameters (data)
     */
    function setHeader ()
    {
        $numargs = func_num_args();

        for ($i=0;$i<$numargs;$i++)
        {
            $this->header[$i] = func_get_arg($i);
        }
    }

    /**
     * Sets data.
      *
     * Note: This function eats as many parameters as you specify.
     *
     * Example:
     * $obj->setData(0, "foo", "bar");
     *
     * Make sure that the amount of parameters stays the same for all
     * setData calls in a single object. Also make sure that your index
     * starts from 0 and ends with the actual number - 1.
     *
     * @param $index    Numeric index
     * @param ...    Additional parameters (data)
     */
    function setData ($index)
    {
        $numargs = func_num_args();

        for ($i=1;$i<$numargs;$i++)
        {
            $this->data[$index][$i] = func_get_arg($i);
        }
    }

    /**
     * Sets hidden data.
      *
     * Note: This function eats as many parameters as you specify.
     *
     * Example:
     * $obj->setHiddenData(0, "foo", "bar");
     *
     * Make sure that the amount of parameters stays the same for all
     * setData calls in a single object. Also make sure that your index
     * starts from 0 and ends with the actual number - 1.
     *
     * @param $index    Numeric index
     * @param ...    Additional parameters (data)
     */
    function setHiddenData ($index)
    {
        $numargs = func_num_args();

        for ($i=1;$i<$numargs;$i++)
        {
            $this->data[$index]["hiddendata"][$i] = func_get_arg($i);
        }
    }

    /**
     * Sets the number of records per page.
     *
     * @param $numresults    Amount of records per page
     */
    function setResultsPerPage ($numresults)
    {
        $this->resultsPerPage = $numresults;
    }

    /**
     * Sets the starting page number.
     *
     * @param $startpage    Page number on which the list display starts
     */
    function setListStart ($startpage)
    {
        $this->listStart = $startpage;
    }

    /**
     * Returns the current page.
     *
     * @param $none
     * @returns Current page number
     */
    function getCurrentPage ()
    {
        if ($this->resultsPerPage == 0)
        {
            return 1;
        }

        return ($this->listStart);
    }

    /**
     * Returns the amount of pages.
     *
     * @param $none
     * @returns Amount of pages
     */
    function getNumPages ()
    {
        return (ceil(count($this->data) / $this->resultsPerPage));
    }

    /**
     * Sorts the list by a given field and a given order.
     *
     * @param $field    Field index
     * @param $order    Sort order (see php's sort documentation)
     */
    function sort ($field, $order)
    {
        if ($order == "")
        {
            $order = SORT_ASC;
        }

        if ($order == "ASC")
        {
            $order = SORT_ASC;
        }

        if ($order == "DESC")
        {
            $order = SORT_DESC;
        }

        $this->sortkey = $field;
        $this->sortmode = $order;

        $field = $field + 1;
        $this->data = array_csort($this->data, "$field", $order);

    }

    /**
     * Field converting facility.
     * Needs to be overridden in the child class to work properbly.
     *
     * @param $field    Field index
     * @param $value     Field value
     */
    function convert ($field, $value, $hiddendata)
    {
        return $value;
    }

    /**
     * Outputs or optionally returns
     *
     * @param $return    If true, returns the list
     */
    function render ($return = true)
    {
        global $cfg;

        $currentpage = $this->getCurrentPage();

        $itemstart = (($currentpage-1)*$this->resultsPerPage)+1;

        $headeroutput = "";
        $output = "";

        /* Render header */
        foreach ($this->header as $key => $value)
        {
            if (is_array($this->sortable))
            {
                if (array_key_exists($key, $this->sortable) && $this->sortable[$key] == true)
                {
                    $this->sortlink->setContent($value);
                    $this->sortlink->setCustom("sortby", $key);

                    if ($this->sortkey == $key && $this->sortmode == SORT_ASC)
                    {
                        $this->sortlink->setCustom("sortmode", "DESC");
                    } else {
                        $this->sortlink->setCustom("sortmode", "ASC");
                    }

                    $this->objHeaderItem->setContent($this->sortlink->render());
                    $headeroutput .= $this->objHeaderItem->render();
                } else {
                    $this->objHeaderItem->setContent($value);
                    $headeroutput .= $this->objHeaderItem->render();
                }
            } else {
                $this->objHeaderItem->setContent($value);
                $headeroutput .= $this->objHeaderItem->render();
            }
   $this->objHeaderItem->advanceID();
        }

        $this->objHeaderRow->setContent($headeroutput);

        $headeroutput = $this->objHeaderRow->render();

        if ($this->resultsPerPage == 0)
        {
            $itemend = count($this->data) - ($itemstart-1);
        } else {
            $itemend = $currentpage*$this->resultsPerPage;
        }

        if ($itemend > count($this->data))
        {
            $itemend = count($this->data);
        }

        for ($i=$itemstart;$i<$itemend+1;$i++)
        {
            $items = "";

            $this->onRenderRow($i);

            foreach ($this->data[$i-1] as $key => $value)
            {
                $this->onRenderColumn($key);

                if ($key != "hiddendata")
                {
                    $hiddendata = $this->data[$i-1]["hiddendata"];

                    $this->objItem->setContent($this->convert($key, $value, $hiddendata));
                    $items .= $this->objItem->render();
                }
    $this->objItem->advanceID();
            }

            $this->objRow->setContent($items);
            $items = "";

            $output .= $this->objRow->render();
   $this->objRow->advanceID();
        }

        $this->objTable->setContent($headeroutput.$output);

        $output = stripslashes($this->objTable->render());

        if ($return == true)
        {
            return $output;
        } else {
            echo $output;
        }
    }
}
?>