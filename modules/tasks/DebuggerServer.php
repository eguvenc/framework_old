<?php

namespace Tasks;

use RuntimeException;
use Obullo\Process\Process;

class DebuggerServer extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function index()
    {
        $process = new Process("php task debugger", ROOT, null, null, 1);
        echo $process->run();
    }
}

// END DebuggerServer.php File
/* End of file DebuggerServer.php

/* Location: .modules/tasks/DebuggerServer.php */