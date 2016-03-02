<?php

namespace Tests;

use Obullo\Http\Controller;

class Tests extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $folders = scandir(FOLDERS .'tests');

        unset($folders[0], $folders[1], $folders[2]);

        foreach ($folders as $folder) {
            
            echo "<b>".ucfirst($folder)."</b><br>";

            $files = scandir(FOLDERS .'tests/'.$folder);

            unset($files[0], $files[1]);

            foreach ($files as $file) {
                echo "&nbsp;&nbsp;".$this->url->anchor("tests/".$folder."/".strtolower(substr($file, 0, -4)), $file)."<br>";
            }
        }
    }
}