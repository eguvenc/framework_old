<?php

namespace Tests;

use Obullo\Http\Controller;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Tests extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $ritit = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(FOLDERS .'tests', RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        $r = array();
        foreach ($ritit as $splFileInfo) {
            $path = $splFileInfo->isDir() ? array($splFileInfo->getFilename() => array()) : array($splFileInfo->getFilename());
            if (in_array(
                $splFileInfo->getFilename(),
                [
                    'views',
                    'fail.php',
                    'index.php',
                    'pass.php',
                    'result.php',
                    'test.php'
                ]
            )) {
                continue;
            }
            for ($depth = $ritit->getDepth() - 1; $depth >= 0; $depth--) {
                $path = array($ritit->getSubIterator($depth)->current()->getFilename() => $path);
            }
            $r = array_merge_recursive($r, $path);
        }

        $this->view->load(
            'test',
            [
                'content' => $this->getHtml($r)
            ]
        );
    }

    /**
     * Returns to folder index
     * 
     * @param array $folders array
     * 
     * @return string html
     */
    protected function getHtml($folders)
    {
        $html = "";
        foreach ($folders as $folder => $filename) {
            if ($folder) {
                $folder = ucfirst($folder);
                $html.= "<ul><li>".$folder."<ul>";
            }
            if (is_array($filename)) {
                $subfolder = "";
                foreach ($filename as $key => $value) {
                    if (! is_numeric($key)) {
                        $subfolder = $key;
                        foreach ($value as $file) {
                            $html.= "<li>".$this->url->anchor("tests/".strtolower($folder)."/".$subfolder."/".strtolower(substr($file, 0, -4)), ucfirst($subfolder).'/'.$file)."</li>";
                        }
                    } elseif (is_numeric($key)) {
                        $html.= "<li>".$this->url->anchor("tests/".strtolower($folder)."/".strtolower(substr($value, 0, -4)), $value)."</li>";
                    }
                }
            }
            $html.= "</ul></li></ul>";
        }
        return $html;
    }

}