<style type="text/css">
#exception_content {
font-family : Verdana, "Bitstream Vera Sans", "DejaVu Sans", Tahoma, Geneva, Arial, Sans-serif;
font-size:12px;
width:99%;
padding:5px;
background-color: #F0F0F0;
}
#exception_content .errorfile { 
display:block;
line-height: 2.0em; 
}
#exception_content pre.source { 
margin: 5px 0 5px 0; 
padding: 0; 
background: none; 
border: none; 
line-height: none; 
}
#exception_content div.collapsed { display: none; }
#exception_content div.arguments { }
#exception_content div.arguments table { 
font-family : Verdana, "Bitstream Vera Sans", "DejaVu Sans", Tahoma, Geneva, Arial, Sans-serif;
font-size:12px; 
border-collapse: collapse; 
border-spacing: 0; 
background: #fff;  
}
#exception_content div.arguments table td { text-align: left; padding: 5px; border: 1px solid #ccc; }
#exception_content div.arguments table td .object_name { color: blue; }
#exception_content pre.source span.line { display: block; }
#exception_content pre.source span.highlight { background: #E0E0E0; }
#exception_content pre.source span.line span.number { color: none; }
#exception_content pre.source span.line span.number { color: none; }
</style>

<script type="text/javascript">
function Obullo_Element() {
    var elements = new Array();
    for (var i = 0; i < arguments.length; i++){
        var element = arguments[i];
        if (typeof element == 'string')
            element = document.getElementById(element);
        if (arguments.length == 1)
            return element;
        elements.push(element);
    }
    return elements;
}
function Obullo_Error_Toggle(obj){
    var el = Obullo_Element(obj);
    if (el == null){
        return false;
    }
    el.className = (el.className != 'collapsed' ? 'collapsed' : '' );
}
</script>

<div id="exception_content">
<b>(<?php echo $type ?>):  <?php echo error\securePath($e->getMessage(), true) ?></b><br />
<?php 
if(isset($sql)) 
{
    echo '<span class="errorfile"><b>SQL :</b> '.$sql.'</span>';
}
?>
<?php $code = ($e->getCode() != 0) ? ' Code : '. $e->getCode() : ''; ?> 
<span class="errorfile"><?php echo error\securePath($e->getFile()) ?><?php echo $code; ?><?php echo ' ( Line : '.$e->getLine().' ) '; ?></span>
<?php 
$debug  = config('debug_backtrace');
if($debug['enabled'] === true OR $debug['enabled'] == 1)  // Covert to readable format
{
    $debug['enabled'] = 'E_ALL';
} 
$rules  = error\parseRegex($debug['enabled']);
$e_code = (substr($e->getMessage(),0,3) == 'SQL') ? 'SQL' : $e->getCode();
$allowed_errors = error\getAllowedErrors($rules);  

if(is_string($debug['enabled'])) 
{
    // Show source code for first exception trace
    // ------------------------------------------------------------------------
    $e_trace['file'] = $e->getFile();
    $e_trace['line'] = $e->getLine();

    echo error\writeFileSource($e_trace);
    
    if( ! isset($allowed_errors[$e_code]))   // Check debug_backtrace enabled for current error. 
    {
        echo '</div>'; return;
    }
    
    // ------------------------------------------------------------------------
    
    $full_traces = error\debugBacktrace($e);

    $debug_traces = array();
    foreach($full_traces as $key => $val)
    {
        if( isset($val['file']) AND isset($val['line']))
        {   
            $debug_traces[] = $val;
        }
    }
    
    if(isset($debug_traces[0]['file']) AND isset($debug_traces[0]['line']))
    {
        if($debug_traces[0]['file'] == $e->getFile() AND $debug_traces[0]['line'] == $e->getLine())
        {
            unset($debug_traces[0]);
            
            $unset = true;
        } 
        else 
        {
            $unset = false;
        }
        
        if(isset($debug_traces[1]['file']) AND isset($debug_traces[1]['line'])) 
        { 
            $class_info = '';
            foreach($debug_traces as $key => $trace) 
            {                    
                $prefix = uniqid().'_';

                if(isset($trace['file'])) 
                {                        
                    $class_info = '';
                    
                    if(isset($trace['class']) AND isset($trace['function']))
                    {
                        $class_info.= $trace['class'] .'->'. $trace['function'];  
                    }
                    
                    if( ! isset($trace['class']) AND isset($trace['function']))
                    {
                        $class_info.= $trace['function']; 
                    }
                    
                    if(isset($trace['args']))  
                    {       
                        if(count($trace['args']) > 0)
                        {                  
                            $class_info.= '(<a href="javascript:void(0);" ';
                            $class_info.= 'onclick="Obullo_Error_Toggle(\'arg_toggle_'.$prefix.$key.'\');">';
                            $class_info.= 'arg';
                            $class_info.= '</a>)'; 
                            
                            $class_info.= '<div id="arg_toggle_'.$prefix.$key.'" class="collapsed">';
                            $class_info.= '<div class="arguments">';

                            $class_info.= '<table>';
                            foreach($trace['args'] as $arg_key => $arg_val)
                            {
                                $class_info.= '<tr>';
                                $class_info.= '<td>'.$arg_key.'</td>';
   
                                if($trace['function'] == 'pdo_connect' AND ($arg_key == 2 OR $arg_key == 1)) // hide database password for security.
                                {
                                    $class_info.= '<td>***********</td>';
                                } 
                                else 
                                {
                                    $class_info.= '<td>'.error\dumpArgument($arg_val).'</td>';
                                }
                                
                                $class_info.= '</tr>'; 
                            }
                            $class_info.= '</table>';
                            
                            $class_info.= '</div>';
                            $class_info.= '</div>';
                        }
                        else
                        {
                            $class_info.= (isset($trace['function'])) ? '()' : '';     
                        }
                    }
                    else
                    {
                        $class_info.= (isset($trace['function'])) ? '()' : '';    
                    }
                    
                    echo '<div class="error_file" style="line-height: 2em;">'.$class_info.'</div>';
                }

                if($unset == false) { ++$key; }
                ?>
                
                <span class="errorfile" style="line-height: 1.8em;">
                <a href="javascript:void(0);" onclick="Obullo_Error_Toggle('error_toggle_' + '<?php echo $prefix.$key?>');"><?php echo error\securePath($trace['file']); echo ' ( '?><?php echo ' Line : '.$trace['line'].' ) '; ?></a>
                </span>
        
                <?php 
                // Show source codes foreach traces
                // ------------------------------------------------------------------------
                
                echo error\writeFileSource($trace, $key, $prefix);
                
                // ------------------------------------------------------------------------
                ?>
                 
    <?php   } // end foreach ?>

    <?php }   // end if isset ?>     
    <?php }   // end if isset ?>

<?php }   // end if debug backtrace ?>
</div>