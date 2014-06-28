<script>
<%
	console.info(form);
	console.info(elements);
%>
<form enctype=" multipart/form-data" data-ajax="<%=form.ajax%>" id="<%=(form.id) ? form.id : 'hasnoid'%>" action="<%=(form.action) ? form.action : ''%>" method="post" role="form" <%=(form.attribute) ? form.attribute : ''%> >
	<%
	_.each(elements, function (value, index) {
		%>
	    <div class="row">
	        <div class="form-group col-sm-12 has-feedback">
	            <div class="col-sm-3"> 
	                <label class="control-label" for="name"><%=value.extra.label%></label>
	            </div>
	            <div class="col-sm-5">
	            	<%
	            	_.each(value.input, function (input, inputIndex){
	            		switch (input.type) {
	            			case 'text' : 
	            			case 'email' : 
	            			case 'password' : {
	            				%>
	            				<input type="<%=input.type%>"
	            				class="form-control input-sm"
	            				value="<%=(input.value) ? input.value : ''%>"
	            				data-validate="<%=(input.rules) ? input.rules : ''%>"
	            				name="<%=input.name%>"
	            				title="<%=input.title%>"
	            				<%=(input.attribute) ? input.attribute : ''%> >
	            				<%
	            				break;
	            			}
	            			case 'dropdown' : {
	            				%>
	            				<select 
	            				data-validate="<%=(input.rules) ? input.rules : ''%>" 
	            				class="form-control input-sm"
	            				name="<%=input.name%>"
	            				id="<%=input.name%>"
	            				<%=(input.attribute) ? input.attribute : ''%>
	            				title="<%=input.title%>" >
  									<%
  									_.each(input.option, function ( optV, optK ) {
  										var selected = '';
  										if(input.value == optK){ selected = "selected='selected'" }
  										%>
		  								<option <%=selected%> value="<%=optK%>"><%=optV%></option>
  										<%
		  							});
									%>
								</select>
	            				<%
	            				break;
	            			}
	            			case 'textarea' : {
	            				%><textarea class="form-control"
	            				data-validate="<%=(input.rules) ? input.rules : ''%>"
	            				name="<%=input.name%>"
	            				title="<%=input.title%>"
	            				<%=(input.attribute) ? input.attribute : ''%> ><%=input.value%></textarea><%
	            				break;
	            			}
	            			case 'radio' : 
	            			case 'checkbox' : {
	            				%>
	            				<div class="<%=input.type%>">
								<label>
									<input
									type="<%=input.type%>"
									name="<%=input.name%>"
									data-validate="<%=input.rules%>"
									title="<%=input.title%>"
									<%=(input.attribute) ? input.attribute : ''%>
									value="<%=input.value%>" />
									<%=input.title%>
								</label>
								</div>
	            				<%
	            				break;
	            			}
	            			case 'hidden' : {
	            				%>
	            				<input
									type="<%=input.type%>"
									name="<%=input.name%>"
									data-validate="<%=input.rules%>"
									<%=(input.attribute) ? input.attribute : ''%>
									value="<%=input.value%>" />
	            				<%
	            				break;
	            			}
	            			case 'file' : {
	            				%>
	            				<input
									type="<%=input.type%>"
									name="<%=input.name%>"
									data-validate="<%=input.rules%>"
									<%=(input.attribute) ? input.attribute : ''%>
									value="<%=input.value%>" />
	            				<%
	            				break;
	            			}
	            			case 'captcha' : {
	            				%>
	            				<input
									type="text"
									name="<%=input.name%>"
									class="form-control input-sm"
									data-validate="<%=input.rules%>"
									<%=(input.attribute) ? input.attribute : ''%>
									value="<%=input.value%>" />
	            				<%
	            				break;
	            			}
	            			case 'submit' : {
	            				%>
	            				<button
	            				type="<%=input.type%>"
	            				title="<%=input.title%>"
	            				<%=(input.attribute) ? input.attribute : ''%>
	            				class="btn btn-primary btn-sm" ><%=input.value%></button>
	            				<%
	            				break;
	            			}
	            			case 'button' : {
	            				%>
	            				<button
	            				type="<%=input.type%>"
	            				title="<%=input.title%>"
	            				<%=(input.attribute) ? input.attribute : ''%>
	            				class="btn btn-default btn-sm" ><%=input.value%></button>
	            				<%
	            				break;
	            			}
	            			case 'reset' : {
	            				%>
	            				<button
	            				type="<%=input.type%>"
	            				title="<%=input.title%>"
	            				<%=(input.attribute) ? input.attribute : ''%>
	            				class="btn btn-danger btn-sm" ><%=input.value%></button>
	            				<%
	            				break;
	            			}
	            			default : {
	            				console.error('Jform : wrong element type : ' + input.type)
	            			}
	            		}
	            	});
	            	%>
	            </div>
	        </div>
	    </div>
		<%
	});
	%>

</form>

</script>