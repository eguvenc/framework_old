<?php

/**
 * Build Dynamic Html Forms
 * 
 * @author rabihsyw <rabihsyw@gmail.com>
 * @package       packages
 * @subpackage    uform
 * @category      form builder
 * 
 * @tutorial    Allowed funtions list : 
 *              input => $this->$method_name();
 *              input, 
 *              hidden, 
 *              radio, 
 *              checkbox, 
 *              dropdown, 
 *              submit, 
 *              password, 
 *              textarea
 */

Class Form_Builder
{
    private $output;
    private $columnStorage;   // form columns informations
    private $rowNum   = 0;
    private $colNum   = 0;
    private $inputNum = 0;
    private $colNames = array();
    private $closure;
    private $_identifier;
    public  $config;
    private $schemaRules;
    private $formTagArgs = '';
    private $_jsObj;

    private $options = array(
        'ajax' => false,
        'wrapperClass' => '',
        );

    private static $css   = 'form_builder.css';
    private static $forms = array();     // multiforms

    // --------------------------------------------------------------------
    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        // set the new instance each time to the controller
        // otherwise must change the params to static
        getInstance()->form_builder = $this;  // Make available it in the controller.

        if(! isset(getInstance()->form) )
        {
            getInstance()->form = new Form;
        }

        $this->config = getConfig('form_builder');

        $args = func_get_args();
        $this->closure = $args[2];  // saving builder function
        
        unset($args[2]); // unset argument

        $this->formTagArgs = $args;

        $this->options['ajax'] = false;

        logMe('debug', 'Form Builder Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * This function is used to handle some functions.
     * handle getInstance()->form->input() functions
     * 
     * all the public functions in the class, must holds 'form-identifier as a parameter .'
     */
    public function __call($method, $arguments)
    {
        switch ($method)
        {
            /*
            Handling the external calls for the following functions.
            We override the behaviour of these functions in the constructor,
            while adding them externally in the Controller.
            */
            case 'input':
            case 'password':
            case 'hidden':
            case 'submit':
            case 'checkbox':
            case 'radio':
            case 'dropdown':
            case 'textarea':
            case 'multiselect':
            case 'button':
            {
                $colname = $arguments[0];
                $this->colNames[$this->rowNum][$this->colNum] = $colname; // set column name
                return array("field_name" => $colname,"method" => $method, "arguments" => $arguments);
                break;
            }
            case 'isValid':
            case 'printForm':
            {
                $identifier = $arguments[0];
                unset($arguments[0]);

                $config = $this->config;
                
                if(empty(self::$forms[$identifier]))
                {
                    throw new Exception('Form Builder Error : trying to printForm for a not created form.');
                }

                //return self::$forms[$identifier]->$method();
                return call_user_func_array(array(self::$forms[$identifier], 'fb_'.$method), $arguments);
            }
            case 'captcha':
            {
                if ( ! isset(getInstance()->sess) )
                {
                    getInstance()->sess = new Sess;
                }
                $config = $this->config;
                
                $identifier = $this->_identifier;

                // creating validation callback function for captcha.
                getInstance()->form->func('callback_captcha_'.$identifier, function() use ($arguments,$identifier, $config) {
                
                    
                    $code = $this->sess->get($this->post->get($config['captcha']['hidden_input_name']));

                    if( $this->post->get($arguments[0]) != $code )
                    {
                        $this->setMessage('callback_captcha_'.$identifier, translate('Security code doesn\'t match security image. '));
                        return false;
                    }
                    return true;

                });

                $colname = $arguments[0];

                $this->_setColumnArray('rules', 'callback_captcha_'.$this->_identifier);

                $this->colNames[$this->rowNum][$this->colNum] = $colname; // set column name
                return array("field_name" => $colname, "method" => $method, "arguments" => $arguments);
                break;
            }
            case 'inputList':
            {
                $i = 0;
                $test = '';
                foreach($arguments[0] as $key => $arg)
                {
                    $this->_processSecondInput($arg);
                }

                return eval($test);
                break;
            }
            default:
            {
                throw new Exception("Form Builder Error : wrong method name '$method'. Please review the readme file.");
                break;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Check the model instance
     */
    public function __get($name)
    {
        if( isset(getInstance()->$name) )
        {
            $appModelName = 'AppModel_'.$name;

            if(getInstance()->{$name} instanceof $appModelName)
            {
                foreach(getInstance()->$name->_odmSchema as $column_name => $values)
                {
                    if( ! empty($values['rules']) )
                    {
                        $this->schemaRules[$column_name] = $values['rules'];
                    }
                    
                }
            }
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Create the form, the main function.
     * 
     * @param  string  form Identifier
     */
    public function create($identifier)
    {
        $this->_identifier = $identifier;

        call_user_func_array(Closure::bind($this->closure, $this, get_class()), array());
        
        $this->formTagArgs[1]['data-identifier'] = $identifier;
        $this->formTagArgs[1]['id'] = $identifier;
        $this->formTagArgs[1]['data-ajax'] = ($this->options['ajax']) ? '1' : '0';
        $this->output = "\t".call_user_func_array(array(getInstance()->form, 'open'), $this->formTagArgs); // open form tag

        self::$forms[$identifier] = $this;
    }

    // --------------------------------------------------------------------
    /**
     * Run the validation
     * 
     * @return boolean
     */
    protected function fb_isValid()
    {
        $this->_setRules();

        return getInstance()->form->isValid();
    }

    // --------------------------------------------------------------------
    
    /**
     * This function is used to add rows to the form
     */
    protected function addRow()
    {
        $this->rowNum++;
        $this->colNum = 0;
    }

    // --------------------------------------------------------------------

    /**
     * Adding new column to the form
     * 
     * @param $value : label' => '', input => array(), array("input" => array())
     */
    protected function addCol($data)
    {
        if ( ! array_key_exists('input', $data))  // for radios & checkboxes & more than one input per column : "input" isn't set directly in $arg
        {
            foreach ($data as $key => $item)
            {
                $tempRules = (isset($data['rules'])) ? $data['rules'] : '';
                $this->_processSecondInput($item, $tempRules );
                // var_dump($item);
                // $this->_buildJSObj('label', $data['label'], $item['input']['field']['field_name'] );
            }
        }
        else // normal set
        {
            $this->_setInputInfo('field', $data['input']);
            $this->_buildJSObj($data);
            $this->inputNum++;
        }

        if(array_key_exists('label', $data))
        {
            $tempLabel = $data['label'];
            $this->_setColumnArray('label', $tempLabel);
        }

        if(array_key_exists('rules', $data))
        {
            $label = (isset($data['label'])) ? ucfirst(strtolower( $data['label'] )) : '';
            $this->_setColumnArray('rules', $data['rules']);
            
            // the following mixs rules for jsObj for 'array inputs' with 'col' rules.
            if(isset($data['input'])) // add rules to js obj
                $this->_buildJSObj('rules', $data['rules'], $data['input']['field_name'] );
        }

        if(isset($data['attr']))
        {
            $tempAttr = $data['attr'];
            $this->_setColumnArray('attr', $tempAttr);
        }

        // increase column index in the columns array
        $this->colNum ++;
        $this->inputNum = 0;
    }

    // ---------------------------------------------------------------
    
    /**
     * [_processSecondInput description]
     * @param  [type] $item  [description]
     * @param  string $rules [this param is optional, used to pass rules to _buildJSObj , to mix column rules with input rules.]
     */
    protected function _processSecondInput($item, $rules = '')
    {
        if(is_array($item))
        {
            foreach($item as $key2 => $value2)
            {   
                if($key2 == 'label')
                {
                    $this->_setInputInfo('inner_label', $value2);
                }
                if($key2 == 'rules')
                {
                    $this->_setInputInfo('rules', $value2);
                }
                if($key2 == 'input')
                {
                    $this->_setInputInfo('field', $value2);
                    
                    // if( ! in_array($value2['method'], array('radio','checkbox')) )
                    {
                        $this->_buildJSObj($item, $rules);
                    }
                }
                if($key2 == 'attr')
                {
                    $this->_setInputInfo('attr', $value2);
                }
                
            }
        }
        $this->inputNum++;
    }

    // -------------------------------------------------------------------
    
    /**
     * [_buildJSObj This function builds a javascript object for all form elements which can be used for validation and so.]
     * @param  [array||string] $item       [can be column input array if you are setting a column, or can be a string if you are setting an attribute. 
     *                                     ie : for rules 'rules']
     * @param  string $new        [used for mixing column rules with input rules.]
     * @param  string $field_name [the input name (field name)]
     * @return [null]             [null]
     */
    protected function _buildJSObj($item, $new = '', $field_name = '')
    {
        if( $item == 'rules' && isset($field_name))
        {
            $rules = preg_replace('/(callback[_a-z]*\|?)(\'|\"|)/i','',$new);

            if(isset($this->_jsObj[$field_name]['rules'])){
                $oldRules = $this->_jsObj[$field_name]['rules'];
                $rules = $this->_distinctRules($rules, $oldRules);
            }
            
            $this->_jsObj[$field_name][$item] = $rules;

        }elseif( $item == 'label' && isset($field_name))
        {
            $rules = preg_replace('/(callback[_a-z]*\|?)(\'|\"|)/i','',$new);

            if(empty($this->_jsObj[$field_name]['label'])){
                $this->_jsObj[$field_name][$item] = $new;
            }
        }elseif(is_array($item)){

            $type = $item['input']['method'];

            if(in_array($item['input']['method'], array('button', 'submit', 'hidden')))
            {
                return false;
            }

            if(preg_match('/^input/i', $item['input']['method']))
            {
                $type = 'text';
            }

            if(preg_match('/^dropdown/i', $item['input']['method']))
            {
                $type = 'select';
            }

            $columnRules = (isset($new)) ? preg_replace('/(callback[_a-z]*\|?)(\'|\"|)/i','',$new) : '';

            $rules = (isset($item['rules'])) ? preg_replace('/(callback[_a-z]*\|?)(\'|\"|)/i','',$item['rules']) : '';

            $rules = $this->_distinctRules($rules, $columnRules);

            $this->_jsObj[$item['input']['field_name']] = array(
                'label' => (isset($item['label'])) ? translate($item['label']) : '',
                'rules' => $rules,
                'input' => array('type' => $type, 'name' => $item['input']['field_name'])
                );
        }
    }

    protected function _processColumnClass($attr = '' , $defaultClass)
    {
        if(preg_match('/class\s*=\s*[\"\'](?<mymatch>.*?)[\"\']/i', $attr,$match))
        {
            $attr = preg_replace("/class\s*=\s*[\"\'](?<mymatch>.*?)[\"\']/i", "class='$match[mymatch] $defaultClass' ", $attr);
        }else{
            $attr = (empty($attr)) ? " class='$defaultClass' " : $attr . " class='$defaultClass' ";
        }

        return $attr;
    }

    // --------------------------------------------------------------------

    /**
     * Assign values to the columns
     * 
     * @param string $index index name of the array element
     * @param string $value value 
     */
    protected function _setColumnArray($index, $value)
    {
        if($index === 'rules')
        {
            if( ! empty ($this->columnStorage[$this->rowNum]['columns'][$this->colNum][$index]) )
            {
                $value .= '|'.$this->columnStorage[$this->rowNum]['columns'][$this->colNum][$index];
            }
        }
        $this->columnStorage[$this->rowNum]['columns'][$this->colNum][$index] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Assign values to the input Array
     * 
     * @param string $index index name of the array element
     * @param string $value value 
     */
    protected function _setInputInfo($index, $value)
    {
        if($index === 'rules')
        {
            if( ! empty ($this->columnStorage[$this->rowNum]['columns'][$this->colNum]['input'][$this->inputNum][$index]) )
            {
                $value .= '|'.$this->columnStorage[$this->rowNum]['columns'][$this->colNum]['input'][$this->inputNum][$index];
            }
        }
        $this->columnStorage[$this->rowNum]['columns'][$this->colNum]['input'][$this->inputNum][$index] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Print Input
     *
     * @param  string $input
     * @return string
     */

    protected function _printInput($input)
    {
        if (is_array($input))
        {
            switch ($input['method'])
            {
                case 'input':
                case 'password':
                case 'hidden':
                case 'submit':
                case 'checkbox':
                case 'radio':
                case 'dropdown':
                case 'textarea':
                case 'multiselect':
                case 'button':
                {
                    return call_user_func_array(array(getInstance()->form, $input['method']), $input['arguments']);
                    break;
                }
                case 'captcha':
                {
                    return $this->_captchaBuild($input['arguments']);
                    break;
                }
            }
        }
        return;
    }

    // --------------------------------------------------------------------
    
    /**
     * Set position for label or input
     * 
     * $this->setPosition('label' , 'left');
     * $this->setPosition('input' , 'right');
     * $this->setPosition('error' , 'bottom');
     * 
     * @param string element name
     * @param string position
     */
    protected function setPosition($element, $position = 'left')
    {
        if (in_array($element, array('label', 'input', 'error')) AND in_array($position, array('left', 'top', 'right', 'center','bottom')))
        {
            $this->columnStorage[$this->rowNum]['position'][$element] = $position;
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Set Title for row
     * 
     * 
     * @param string element name
     * @param string position
     */
    protected function setTitle($title = '')
    {
        $this->columnStorage[$this->rowNum]['title'] = $title;
    }

    // --------------------------------------------------------------------
    
    /**
     * set Class for row
     * 
     * $this->setClass('class1' , 'class2');
     * 
     * @param string params
     */
    protected function setClass()
    {
        $args = func_get_args();
        if( !empty($args) )
        {
            foreach($args as $arg)
            {
                $this->columnStorage[$this->rowNum]['class'] = empty($this->columnStorage[$this->rowNum]['class']) ? $arg : $this->columnStorage[$this->rowNum]['class'] . ' ' . $arg;
            }
        }
    }

    protected function _printJSObj()
    {
        $out = "\n<script>";
        $out .= "\tvar {$this->_identifier}_fields = '".json_encode($this->_jsObj)."';" ;
        $out .= "\n</script>";
        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * Print the form HTML
     * 
     * @return string
     */
    protected function fb_printForm()
    {
        $out = "\n<!-- Start Form-Builder '$this->_identifier' -->\n";
        $out .= $this->_printJSObj();
        $out .= "\n<div class='form-builder-div-wrapper ".($this->options['wrapperClass'])."'>\n";         // check the printout type "table or div"
        $out .= $this->output;

        if (is_array($this->columnStorage))         // looping the fields array
        {
            foreach ($this->columnStorage as $rowNum => $v)
            {
                $out .= (! empty($v['title']) ) ? "<div class='form-builder-row-title'>$v[title]</div>" : ''; // printing row title

                $rowClass = ( !empty($v['class']) ? 'form-builder-row '.$v['class'] : 'form-builder-row' ); // row class
                
                $out .= "\n\t\t<div class='$rowClass'>";  // printing a row "<div>"

                if (is_array($v))
                {
                    $columnsNum = count($v['columns']);     // get column grid class
                    $gridClass  = $this->_calculateWidth($columnsNum);

                    foreach ($v['columns'] as $colNum => $v2)
                    {
                        $label = $error = $columnContent = '';
                        
                        $addon_class = (isset($v['position']['input'])) ? "form-builder-ipos-" . $v['position']['input'] : "";
                        $addon_class .= ' '.array_shift($gridClass);
                        $addon_class .= ' form-builder-column ';

                        $attrs = $this->_processColumnClass((!empty($v2['attr']) ? $v2['attr'] : ''), $addon_class);

                        $out  .= "\n\t\t\t<div ".( ( !empty( $attrs ) ) ? $attrs : '' )." >";  // add the column TD
                        $label = "\n\t\t\t\t".$this->_printLabel($rowNum, $colNum);
                        
                        list($columnContent, $error) = $this->_printColumnContent($rowNum, $colNum);  // retrive column content.

                        $inputWrapper = "\n\t\t\t\t<div class='form-builder-field-wrapper'>\n";
                        
                        // error position top, bottom
                        if( ! empty($v['position']['error']) )
                        {
                            if($v['position']['error'] == 'bottom')
                            {
                                $inputWrapper .= $columnContent;
                                $inputWrapper .= "<div class='clear' ></div>";
                                $inputWrapper .= $error;
                            }
                            elseif($v['position']['error'] == 'top')
                            {
                                $inputWrapper .= $error;
                                $inputWrapper .= "<div class='clear' ></div>";
                                $inputWrapper .= $columnContent;
                            }
                        }else{
                            $inputWrapper .= $columnContent;
                            $inputWrapper .= "<div class='clear' ></div>";
                            $inputWrapper .= $error;
                        }

                        $inputWrapper .= "\n\t\t\t\t</div>";

                        $out .= $label . $inputWrapper;
                        $out .= "\n\t\t\t</div>"; // close the div tags
                    }
                }

                $out .= "\n\t\t<div class='clear' ></div></div>\n";  // close the "form-builder-row" div
            }
        }

        $out .= "\t".getInstance()->form->close()."\n";
        $out .= "</div>\n";

        $out .= "<div class='clear' ></div>";
        $out .= "\n<!-- End Form-Builder '$this->_identifier' -->\n";

        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * Print CSS file embed tag
     */
    public function printCss()
    {
        return getInstance()->html->css(self::$css);
    }

    // --------------------------------------------------------------------

    /**
     * This function determines which 'css classes'
     * will be assigned to each column
     * 
     * @return array
     */
    protected function _calculateWidth($columnsNum)
    {
        $gridSys = array(
            1 => array(10),
            2 => array(5, 5),
            3 => array(4, 3, 3),
            4 => array(3, 3, 3, 1),
            5 => array(2, 2, 2, 2, 2),
            6 => array(2, 2, 2, 2, 1, 1),
            7 => array(2, 2, 2, 1, 1, 1, 1),
            8 => array(2, 2, 1, 1, 1, 1, 1, 1),
            9 => array(2, 1, 1, 1, 1, 1, 1, 1, 1),
            10 => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
        );

        $x = array();
        if (in_array($columnsNum, array_keys($gridSys))) // check if the count of columns in-
        {                                                // -the same row is larger than the accepted count.
            array_walk($gridSys[$columnsNum], function($value, $key) use(&$x) {
                $x[] = 'form-builder-grid-' . $value;
            });
        }
        else
        {
            for ($i = 0; $i < $columnsNum; $i++)
            {
                $x[] = 'form-builder-grid-10';
            }
        }

        return $x;
    }

    // --------------------------------------------------------------------

    /**
     * Print the column content
     * 
     * @return string Column output
     */
    protected function _printColumnContent($rowNum, $colNum)
    {
        $arg   = func_get_args();
        
        $row   = $this->columnStorage[$rowNum];
        $col   = $this->columnStorage[$rowNum]['columns'][$colNum];
        $out   = "\n\t\t\t";
        $error = '';

        if ( ! isset($col['input']['method']) ) 
        {
            $i = 0;
            foreach ($col['input'] as $col_v)
            {
                // unset all keys except label,field.
                $tempCol = $col_v;
                unset($tempCol['rules']);

                foreach($tempCol as $key => $value)
                {
                    $idForLabel = $col_v['field']['field_name'].'_'.$i; // this var is used to set input id and 'for' attribue for 'label'.

                    if($key == 'inner_label')
                    {
                        if(in_array($col_v['field']['method'], array('radio', 'checkbox')))
                            $classLabel = "form-builder-radio-label";
                        else
                            $classLabel = "form-builder-input-label";
                        $out .= (!empty($col_v['inner_label'])) ? getInstance()->form->label($col_v['inner_label'], $idForLabel , " class='$classLabel' " ) : '';
                    }

                    if($key == 'field')
                    {
                        if(isset($col_v['field']['arguments'][3]))
                            $col_v['field']['arguments'][3] .= " id='$idForLabel' ";
                        $out .= $this->_printInput($col_v['field']);
                    }
                }

                if( ($i < 1 && in_array($col_v['field']['method'], array('radio','checkbox'))) || !in_array($col_v['field']['method'], array('radio','checkbox')) )
                    $error .= getInstance()->form->error($col_v['field']['field_name'], "\n\t\t\t\t<div class='form-builder-error' >", "</div>");

                $i ++;
            }
        }

        return array($out, $error);
    }

    // --------------------------------------------------------------------

    /**
     * Setting a value for 'inputs', $this->form->setValue()
     */
    protected function setValue()
    {
        $arg = (func_get_args());
        return call_user_func_array(array(getInstance()->form, 'setValue'), $arg);
    }

    // --------------------------------------------------------------------

    /**
     * Setting a value for 'radio', $this->form->setRadio()
     */
    protected function setRadio()
    {
        $arg = (func_get_args());
        return call_user_func_array(array(getInstance()->form, 'setRadio'), $arg);
    }

    // --------------------------------------------------------------------

    /**
     * Setting a value for 'radio', $this->form->setRadio()
     */
    protected function setSelect()
    {
        $arg = (func_get_args());
        return call_user_func_array(array(getInstance()->form, 'setSelect'), $arg);
    }

    // --------------------------------------------------------------------
    /**
     * Setting a value for 'checboxs', $this->form->setCheckbox()
     */
    protected function setCheckbox()
    {
        $arg = (func_get_args());
        return call_user_func_array(array(getInstance()->form, 'setCheckbox'), $arg);
    }

    // --------------------------------------------------------------------

    /**
     * Print the label of the column
     * 
     * @return string Column Label
     */
    protected function _printLabel()
    {
        $out = '';
        $arg = func_get_args();

        $row = $this->columnStorage[$arg[0]];
        $addon_class = (isset($row['position']['label'])) ? 'form-builder-label-' . $row['position']['label'] : 'form-builder-label-top';

        if(! empty($row['columns'][$arg[1]]['label']))
        {
            $out  = "<div class='form-builder-label-wrapper $addon_class'>";
            if(! empty( $row['columns'][$arg[1]]['rules'] ))
            {
                if( preg_match('/required/i', $row['columns'][$arg[1]]['rules']) )
                {
                    $out .= ' <span class=\'required-start\'>*</span> ';
                }
            }
            $out .= (isset($row['columns'][$arg[1]]['label'])) ? getInstance()->form->label($row['columns'][$arg[1]]['label']) : ' ';
            $out .= '</div>';
        }

        return $out;
    }

    // --------------------------------------------------------------------
    
    /**
     * Set rules for validat
     */
    protected function _setRules()
    {
        // inputs acceptable to apply rules
        $rulesJustFor = array('captcha','input','password','checkbox','radio','dropdown','multiselect','textarea');
        
        foreach($this->columnStorage as $row )
        {
            foreach($row as $key => $rowVars)
            {
                if($key == 'columns')
                {
                    foreach($rowVars as $colKey => $col )
                    {
                        if(is_array($col['input']))
                        {
                            $counter = 0; // inputs counter
                            $count = count($col['input']); // count of inputs per column
                            foreach($col['input'] as $input)
                            {
                                $colRules = '';
                                $rules = '';

                                if( ! empty($input['rules']) )
                                {
                                    $rules = $input['rules'];
                                }
                                
                                if( in_array($input['field']['method'], $rulesJustFor ) ) // if input acceptable to apply rules
                                {
                                    if( isset($col['rules']))
                                    {
                                        $colRules = $col['rules'];
                                        // unset($col['rules']);
                                    }
                                    $counter++; // increase the counter         
                                }

                                if(isset($colRules))
                                {
                                    $rules = $this->_distinctRules($rules, $colRules);
                                }

                                if( ! empty($this->schemaRules) )
                                {
                                    if( array_key_exists($input['field']['field_name'],$this->schemaRules) )
                                    {
                                        $sch_rule = $this->schemaRules[$input['field']['field_name']];
                                        
                                        $this->_distinctRules($rules, $sch_rule);
                                    }
                                }

                                if(! empty($rules))
                                {
                                    if( isset($input['inner_label']) && !( in_array($input['field']['method'], array('radio','checkbox','hidden')) ) )
                                    {
                                        $label = $input['inner_label'];
                                    }else{
                                        $label = $col['label'];
                                        $label = ( $count > 1 && !( in_array($input['field']['method'], array('radio','checkbox','hidden')) ) ) ? $label . " ($counter) " : $label;
                                    }
                                    getInstance()->form->setRules($input['field']['field_name'], $label, $rules);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function _distinctRules($rules, $mixWith)
    {
        $sch_rule = explode('|', $mixWith);
        if(empty($rules))
            $rules = '';

        if(! empty ($sch_rule) )
        {
            foreach($sch_rule as $rule)
            {
                if(! empty($rule))
                {
                    if( strpos($rules, $rule) === false )
                    {
                        $rules .= '|'.$rule;
                    }
                }
            }
        }
        return $rules;
    }

    // --------------------------------------------------------------------

    /**
     * Captcha
     * 
     * @return string
     */
    protected function _captchaBuild()
    {
        $args = func_get_args();
        $args = $args[0];
        
        $config = $this->config;
        $config = $config['captcha'];

        if( empty($config['hidden_input_name']) )
        {
            throw new Exception('Form Builder error : Captcha hidden_input_name must has been set in form_builder.php config file.');
        }
        
        if( is_callable($config['func']) )
        {
            $captcha = call_user_func_array(Closure::bind($config['func'], getInstance(), 'Controller'), array());

            // if( empty($captcha['hidden_input_template']) OR empty($captcha['image_url']) )
            if( empty($captcha['image_id']) OR empty($captcha['image_url']) )
            {
                throw(new Exception('Form builder error : Captcha closure in the config file must return an array containing two index keys (image_hidden_input, image_url).'));
            }

            $out = "\t<div class='form-builder-captcha-wrapper'>\n";

                $img_template =  ( empty($config['image_template']) ) ? '<img src="%s" data-id="%s" />' : $config['image_template'];

                $out.= "\t".sprintf($img_template, $captcha['image_url'], $captcha['image_id'])."\n";
                // $out .= '<img src="/membership/captcha_refresh" />';

                $out .= "<a href='javascript:;' id='captcha-refresh-{$this->_identifier}' data-do='refresh-captcha' class='form-builder-captcha-refresh' >Get a new security code</a>";
                $out .= "<div class='clear' ></div>";
                // captcha hidden field preceded by form _identifier
                $out.= "\t". call_user_func_array(array(getInstance()->form, 'hidden'), array($config['hidden_input_name'], $captcha['image_id']))."\n";
                $out.= "\t". call_user_func_array(array(getInstance()->form, 'input'), $args)."\n";

            $out.= "\t</div>\n";

            return $out;
        }
        else
        {
            throw new Exception('Form Builder error : Captcha key must be a callable function in form_builder.php config file.');
        }
    }

    // --------------------------------------------------------------------

    /**
     * Create a callback function
     * for validator
     * 
     * @param  string $func
     * @param  closure $closure
     * @return void
     */
    public function func($func,$closure)
    {
        getInstance()->form->func($func,$closure);
    }

    // --------------------------------------------------------------------

    public function setMessage($key, $val)
    {
        getInstance()->form->setMessage($key, $val);
    }

    /**
     * Get message from form object
     */
    public function getMessage()
    {
        return getInstance()->form->getMessage();
    }

    // --------------------------------------------------------------------

    public function setNotice($key, $val)
    {
        getInstance()->form->setNotice($key, $val);
    }

    // --------------------------------------------------------------------

    /**
     * Get notice from form object
     */
    public function getNotice()
    {
        return getInstance()->form->getNotice();
    }

    // --------------------------------------------------------------------

    /**
     * Get JS object
     */
    protected function _getJavascriptObject()
    {

    }

    // --------------------------------------------------------------------

    /**
     * set Option
     */
    protected function setOption($name, $value)
    {
        if(isset($this->options[$name]))
            $this->options[$name] = $value;
    }

}

// END Form Builder Class

/* End of file form_builder.php */
/* Location: ./packages/form_builder/releases/0.0.1/form_builder.php */