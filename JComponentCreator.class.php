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
    private $pattern_folder = 'Patterns';

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

    private function getPhpFromTemplate($template_name, $placeholders, $replace)
    {
        $template = $this->loadTemplate($template_name);
        return str_replace($placeholders, $replace, $template);
    }

    private function loadTemplate($template_name)
    {
        $path = $this->pattern_folder . DIRECTORY_SEPARATOR . $template_name . '.tmpl';
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return false;
    }

    private function generateAdminMainPhp()
    {
        return $this->getPhpFromTemplate('admin_component', [
            '{SYSTEM_COMPONENT_NAME}',
            '{COMPONENT_NAME}'
        ], [
            $this->sname,
            $this->compname
        ]);
    }

    private function generateAdminControllerPhp()
    {
        return $this->getPhpFromTemplate('admin_controller', ['{COMPONENT_NAME}'], [$this->compname]);
    }

    private function generateAdminView()
    {
        return $this->getPhpFromTemplate('admin_view', ['{COMPONENT_NAME}', '{UPPER_COMPONENT_NAME}'], [$this->compname, strtoupper($this->sname)]);
    }


    private function generateSiteMainPhp()
    {
        return $this->getPhpFromTemplate('site_component', ['{COMPONENT_NAME}'], [$this->compname]);
    }

    private function generateSiteControllerPhp()
    {
        return $this->getPhpFromTemplate('site_controller', ['{COMPONENT_NAME}'], [$this->compname]);
    }

    private function generateSiteViewPhp()
    {
        return $this->getPhpFromTemplate('site_view', ['{COMPONENT_NAME}'], [$this->compname]);
    }

    private function generateInstallerScript()
    {
        return $this->getPhpFromTemplate('install_script', ['{SYSTEM_COMPONENT_NAME}'], [$this->sname]);
    }

    private function generateXml()
    {
        return $this->getPhpFromTemplate('xml', [
            '{SYSTEM_COMPONENT_NAME}',
            '{COMPONENT_NAME}',
            '{CREATION_DATE}',
            '{AUTHOR_NAME}',
            '{AUTHOR_EMAIL}',
            '{AUTHOR_URL}',
            '{COPYRIGHT}',
            '{LICENSE}',
            '{VERSION}',
            '{DESCRIPTION}',
        ], [
            $this->sname,
            $this->compname,
            $this->creationdate,
            $this->author,
            $this->authoremail,
            $this->authorurl,
            $this->copyright,
            $this->license,
            $this->version,
            $this->descr,
        ]);
    }

    private function generateSiteLayout()
    {
        return $this->getPhpFromTemplate('site_layout', ['{COMPONENT_NAME}', '{DESCRIPTION}'], [$this->compname, $this->descr]);
    }

    private function createFile($filename = '', $content = '')
    {
        $fp = fopen($filename, "w");
        fwrite($fp, $content);
        fclose($fp);
        return $filename;
    }

    private function addToZip($filename = '')
    {
        $this->zipfiles[] = $filename;
    }

    private function createAndSaveZip()
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

    private function generateSiteFolders()
    {
        if (!file_exists("site")) {
            mkdir("site");
            mkdir("site/views");
            mkdir("site/views/" . $this->compname);
            mkdir("site/views/" . $this->compname . "/tmpl");
        }
    }

    private function getEmptyHtml()
    {
        return '<html><body></body></html>';
    }

    private function getEmptyPhp()
    {
        return "<?php defined('_JEXEC') or die;";
    }

    private function generateAdminFolders()
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
    }

    private function deleteTmpFolders()
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
        if (file_exists("site/views/" . $this->compname . '/tmpl')) rmdir("site/views/" . $this->compname . '/tmpl');
        if (file_exists("site/views/" . $this->compname)) rmdir("site/views/" . $this->compname);
        if (file_exists("site/views")) rmdir("site/views");
        if (file_exists("site")) rmdir("site");
    }

    public function run()
    {
        if (isset($_POST['sname']) && $_POST['name']) {
            $this->addToZip($this->createFile($this->compname . '.xml', $this->generateXml()));
            $this->addToZip($this->createFile('script.php', $this->generateInstallerScript()));
            $this->addToZip($this->createFile('index.html', $this->getEmptyHtml()));

            $this->generateSiteFolders();
            $this->addToZip($this->createFile('site/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("site/views/" . $this->compname . '/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("site/views/" . $this->compname . '/tmpl/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("site/views/" . $this->compname . '/tmpl/default.php', $this->generateSiteLayout()));
            $this->addToZip($this->createFile('site/' . $this->compname . '.php', $this->generateSiteMainPhp()));
            $this->addToZip($this->createFile('site/controller.php', $this->generateSiteControllerPhp()));
            $this->addToZip($this->createFile('site/views/' . $this->compname . '/view.html.php', $this->generateSiteViewPhp()));

            $this->generateAdminFolders();
            $this->addToZip($this->createFile('admin/' . $this->compname . '.php', $this->generateAdminMainPhp()));
            $this->addToZip($this->createFile('admin/controller.php', $this->generateAdminControllerPhp()));
            $this->addToZip($this->createFile('admin/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile('admin/controllers/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile('admin/models/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile('admin/models/fields/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile('admin/models/forms/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile('admin/views/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("admin/views/" . $this->compname . '/view.html.php', $this->generateAdminView()));
            $this->addToZip($this->createFile("admin/views/" . $this->compname . '/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("admin/views/" . $this->compname . '/tmpl/index.html', $this->getEmptyHtml()));
            $this->addToZip($this->createFile("admin/views/" . $this->compname . '/tmpl/default.php', $this->getEmptyPhp()));
            $this->addToZip($this->createFile('languages/en-GB.' . $this->sname . '.ini', strtoupper($this->sname) . '="' . (ucwords($this->name)) . '"'));
            $this->addToZip($this->createFile('languages/en-GB.' . $this->sname . '.sys.ini', strtoupper($this->sname) . '="' . (ucwords($this->name)) . '"'));


            $this->createAndSaveZip();
            $this->deleteTmpFolders();
        } else {
            $this->showform();
        }
    }

    private function showForm()
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
