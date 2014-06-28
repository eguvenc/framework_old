<script type="text/template" class="template">

	<form id="<%= formData.formId %>" data-ajax="<%= formData.ajax %>" class="form-horizontal register-form" style='width:80%; margin:0' role="form" method="post" action="<%- formData.postUrl %>" novalidate="novalidate">
		<% _.each( formData.inputs, function( input ){

			switch(input.type) { 
				case 'textbox':
		%>
					<div class='row'>
						<div class='form-group col-sm-12 has-feedback'>
							<div class='col-sm-3'> 
								<label for="<%- input.name %>" class="control-label"> <%- input.label %></label>
								<span class="form-required"> *</span>
							</div>
							<div class='col-sm-6'  data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
								<input type="text" data-validate="<%= input.rules %>" name="<%- input.name %>" value="<%- input.value %>" id="<%- input.name %>" <%= input.attr %> /> 
							</div>
						</div>
					</div>
	  	<% 
  				break; 
  				case 'password': 
		%>
				  	<div class='row'>
						<div class='form-group col-sm-12 has-feedback'>
							<div class='col-sm-3'> 
								<label for="<%- input.name %>" class="control-label"> <%- input.label %></label>
								<span class="form-required"> *</span>
							</div>
							<div class='col-sm-6' data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
								<input type="password" name="<%- input.name %>" id="<%- input.name %>" value="<%- input.value %>" <%= input.attr %>  /> 
							</div>
						</div>
					</div>
	  	<% 
		  		break;
		  		case 'dropdown':
  		%>
		  			<div class='row'>
						<div class='form-group col-sm-12 has-feedback'>
							<div class='col-sm-3'> 
								<label for="<%- input.name %>" class="control-label"> <%- input.label %> </label>
								<span class="form-required"> *</span>
							</div>
							<div class='col-sm-6' title="<%- input.title %>">
								<select name="<%- input.name %>" id="<%- input.name %>" <%= input.attr %>>

  		<%
  									_.each(input.dataDropdown, function ( value, key ) {
  										var selected;
  										if(input.value == key){ selected = "selected='selected'" }
  		%>
		  								<option <%= selected %> value = "<%- key %>"><%- value %></option>
  		<%
		  							})
		%>
								</select>
							</div>
						</div>
					</div>
		<% 
				break;
				case 'groupDropdown' :
		%>
					<div class='row'>
						<div class='form-group col-sm-12 has-feedback'>
							<div class='col-sm-3'> 
								<label for="birthday" class="control-label"> <%- input.label %> </label>
								<span class="form-required"> *</span>
							</div>
							<div class='col-sm-6 date-container'  data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
								
								<% _.each(input.groupDropdown, function ( value ) { %>
									<select name="<%- value.name %>" <%= value.formAttr %> >
										<option value=""> <%- value.firstOption %></option>
										<% _.each(value.dataDropdown, function ( row, key ) { var selected = ""; %>
										<% if(value.value == key){ selected = "selected='selected'" } %>
											<option <%= selected %> value="<%- key %>"> <%- row %></option>
										<% }) %>
									</select>

								<% }) %>
							</div>
						</div>
					</div>
		<%
				break;
				case 'subheader' : 
		%>
				<br clear="all"><h3 class="form-sectionTitle"><%= input.data %></h3>
		<%
				break;
				case 'verify' : 
		%>
					<div class='row form-pb25'>
						<div class='form-group col-sm-12 has-feedback'>
							<div class='col-sm-3'> 
								<label for="<%- input.name %>" class="control-label"><%- input.label %></label>
								<span class="form-required"> *</span>
							</div>
							<div class='col-sm-6'>
								<input type="text" name="<%- input.name %>" value="" id="<%- input.name %>"  class="form-control input-sm validation" /> 
								<input type="button" value="<%- input.bttn_value %>" id="verifybtn" class="" name="verifybtn" focused="0" onClick="verify()" />
							</div>
						</div>
					</div>
		<%
				break;
				case 'captcha' :
		%>
				<div class='row'>
					<div class='form-group col-sm-12 has-feedback'>
						<div class='col-sm-3'> 
							<label for="<%= input.name %>" class="control-label"> <%- input.label %></label>
							<span class="form-required"> *</span>
						</div>
						<div class='col-sm-3'>
							<input type="text" name="<%= input.name %>" value="" id="<%= input.name %>"  class="form-control input-sm capthca_input" />
						</div>
						<div class='col-sm-2'>
							<img src="/tutorials/hello_captcha_create" id="captchaImg" class="captcha_img"> 
						</div>
						<div class='col-sm-4'>
							<a href="#" class="capthca_link" onclick="refreshCaptcha();return false">Yeni bir güvenlik kodu al</a>
						</div>

					</div>
				</div>

		<%
				break;
				case 'checkbox' :
		%>

				<div class='row'>
					<div class='form-group col-sm-12 has-feedback'>
						<div class='col-sm-1'>
						      <input type="checkbox" value="<%= input.value %>" name="<%= input.name %>" id="<%= input.name %>">
						</div>
						<div class='col-sm-11'> 
							<label class="control-label" for="<%= input.name %>">
								<%= input.label %>
							</label>
						</div>
					</div>
				</div>

		<%
				break;
				case 'hidden' :
		%>
				<input type="hidden" name="<%- input.name %>" value="<%- input.value %>" id="<%- input.name %>"  /> 
		<%
				break;
			}
		%>

		<% }) %>
		
		<div class="register-submit">
		      <input type="submit" value="Kaydol" id="submitbtn" class="" name="submitbtn" focused="0" />
		</div>

	</form>
</script>