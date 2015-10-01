<?php
/*
################################################################################
#                              J! Component Creator                            #
################################################################################
# Class Name: JComponentCreator                                                #
# File-Release-Date:  2015/09/04                                               #
#==============================================================================#
# Author: Max Stemplevski                                                      #
# Site:                                                                        #
# Twitter: @stemax                                                             #
# Copyright 2014+ - All Rights Reserved.                                        #
################################################################################
*/

/* Licence
 * #############################################################################
 * | This program is free software; you can redistribute it and/or             |
 * | modify it under the terms of the GNU General var License                  |
 * | as published by the Free Software Foundation; either version 2            |
 * | of the License, or (at your option) any later version.                    |
 * |                                                                           |
 * | This program is distributed in the hope that it will be useful,           |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the              |
 * | GNU General var License for more details.                                 |
 * |                                                                           |
 * +---------------------------------------------------------------------------+
 */

class JComponentCreator
{

    public $sname = null;
    public $name = null;
    public $creationdate = null;
    public $version = null;
    public $descr = null;
    public $author = null;
    public $authoremail = null;
    public $authorurl = null;
    public $copyright = null;
    public $license = null;
    public $compname = null;
    public $zipfiles = array();

    function __construct()
    {
        $this->sname = isset($_POST['sname']) ? $_POST['sname'] : '';
        $this->name = isset($_POST['name']) ? $_POST['name'] : '';
        $this->creationdate = isset($_POST['creationdate']) ? $_POST['creationdate'] : '';
        $this->version = isset($_POST['version']) ? $_POST['version'] : '';
        $this->descr = isset($_POST['descr']) ? $_POST['descr'] : '';
        $this->author = isset($_POST['author']) ? $_POST['author'] : '';
        $this->authoremail = isset($_POST['authoremail']) ? $_POST['authoremail'] : '';
        $this->authorurl = isset($_POST['authorurl']) ? $_POST['authorurl'] : '';
        $this->copyright = isset($_POST['copyright']) ? $_POST['copyright'] : '';
        $this->license = isset($_POST['license']) ? $_POST['license'] : '';
        $this->compname = trim(str_replace('com_', '', $this->sname));
    }

    function generateAdminMainPhp()
    {
        $php_content = array();
        $php_content [] = "<?php";
        $php_content [] = "defined('_JEXEC') or die;";
        $php_content [] = '     if (!JFactory::getUser()->authorise("core.manage", "' . $this->sname . '")) {';
        $php_content [] = '         return JError::raiseWarning(404, JText::_("JERROR_ALERTNOAUTHOR"));';
        $php_content [] = '     }';
        $php_content [] = 'jimport("joomla.application.component.controller");';
        $php_content [] = '$controller = JControllerLegacy::getInstance("' . $this->compname . '");';
        $php_content [] = '$controller->execute(JRequest::getCmd("task"));';
        $php_content [] = '$controller->redirect();';
        $php_content [] = '';
        $php_str = implode("\r\n", $php_content);
        return $php_str;
    }

    function generateControllerPhp()
    {
        $php_content = array();
        $php_content [] = "<?php";
        $php_content [] = "defined('_JEXEC') or die;";
        $php_content [] = "jimport('joomla.application.component.controller');";
        $php_content [] = 'class ' . $this->compname . 'Controller extends JControllerLegacy';
        $php_content [] = '{';
        $php_content [] = ' public function __construct($config = array())';
        $php_content [] = '{';
        $php_content [] = 'return parent::__construct($config);';
        $php_content [] = '}';
        $php_content [] = 'function display($cachable = false, $urlparams = array())';
        $php_content [] = '{';
        $php_content [] = '  parent::display($cachable,$urlparams);';
        $php_content [] = '}';
        $php_content [] = '}';
        $php_str = implode("\r\n", $php_content);
        return $php_str;
    }

    function generateInstallerScript()
    {
        $php_content = array();
        $php_content [] = "<?php";
        $php_content [] = "defined('_JEXEC') or die;";
        $php_content [] = "class " . $this->sname . "InstallerScript ";
        $php_content [] = '{';
        $php_content [] = ' function install($parent){}';
        $php_content [] = ' function uninstall($parent){}';
        $php_content [] = ' function update($parent){}';
        $php_content [] = ' function preflight($type, $parent) {}';
        $php_content [] = ' function postflight($type, $parent) {}';
        $php_content [] = '}';
        $php_str = implode("\r\n", $php_content);
        return $php_str;
    }

