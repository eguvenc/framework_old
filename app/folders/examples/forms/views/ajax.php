
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Ajax Form</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/welcome.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script type="text/javascript">
var ajax = {
    post : function(url, closure, params){
        var xmlhttp;
        xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                if( typeof closure === 'function'){
                    closure(xmlhttp.responseText);
                }
            }
        }
        xmlhttp.open("POST",url,true);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.send(params);
    },
    get : function() {
        // paste here
    }
}
function parseNode(obj, element, i) {

    var name = element.name;
    var fieldAlreadyHasError = document.getElementById(i + '_fieldError');

    if (typeof obj['success'] !== 'undefined' && obj['success'] == '0') {

        if(typeof obj.errors[name] !== 'undefined'){

            var elementType = element.getAttribute("type");

            if(fieldAlreadyHasError){

                if (element.parentNode.parentNode.className != 'checkbox' && element.parentNode.parentNode.className != 'radio') {
                    document.getElementById(i + '_fieldError').innerHTML = '<label class="control-label" for="'+element.name+'">' + obj.errors[name] + '</label>';
                }
                if (element.tagName == "INPUT" || element.tagName == "SELECT") {
                    element.parentNode.className += " has-error";
                }
                
            } else {

                var topClass = element.parentNode.parentNode.className;
                if (topClass == 'checkbox' || topClass == 'radio') {
                    if(/form\-group\s?/i.test(element.parentNode.parentNode.parentNode.className.toString())) {
                        removeErrorClass(i + '_fieldError', element, fieldAlreadyHasError);
                        element.parentNode.parentNode.parentNode.className += " has-error";
                    }
                } else {

                    e.innerHTML = '<label class="control-label" for="' + element.name + '">' + obj.errors[name] + '</label>';
                    element.parentNode.insertBefore(e, element.parentNode.children[0]);
                    // element.parentNode.appendChild(e);

                    if (element.parentNode.className == 'captcha' && /form\-group\s?/i.test(element.parentNode.parentNode.className.toString())) {
                        removeErrorClass(i + '_fieldError', element, fieldAlreadyHasError);
                        element.parentNode.parentNode.className += " has-error";
                    }
                    if(/form\-group\s?/i.test(element.parentNode.className.toString())) {
                        removeErrorClass(i + '_fieldError', element, fieldAlreadyHasError);
                        element.parentNode.className += " has-error";
                    }
                }
            }

        } else {

            removeErrorClass(i + '_fieldError', element, fieldAlreadyHasError);
        }

    } else {

        removeErrorClass(i + '_fieldError', element, fieldAlreadyHasError);
    }
}
function removeErrorClass(field, element, fieldAlreadyHasError){
    element.parentNode.className = element.parentNode.className.replace(/ has\-error/g,"");
    element.parentNode.parentNode.className = element.parentNode.parentNode.className.replace(/ has\-error/g,"");
    element.parentNode.parentNode.parentNode.className = element.parentNode.parentNode.parentNode.className.replace(/ has\-error/g,"");
    if (fieldAlreadyHasError) {
        document.getElementById(field).innerHTML = ""; 
    }
}
function submitAjax(formId){

    var myform = document.getElementById(formId);
    myform.onsubmit = function(){

        var elements = new Array();
        elements[0] = myform.getElementsByTagName('input');
        elements[1] = myform.getElementsByTagName('select');
        elements[2] = myform.getElementsByTagName('textarea');

        var elementsClass = document.getElementsByClassName('_fieldError');

        ajax.post( myform.getAttribute('action'), function(json){
            var obj = JSON.parse(json);
            
            if(typeof obj['success'] !== 'undefined' && obj['success'] == '1') {  // Success

                if(typeof obj['messages'] !== 'undefined'){

                    alert(obj['messages']);

                    return false;
                }
            }
            else  // Fail
            {
                for (var i = 0; i < elements.length; i++){
                    var elemets2 = new Array();
                        elemets2 = elements[i];
                    for(var ii = 0; ii < elemets2.length; ii++){
                        e = document.createElement('div');
                        e.className = '_fieldError';
                        errorInputNameId = i.toString() + ii.toString();
                        e.id = errorInputNameId + '_fieldError';
                        if (elemets2[ii].type != 'submit'){
                            if ( elemets2[ii].type != 'hidden') {
                                parseNode(obj, elemets2[ii], errorInputNameId);
                            }
                        }
                    }
                }
            }
            
            // Test results
            //
            //
            document.getElementById("response").children[0].innerHTML = json.toString();
        },
        new FormData(myform)
        );
        return false; // Do not do form submit;
    }
}
</script>
</head>

  <body>
  
    <div class="container">

      <div class="header clearfix">
        <?php echo $this->view->get('examples::form_navigation') ?>
        <h2 class="text-muted"><a href="/examples/">Ajax Form</a></h2>
      </div>

      <div class="content">

        <div id="formHtml">
           <form role="form" action="/examples/forms/ajax" method="POST" id="ajaxForm">
          
             <div class="form-group">
                <input type="email" name="email" value="" class="form-control" id="email" placeholder="Email">
             </div>

             <div class="form-group">
                <input type="password" name="password" class="form-control" id="pwd" placeholder="Password">
             </div>
            
            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control" id="pwd" placeholder="Confirm Password">
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label><input type="checkbox" name="agreement" value="1" id="agreement"> I agree terms and conditions</label>
                </div>
            </div>

            <div class="form-group <?php echo $this->form->getErrorClass('communicate') ?>">
              <p>How we communicate with you ?</p>
              <div class="radio">
              <label for="communicate1"><?php echo $this->element->radio('communicate', 'email', $this->form->setRadio('communicate', 'email'), ' id="communicate1" ') ?>&nbsp;By Email</label>
              <label for="communicate2"><?php echo $this->element->radio('communicate', 'phone', $this->form->setRadio('communicate', 'phone'), ' id="communicate2" '); ?>&nbsp;By Phone</label>
              </div>
            </div>

            <div class="form-group <?php echo $this->form->getErrorClass('hear') ?>">
              <p>Where did you hear about us ?</p>
              <?php
                echo $this->form->getErrorLabel('hear');
                $options = array(
                    '' => '',
                    'newspapers'  => 'Newspapers',
                    'google'  => 'Google',
                    'tv'  => 'Tv',
                    'radio'  => 'Radio',
                    'friend'  => 'A friend',
                );
                echo $this->element->dropdown('hear', $options, $this->form->getValue('hear'), ' class="form-control" ');
              ?>
            </div>
            <button type="submit" class="btn btn-default" onclick="submitAjax('ajaxForm');">Submit</button>
        </form>
        </div>

      </div>

      <hr />
        <section>
            <h3>Results</h3>
            <div id="response">
                <pre></pre>
            </div>
        </section>

      <!--
      <footer class="footer"></footer>
      -->

    </div> <!-- /container -->

  </body>
</html>