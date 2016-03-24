<?php

namespace Tasks;

use Obullo\Cli\Controller;
use Obullo\Cli\Console;

class Welcome extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo Console::logo("Welcome to Task Manager (c) 2016");
        echo Console::newline(2);

echo Console::help("Available commands:", true);
echo Console::newline(1);
echo Console::help("
welcome        : Run your first task.
welcome help   : See list all of available commands."
);
echo Console::newline(2);
echo Console::help("Usage:", true);
echo Console::newline(2);
echo Console::help("php task [command] method [arguments]");
echo Console::newline(1);
echo Console::help("php task [command] --help");
echo Console::newline(2);
    }

    public function help()
    {
        echo Console::logo("Welcome to Task Manager (c) 2015");

echo Console::newline(2);
echo Console::help("Help:", true);
echo Console::help("

Available Commands

    help        : Display help."
);
echo Console::newline(2);
    }

}