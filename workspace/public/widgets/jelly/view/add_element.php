<!DOCTYPE html>
<html>
	<head>
		<title>Add Form Element</title>
		<meta charset="utf-8">

		<link rel="stylesheet" href="/assets/jelly/css/bootstrap.min.css">
		<link rel="stylesheet" href="/assets/jelly/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="/assets/jelly/css/style.css">
		
		<script type="text/javascript">
			function dropdownAction(obj) {

	            var saveForm = (saveForm) ? saveForm : document.saveForm;

	            if (obj.value == 'submit' || obj.value == 'reset') {
	            	saveForm.label.setAttribute('disabled', 'disabled');
	            } else {
	            	saveForm.label.removeAttribute('disabled');
	            }

	            if (obj.value == 'captcha') {
	                saveForm.name.value = 'captcha';
	                saveForm.name.setAttribute('disabled', 'disabled');
	                saveForm.attribute.value = 'id="captcha"';
	                saveForm.rules.value = 'required';

	                var data = {};
					data['id']    = 'captcha';
					data['type']  = 'hidden';
					data['name']  = 'name';
					data['value'] = 'captcha';
	            	var inputData = createInput(data);

	                saveForm.appendChild(inputData);
	            } else {
	                var hiddenInput = document.getElementById('captcha');
	                if (hiddenInput) {
	                    hiddenInput.remove();
	                	saveForm.name.removeAttribute('disabled');
	                }
	            }
	        }
	        function groupDropdown(obj)
	        {
				var groupOrder = document.getElementById('groupOrder');
                if (saveForm.group_id !== undefined && groupOrder.style.display == 'none') {
					groupOrder.style.display = 'block';
					groupOrder.style.display = 'block';
					groupOrder.focus();
					groupOrder.scrollIntoView();
					console.log(groupOrder);
					saveForm.group_order.value = saveForm.group_id.getAttribute('data');
					saveForm.group_order.setAttribute('disabled', 'disabled');
                } else {
                	var groupOrder = document.getElementById('groupOrder');
                	if (groupOrder) {
	                	groupOrder.style.display = 'none';
	                	saveForm.group_order.removeAttribute('disabled');
	                }
                }
	        }
	        function createInput(data)
	        {
	        	var i = document.createElement("input");
	                i.setAttribute('type', data['type']);
	                i.setAttribute('name', data['name']);
	                i.setAttribute('id', data['id']);
	                i.setAttribute('value', data['value']);

                return i;
	        }
		</script>
	</head>
	<body>
		<div class="container">
			<div class="row">

    			<?php echo $this->view->load('list_elements', false); ?>

				<div class="page-header"><h4>Add Form Element</h4></div>
				<?php echo $this->form->getMessage($this->flash); ?>
				<hr>

				<?php echo $formData; ?>

			</div>
		</div>
	</body>
</html>