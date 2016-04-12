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
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(FOLDERS .'tests', RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        $results = array();
        foreach ($iterator as $splFileInfo) {
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
            for ($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--) {
                $path = array($iterator->getSubIterator($depth)->current()->getFilename() => $path);
            }
            $results = array_merge_recursive($results, $path);
        }
        
        $this->view->load(
            'test',
            [
                'content' => $this->getHtml($results)
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

                        // Unix directories does not support uppercase characters 
                        // we force to all directory names to lowercase

                        $subfolder = $key;
                        foreach ($value as $k => $file) {

                            if (is_array($file)) {
                                $subfolder = $key;
                                $subfolder = $subfolder.'/'.ucfirst($k);
                                foreach ($file as $v) {
                                    $html.= "<li>".$this->url->anchor("tests/".strtolower($folder)."/".strtolower($subfolder)."/".substr($v, 0, -4))."</li>";
                                }

                            } else {
                                $html.= "<li>".$this->url->anchor("tests/".strtolower($folder)."/".strtolower($subfolder)."/".substr($file, 0, -4))."</li>";
                            }
                        }
                    } elseif (is_numeric($key)) {
                        $file = substr($value, 0, -4);
                        $html.= "<li>".$this->url->anchor("tests/".strtolower($folder)."/".$file)."</li>";
                    }
                }
            }
            $html.= "</ul></li></ul>";
        }
        return $html;
    }

}