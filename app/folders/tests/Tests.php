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

        $html = "";
        foreach ($folders as $folder) {

            if ($folder == 'views')
                continue;

            $html.= "<ul><li>".ucfirst($folder)."<ul>";
            $files = scandir(FOLDERS .'tests/'.$folder);
            unset($files[0], $files[1]);

            foreach ($files as $file) {
                
                if ($file == 'views')
                continue;

                $html.= "<li>".$this->url->anchor("tests/".$folder."/".strtolower(substr($file, 0, -4)), $file)."</li>";
            }
            $html.= "</ul></li></ul>";
        }

        $this->view->load(
            'test',
            [
                'content' => $html
            ]
        );
    }
}