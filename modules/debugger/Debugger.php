<?php

namespace Debugger;

class Debugger extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];

        if ($this->c['app']->getEnv() == 'production') {  // Disable debugger in production mode
            $this->c['response']->show404();
        }
    }

    /**
     * Write iframe
     *  
     * @return void
     */
    public function index()
    {
        $debuggerUrl  = $this->c['app']->uri->getBaseUrl(INDEX_PHP.'/debugger/console?'.FRAMEWORK.'_debugger=1'); // Disable logs sending by _debugger=1 params.
        $websocketUri = $this->c['config']['debugger']['socket'];

        $this->on();
        $this->runServer();  // Start debugger daemon

        echo '<!DOCTYPE html>
        <html>
        <head>
        <script type="text/javascript">

            var ajax = {
                get : function(url, closure, params){
                    var xmlhttp;
                    if (window.XMLHttpRequest){
                        xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
                    }else{
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            if( typeof closure === "function"){
                                closure(xmlhttp.responseText);
                            }
                        }
                    }
                    xmlhttp.open("GET",url,true);
                    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                    xmlhttp.send(params);
                }
            }
            function refreshDebugger(sock, env) {
                if (sock !=0 && refreshDebugger.socket == sock) {  // Just one time refresh the content for same socket id
                    return;
                }
                refreshDebugger.socket = sock;

                var f2 = document.getElementById("f2");
                f2 = f2.contentWindow.document;

                var f1 = document.getElementById("f1");
                f1 = f1.contentWindow.document;

                ajax.get("'.$debuggerUrl.'", function(html){
                        f2.body.innerHTML = html;
                        var ajaxDiv       = f2.getElementById("obulloDebugger-ajax-log");
                        var httpDiv       = f2.getElementById("obulloDebugger-http-log");
                        var consoleDiv    = f2.getElementById("obulloDebugger-console-log");
                        var wrapper       = f2.getElementById("obulloDebugger");
                        
                        ajaxDiv.scrollTop    = ajaxDiv.scrollHeight;
                        httpDiv.scrollTop    = httpDiv.scrollHeight;
                        consoleDiv.scrollTop = consoleDiv.scrollHeight;
                        wrapper.scrollTop    = wrapper.scrollHeight;

                        if (typeof env != "undefined") {
                            f2.getElementById("obulloDebugger-environment").innerHTML = decode64(env);
                        }
                    }
                );
            }
            function load(refresh){
                try
                {
                    var wsUri = "'.$websocketUri.'";           // Create webSocket connection
                    var websocket =  new WebSocket(wsUri);

                    websocket.onopen = function(data) {        // Connection is open 
                        console.log("Debugger websocket connection established.");
                    }
                    websocket.onmessage = function(response) { // Received messages from server
                        var msg = JSON.parse(response.data);   // Php sends Json data

                        if (msg.type == "system") {
                            if (msg.message == "HTTP_REQUEST") {
                                refreshDebugger(msg.socket, msg.env);
                            } else if (msg.message == "AJAX_REQUEST") {
                                refreshDebugger(msg.socket, msg.env);
                            } else if (msg.message == "CLI_REQUEST") {
                                refreshDebugger(msg.socket, msg.env);
                            }
                        }
                    };
                    websocket.onclose = function(data) {        // Connection is closed connect again ?
                        console.log("Connection closed.");
                    }
                    frame1.window.onbeforeunload = function() {
                        // websocket.close();
                    };

                }
                catch(ex)
                { 
                    console.log("Debugger exception error:" + ex);
                }
            }

            var keyStr = "ABCDEFGHIJKLMNOP" +
                           "QRSTUVWXYZabcdef" +
                           "ghijklmnopqrstuv" +
                           "wxyz0123456789+/" +
                           "=";

            function decode64(input) {
                 var output = "";
                 var chr1, chr2, chr3 = "";
                 var enc1, enc2, enc3, enc4 = "";
                 var i = 0;

                 // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
                 var base64test = /[^A-Za-z0-9\+\/\=]/g;
                 if (base64test.exec(input)) {
                    alert("There were invalid base64 characters in the input text.\n" +
                          "Valid base64 characters are A-Z, a-z, 0-9, \'+\', \'/\',and \'=\'\n" +
                          "Expect errors in decoding.");
                 }
                 input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                 do {
                    enc1 = keyStr.indexOf(input.charAt(i++));
                    enc2 = keyStr.indexOf(input.charAt(i++));
                    enc3 = keyStr.indexOf(input.charAt(i++));
                    enc4 = keyStr.indexOf(input.charAt(i++));

                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;

                    output = output + String.fromCharCode(chr1);

                    if (enc3 != 64) {
                       output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                       output = output + String.fromCharCode(chr3);
                    }

                    chr1 = chr2 = chr3 = "";
                    enc1 = enc2 = enc3 = enc4 = "";

                 } while (i < input.length);

                 return unescape(output);
              }
        </script>
        </head>

        <frameset rows="60%,40%" frameborder="0">
             <frame id ="f1" name="frame1" onload="load();" src="'.$this->c['app']->uri->getBaseUrl(INDEX_PHP.'?'.FRAMEWORK.'_debugger=1').'">
             <frame id ="f2" src="'.$debuggerUrl.'">
        </frameset>
        </html>';
    }

    /**
     * Enable debugger using shared memory
     *
     * @return void
     */
    public function on()
    {
        $key = (int)sprintf("%u", crc32('__obulloDebugger'));
        $id = @shmop_open($key, "c", 0644, strlen('On'));  // Create shared memory block

        $shmopBytesWritten = shmop_write($id, 'On', 0); // Lets write string into shared memory
        if ($shmopBytesWritten != shmop_size($id)) {
            die('Debugger couldn\'t write the entire length of data to memory.');
        }
        shmop_close($id);
    }

    /**
     * Disable debugger by removing it from shared memory
     * 
     * @return void
     */
    public function off()
    {       
        $key = (int)sprintf("%u", crc32('__obulloDebugger'));
        $id = @shmop_open($key, "a", 0644, 0); 
        if ($id) {
            shmop_delete($id);
            shmop_close($id);
        }
    }

    /**
     * Daemon of debugger server
     * 
     * @return void
     */
    protected function runServer()
    {
        $key = (int)sprintf("%u", crc32('__obulloDebugServerStatus'));

        $id = @shmop_open($key, "a", 0, 0);
        if (is_int($id)) {          // If key exists status run, do not run the process again.
            return;
        }

        $shell = PHP_PATH .' '. FPATH .'/'. TASK_FILE .' DebuggerServer index';  // /usr/bin/php /var/www/project/task DebuggerServer index
        shell_exec($shell . ' > /dev/null &');  // Async task

        $id = @shmop_open($key, "c", 0644, strlen('On'));  // Create shared memory block

        $shmopBytesWritten = shmop_write($id, 'On', 0);    // Lets write string into shared memory
        if ($shmopBytesWritten != shmop_size($id)) {
            die('Debugger couldn\'t write the entire length of data to memory.');
        }
        shmop_close($id);

    }

    /**
     * Write console output
     * 
     * @return void
     */
    public function console()
    {
        $debugger = new \Obullo\Application\Debugger\Output($this->c);
        echo $debugger->printDebugger();
    }

    /**
     * Clear all log data
     * 
     * @return voide
     */
    public function clear()
    {
        $debugger = new \Obullo\Application\Debugger\Output($this->c);
        $debugger->clear();
        echo $debugger->printDebugger();
    }

}

// END Debugger.phpFile
/* End of file Debugger.php

/* Location: .app/modules/Debugger/Debugger.php */