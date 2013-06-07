<script type="text/javascript" charset="utf-8">
<!--
/**
* Obullo Framework (c) 2010.
*
* PHP5 HMVC Based Scalable Software.
* 
* @package       obullo       
* @author        obullo.com
*/

/**              
* Popup Window For Profiler 
* Class.
*/

ob_profiler_window = window.open ("", "mywindow", "menubar=0, resizable=1, location=0, status=0, scrollbars=1, width=700, height=600");
ob_profiler_window.document.write("<html><head>");
ob_profiler_window.document.write("</head>");
ob_profiler_window.document.write("<body>");
ob_profiler_window.document.write("<?php echo addslashes($output); ?>");
ob_profiler_window.document.write("</body></html>");
ob_profiler_window.moveTo(260,0);

var headID      = ob_profiler_window.document.getElementsByTagName("head")[0];         
var cssNode     = ob_profiler_window.document.createElement('link');

cssNode.type    = 'text/css';
cssNode.rel     = 'stylesheet';
cssNode.href    = '<?php echo this()->config->public_url()?>css/profiler.css';
cssNode.media   = 'screen';
headID.appendChild(cssNode);

ob_profiler_window.document.close(); /* Close the document session */

//-->
</script>