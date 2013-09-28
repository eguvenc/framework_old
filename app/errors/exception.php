<style type="text/css">
#exceptionContent {
font-family:Arial,Verdana,Sans-serif;
font-size:12px;
width:99%;
padding:5px;
background-color: #F0F0F0;
}
#exceptionContent  h1 {
font-size:          16px;
color:              #333;
margin:             0;
}
#exceptionContent  h2 {
font-size:          12px;
color:              #333;
margin:             0;
}
#exceptionContent .errorFile { 
display:block;
line-height: 2.0em; 
}
#exceptionContent pre.source { 
margin: 0px 0 0px 0; 
padding: 0; 
background: none; 
border: none; 
line-height: none; 
}
#exceptionContent div.collapsed { display: none; }
#exceptionContent div.arguments { }
#exceptionContent div.arguments table { 
font-family : Verdana, Arial, Sans-serif;
font-size:12px; 
border-collapse: collapse; 
border-spacing: 0; 
background: #fff;  
}
#exceptionContent div.arguments table td { text-align: left; padding: 5px; border: 1px solid #ccc; }
#exceptionContent div.arguments table td .object_name { color: blue; }
#exceptionContent pre.source span.line { display: block; }
#exceptionContent pre.source span.highlight { background: #D3D3D3; }
#exceptionContent pre.source span.line span.number { color: none; }
#exceptionContent pre.source span.line span.number { color: none; }
</style>

<script type="text/javascript">
function ExceptionElement() {
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
function ExceptionToggle(obj){
    var el = ExceptionElement(obj);
    if (el == null){
        return false;
    }
    el.className = (el.className != 'collapsed' ? 'collapsed' : '' );
}
</script>

<div id="exceptionContent">
    <h1><?php echo $type ?></h1>
    <h2><?php echo Error::getSecurePath($e->getMessage(), true) ?></h2>
<?php 
if(isset($sql)) 
{
    echo '<span class="errorFile"><b>SQL :</b> '.$sql.'</span>';
}
?>
<?php $code = ($e->getCode() != 0) ? ' Code : '. $e->getCode() : '' ?> 
<span class="errorFile"><?php echo Error::getSecurePath($e->getFile()) ?><?php echo $code ?><?php echo ' ( Line : '.$e->getLine().' ) ' ?></span>
<?php 
$debug = config('debug_backtrace');
if($debug['enabled'] === true OR $debug['enabled'] == 1)  // Covert to readable format
{
    $debug['enabled'] = 'E_ALL';
}

$rules = Error::parseRegex($debug['enabled']);
$allowedErrors = Error::getAllowedErrors($rules);

$eCode  = (substr($e->getMessage(),0,3) == 'SQL') ? 'SQL' : $e->getCode();

if(is_string($debug['enabled'])) 
{
    // Show source code for first exception trace
    // ------------------------------------------------------------------------
    $eTrace['file'] = $e->getFile();
    $eTrace['line'] = $e->getLine();

    echo Error::debugFileSource($eTrace);
    
    if( ! isset($allowedErrors[$eCode]))   // Check debug_backtrace enabled for current error. 
    {
        echo '</div>'; return;
    }
    
    // ------------------------------------------------------------------------
    
    $fullTraces = $e->getTrace();

    $debugTraces = array();
    foreach($fullTraces as $key => $val)
    {
        if( isset($val['file']) AND isset($val['line']))
        {   
            $debugTraces[] = $val;
        }
    }
    
    if(isset($debugTraces[0]['file']) AND isset($debugTraces[0]['line']))
    {
        if($debugTraces[0]['file'] == $e->getFile() AND $debugTraces[0]['line'] == $e->getLine())
        {
            unset($debugTraces[0]);
            
            $unset = true;
        } 
        else 
        {
            $unset = false;
        }
        
        if(isset($debugTraces[1]['file']) AND isset($debugTraces[1]['line'])) 
        { 
            $classInfo = '';
            foreach($debugTraces as $key => $trace) 
            {                    
                $prefix = uniqid().'_';

                if(isset($trace['file'])) 
                {                        
                    $classInfo = '';
                    
                    if(isset($trace['class']) AND isset($trace['function']))
                    {
                        $classInfo.= $trace['class'] .'->'. $trace['function'];  
                    }
                    
                    if( ! isset($trace['class']) AND isset($trace['function']))
                    {
                        $classInfo.= $trace['function']; 
                    }
                    
                    if(isset($trace['args']))  
                    {       
                        if(count($trace['args']) > 0)
                        {                  
                            $classInfo.= '(<a href="javascript:void(0);" ';
                            $classInfo.= 'onclick="ExceptionToggle(\'arg_toggle_'.$prefix.$key.'\');">';
                            $classInfo.= 'arg';
                            $classInfo.= '</a>)'; 
                            
                            $classInfo.= '<div id="arg_toggle_'.$prefix.$key.'" class="collapsed">';
                            $classInfo.= '<div class="arguments">';

                            $classInfo.= '<table>';
                            foreach($trace['args'] as $arg_key => $arg_val)
                            {
                                $classInfo.= '<tr>';
                                $classInfo.= '<td>'.$arg_key.'</td>';
   
                                if($trace['function'] == 'pdoConnect' AND ($arg_key == 2 OR $arg_key == 1)) // hide database password for security.
                                {
                                    $classInfo.= '<td>***********</td>';
                                } 
                                else 
                                {
                                    $classInfo.= '<td>'.Error::dumpArgument($arg_val).'</td>';
                                }
                                
                                $classInfo.= '</tr>'; 
                            }
                            $classInfo.= '</table>';
                            
                            $classInfo.= '</div>';
                            $classInfo.= '</div>';
                        }
                        else
                        {
                            $classInfo.= (isset($trace['function'])) ? '()' : '';     
                        }
                    }
                    else
                    {
                        $classInfo.= (isset($trace['function'])) ? '()' : '';    
                    }
                    
                    echo '<div class="error_file" style="line-height: 2em;">'.$classInfo.'</div>';
                }

                if($unset == false) { ++$key; }
                ?>
                
                <span class="errorFile" style="line-height: 1.8em;">
                <a href="javascript:void(0);" onclick="ExceptionToggle('error_toggle_' + '<?php echo $prefix.$key?>');"><?php echo Error::getSecurePath($trace['file']); echo ' ( '?><?php echo ' Line : '.$trace['line'].' ) '; ?></a>
                </span>
        
                <?php 
                // Show source codes foreach traces
                // ------------------------------------------------------------------------
                
                echo Error::debugFileSource($trace, $key, $prefix);
                
                // ------------------------------------------------------------------------
                ?>
                 
    <?php   } // end foreach ?>

    <?php }   // end if isset ?>     
    <?php }   // end if isset ?>

<?php }   // end if debug backtrace ?>
</div>