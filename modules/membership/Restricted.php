<?php

namespace Membership;

class Restricted extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
        $this->c['user'];
        $this->c['flash'];

        // var_dump($this->c['auth.storage']->getKey('__permanent'));
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo '<html>';
        echo '<head>';
        echo '<script type="text/javascript">
        var ajax = {
            post : function(url, closure, params){
                var xmlhttp;
                if (window.XMLHttpRequest){
                    xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
                }else{
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
                }
                xmlhttp.onreadystatechange=function(){
                    if (xmlhttp.readyState==4 && xmlhttp.status==200){
                        if( typeof closure === \'function\'){
                            closure(xmlhttp.responseText);
                        }
                    }
                }
                xmlhttp.open("POST",url,true);
                xmlhttp.setRequestHeader(\'X-Requested-With\', \'XMLHttpRequest\');
                xmlhttp.send(params);
            },
            get : function() {
                // paste here
            }
        }
        function submitAjax() {
            ajax.post( "/examples/restricted", function(data){
                alert(data);
            });
        }
        </script>';
        echo '</head><body>';


        echo '<h1>Restricted Area</h1>';

        echo $this->flash->output();

        echo '<section>';

        echo '<a href="/membership/logout">Logout</a>';
        echo '<pre>';
        print_r($this->user->identity->getArray());
        echo '</pre>';

        echo '</section>';


        echo '<form action="/examples/login/restricted" method="POST" id="ajax_tutorial" />
                <input type="button" name="dopost" value="DoPost" onclick="submitAjax();" />
            </form></body></html>';
    }
}