    function generateXml()
    {

        $xml_content = array();
        $xml_content [] = '<?xml version="1.0" encoding="utf-8"?>';
        $xml_content [] = '<extension type="component" version="1.6.0" method="upgrade">';
        $xml_content [] = '<name>' . $this->sname . '</name>';
        $xml_content [] = '<creationDate>' . $this->creationdate . '</creationDate>';
        $xml_content [] = '<author>' . $this->author . '</author>';
        $xml_content [] = '<authorEmail>' . $this->authoremail . '</authorEmail>';
        $xml_content [] = '<authorUrl>' . $this->authorurl . '</authorUrl>';
        $xml_content [] = '<copyright>' . $this->copyright . ' [Generated by SMT JGenerator]</copyright>';
        $xml_content [] = '<license>' . $this->license . '</license>';
        $xml_content [] = '<version>' . $this->version . '</version>';
        $xml_content [] = '<description>' . $this->descr . '</description>';
        $xml_content [] = '<scriptfile>script.php</scriptfile>';

        $xml_content [] = '<files folder="site">';
        $xml_content [] = '<filename>index.html</filename>';
        $xml_content [] = '</files>';

        $xml_content [] = '<administration>';
        $xml_content [] = '<files folder="admin">';

        $xml_content [] = '<filename>index.html</filename>';
        $xml_content [] = '<filename>' . $this->compname . '.php</filename>';
        $xml_content [] = '<filename>controller.php</filename>';
        $xml_content [] = '<folder>controllers</folder>';
        $xml_content [] = '<folder>models</folder>';
        $xml_content [] = '<folder>views</folder>';
        $xml_content [] = '</files>';

        $xml_content [] = '<menu link="option=' . $this->sname . '" >' . $this->sname . '</menu>';

        $xml_content [] = '<languages>';
        $xml_content [] = '<language tag="en-GB">languages/en-GB.' . $this->sname . '.ini</language>';
        $xml_content [] = '<language tag="en-GB">languages/en-GB.' . $this->sname . '.sys.ini</language>';
        $xml_content [] = '</languages>';

        $xml_content [] = '</administration>';

        $xml_content [] = '</extension>';
        $xml_str = implode("\r\n", $xml_content);
        return $xml_str;
    }

    function createFile($filename = '', $content = '')
    {
        $fp = fopen($filename, "w");
        $wresult = fwrite($fp, $content);
        fclose($fp);
        return $filename;
    }

    function addToZip($filename = '')
    {
        $this->zipfiles[] = $filename;
    }

    function createAndSaveZip()
    {
        if (extension_loaded('zip')) {
            $zip = new ZipArchive();
            $zip_name = $this->sname . ".zip";
            if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                return false;
            }
            if (sizeof($this->zipfiles))
                foreach ($this->zipfiles as $zfile) {
                    $zip->addFile($zfile);
                }
            $zip->close();
            if (file_exists($zip_name)) {
                header('Content-type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zip_name . '"');
                readfile($zip_name);
                unlink($zip_name);

                if (sizeof($this->zipfiles))
                    foreach ($this->zipfiles as $zfile) {
                        unlink($zfile);
                    }
            }
        } else
            return false;
    }


    function generateSiteFolders()
    {
        if (!file_exists("site")) {
            mkdir("site");
        }

        $this->addToZip($this->createFile('site/index.html', $this->getEmptyHtml()));
    }

    function getEmptyHtml()
    {
        return '<html><body></body></html>';
    }

    function generateAdminFolders()
    {
        if (!file_exists("admin")) {
            mkdir("admin");
            mkdir("admin/controllers");
            mkdir("admin/models");
            mkdir("admin/models/fields");
            mkdir("admin/models/forms");
            mkdir("admin/views");
            mkdir("admin/views/" . $this->compname);
            mkdir("admin/views/" . $this->compname . "/tmpl");
            mkdir("languages");
        }

        $this->addToZip($this->createFile('admin/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile('admin/controllers/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile('admin/models/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile('admin/models/fields/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile('admin/models/forms/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile('admin/views/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile("admin/views/" . $this->compname . '/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile("admin/views/" . $this->compname . '/tmpl/index.html', $this->getEmptyHtml()));
        $this->addToZip($this->createFile("admin/views/" . $this->compname . '/tmpl/default.php', "<?php defined('_JEXEC') or die;"));
        $this->addToZip($this->createFile('languages/en-GB.' . $this->sname . '.ini', strtoupper($this->sname) . '="' . (ucwords($this->name)) . '"'));
        $this->addToZip($this->createFile('languages/en-GB.' . $this->sname . '.sys.ini', strtoupper($this->sname) . '="' . (ucwords($this->name)) . '"'));

        $php_content = array();
        $php_content [] = "<?php";
        $php_content [] = "defined('_JEXEC') or die;";
        $php_content [] = "jimport('joomla.application.component.view');";
        $php_content [] = 'class ' . $this->compname . 'View' . $this->compname . ' extends JViewLegacy';
        $php_content [] = '{';
        $php_content [] = 'function display($tpl = null) ';
        $php_content [] = '{';
        $php_content [] = '  JToolbarHelper::title(JText::_("' . (strtoupper($this->sname)) . '"), "info");';
        $php_content [] = '  parent::display($tpl);';
        $php_content [] = '}';
        $php_content [] = '}';
        $php_str = implode("\r\n", $php_content);

        $this->addToZip($this->createFile("admin/views/" . $this->compname . '/view.html.php', $php_str));
    }

    function deleteTmpFolders()
    {
        if (file_exists("admin/models/fields")) rmdir("admin/models/fields");
        if (file_exists("admin/models/forms")) rmdir("admin/models/forms");
        if (file_exists("admin/models")) rmdir("admin/models");
        if (file_exists("admin/controllers")) rmdir("admin/controllers");
        if (file_exists("admin/models")) rmdir("admin/models");
        if (file_exists("admin/views/" . $this->compname . '/tmpl')) rmdir("admin/views/" . $this->compname . '/tmpl');
        if (file_exists("admin/views/" . $this->compname)) rmdir("admin/views/" . $this->compname);
        if (file_exists("admin/views")) rmdir("admin/views");
        if (file_exists("languages")) rmdir("languages");
        if (file_exists("admin")) rmdir("admin");
        if (file_exists("site")) rmdir("site");
    }

    function run()
    {
        if (isset($_POST['sname']) && $_POST['name']) {
            $this->addToZip($this->createFile($this->compname . '.xml', $this->generateXml()));
            $this->addToZip($this->createFile('script.php', $this->generateInstallerScript()));
            $this->addToZip($this->createFile('index.html', $this->getEmptyHtml()));
            $this->generateSiteFolders();
            $this->generateAdminFolders();
            $this->addToZip($this->createFile('admin/' . $this->compname . '.php', $this->generateAdminMainPhp()));
            $this->addToZip($this->createFile('admin/controller.php', $this->generateControllerPhp()));

            $this->createAndSaveZip();
            $this->deleteTmpFolders();
        } else {
            $this->showform();
        }
    }

    function showForm()
    {
        ?>
        <html>
        <head>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
            <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
            <style>
                .header_3d {
                    color: #fffffc;
                    text-shadow: 0 1px 0 #999, 0 2px 0 #888, 0 3px 0 #777, 0 4px 0 #666, 0 5px 0 #555, 0 6px 0 #444, 0 7px 0 #333, 0 8px 7px #001135;
                }
            </style>
            <title>J! Component Creator</title>
        </head>
        <body>
        <form method="post" action="index.php" name="subform" class="form"/>
        <div class="jumbotron navbar-form">

            <div class="container">
                <div class="page-header header_3d"><h1>J! Component Creator:</h1></div>
                <table width="50%" class="table table-striped table-hover">
                    <tr>
                        <td>System name of component:</td>
                        <td><input class="form-control required" type="text" value="com_" name="sname" size="45"/></td>
                    </tr>
                    <tr>
                        <td>Title(Name) of component:</td>
                        <td><input class="form-control required" type="text" value="" name="name" size="45"/></td>
                    </tr>
                    <tr>
                        <td>CreationDate:</td>
                        <td><input class="form-control" type="text" value="<?php echo date('F Y'); ?>"
                                   name="creationdate"
                                   size="45"/></td>
                    </tr>
                    <tr>
                        <td>Version:</td>
                        <td><input class="form-control" type="text" value="1.0.0" name="version" size="45"/></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td><textarea class="form-control" name="descr"></textarea></td>
                    </tr>
                    <tr>
                        <td>Author:</td>
                        <td><input class="form-control" type="text" value="" name="author" size="45"/></td>
                    </tr>
                    <tr>
                        <td>AuthorEmail:</td>
                        <td><input class="form-control" type="text" value="" name="authoremail" size="45"/></td>
                    </tr>
                    <tr>
                        <td>AuthorUrl:</td>
                        <td><input class="form-control" type="text" value="" name="authorurl" size="45"/></td>
                    </tr>
                    <tr>
                        <td>Copyright:</td>
                        <td><input class="form-control" type="text"
                                   value="Copyright 2010 - <?php echo date('Y');?>. All rights reserved"
                                   name="copyright"
                                   size="45"/></td>
                    </tr>
                    <tr>
                        <td>License:</td>
                        <td><input class="form-control" type="text" value="GNU" name="license" size="45"/></td>
                    </tr>
                </table>
                <button class="btn btn-primary btn-lg" type="submit">Generate new component</button>
            </div>
        </div>
        </form>
        <div class="btn btn-primary btn-xs pull-right " disabled="true">Created by SMT</div>
        </body>
        </html>
    <?php
    }
}

?>
