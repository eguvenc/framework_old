/**
 * Obullo Framework.
 * @author Rabih Abou Zaid <rabihsyw@gmail.com>
 * Template plugin
 * 
 * Dependency : Jquery
 *
 * @type Object
 */

var COMMON = new function () {
	_com_params  = {};
	
	_com_private = {};
	
	com_obj      = {
		cookie : function () {
			var _cookie_params  = {
				args 	: {},
				cookie  : '',
				exdays  : 2
			};
			var _cookie_private = {
				breakCookie : function () {
					var ca = document.cookie.split(';');
					if (ca.length > 0) {
						var d = new Date();
						d.setTime(d.getTime()+(_cookie_params.exdays*24*60*60*1000));

						for(var i in ca){
							var c = ca[i].trim().split('=');
							if (c[0] && c[1]) {
								try {
									var colName = c[0].trim();
									var newE = {};

									newE.value = c[1].trim();
									newE.expires = d.toGMTString();
									newE.path = '/';

									_cookie_params.args[colName] = newE;

								} catch (e) {
									console.error(e);
								}
							}
						}
					}
				},
				argsToCookie : function (name, arg) {
					var str = '';

					str = name + '=' + arg.value + ';' + 'expires=' + arg.expires + ';' + 'path=' + arg.path;

					return str;
				}
			};
			var cookie_obj      = {
				set : function(cName,cValue, expires, path){
					try {
						if (! _cookie_params.args[cName]) {
							_cookie_params.args[cName] = {};
						}
						
						var d = new Date();
						d.setTime(d.getTime()+(_cookie_params.exdays*24*60*60*1000));
						
						_cookie_params.args[cName].value = cValue;
						_cookie_params.args[cName].path = (path) ? path : '/';
						_cookie_params.args[cName].expires = (expires) ? expires : d.toGMTString();
					} catch (e) {
						console.error(e);
					}
				},
				get : function(cName){
					return (_cookie_params.args[cName]) ? _cookie_params.args[cName].value : false;
				},
				commit : function () {
					for (var i in _cookie_params.args) {
						var _cookie = '';
						_cookie = _cookie_private.argsToCookie(i, _cookie_params.args[i]);
						document.cookie = _cookie;
					}
				},
				init   : function () {
					_cookie_private.breakCookie();
				}
			};
			return cookie_obj;
		}(),
		localStorage    : {
			set : function(itemName,itemValue, expirationMin){
				var expirationMS = ((expirationMin) ? expirationMin : 24) * 60 * 1000;

				if (! COMMON.isJson(itemValue)){
					try {
						itemValue = $.parseJSON(itemValue);
					} catch (e) {
						console.error('Wrong string format, couldn\'t be parsed as JSON.');
						console.error(e);
						return;
					}
				}

				itemValue['timestamp'] = new Date().getTime() + expirationMS;
				localStorage.setItem(itemName, JSON.stringify(itemValue));

			},
			
			get : function(itemName){
				itemValue = localStorage.getItem(itemName);
				try {
					itemValue = $.parseJSON(itemValue);
					if (COMMON.isJson(itemValue)) {
						var dt = new Date();

						if (itemValue.timestamp && dt.getTime() < itemValue.timestamp) {
							return itemValue;
						}
					}
				} catch(e) {
					return false;
				}

				return false;
			},
			erase : function(itemName) {
				localStorage.removeItem(itemName);
			},
		},
		JQueryAjax : function (url, data, options, afterClosure ) {
			$.ajax({
				type: (!COMMON.isEmpty(options) && options.type) ? options.type : 'GET',
				url: url,
				// beforeSend: function(xhr){
			 //       xhr.withCredentials = true;
			 //    },
				crossDomain : (!COMMON.isEmpty(options) && options.crossDomain) ? options.crossDomain : false,
				// headers: {'X-Requested-With': 'XMLHttpRequest'},
				data: (COMMON.isEmpty(data)) ? {} : data

			}).always(function(res) {
				if (typeof afterClosure == 'function') {
					afterClosure(res);
				}
			});
		},
		ajax      : function (url, data, options, afterClosure) {
			var _ajax_params = {
				url      : url,
				type  	 : (options && options.hasOwnProperty('type') && /(get|post){1}/i.test(options.type)) ? options.type : 'GET',
				async 	 : (options && options.async && typeof options.async == 'boolean') ? options.async : false,
				response : false,
			};

			var privateF = {
				data : function (data, type) {
					var args = '';
					if (/get/i.test(type)) {
						
						if (typeof options == 'object' && options.cached) {
							args = '?_=null';
						} else {
							args = '?_='+Math.floor(Math.random() * 100000 + 1);
						}

						for (key in data) {
							args += '&'+key+'='+data[key];
						}
					} else {
						args = new FormData();
						for (key in data) {
							args.append(key, data[key]);
						}
					}
					return args;
				}(data, _ajax_params.type),
			};

			var obj = {
				initRequest : function () {

				    if (window.XMLHttpRequest) {
				        _ajax_params.xmlhttp = new XMLHttpRequest(); /* code for IE7+, Firefox, Chrome, Opera, Safari */
				    } else {
				        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); /* code for IE6, IE5 */
				    }
				}(),
				send     : function () {
					
					_ajax_params.xmlhttp.onreadystatechange = function(){

				        if (_ajax_params.xmlhttp.readyState==4 && _ajax_params.xmlhttp.status==200) {
				            _ajax_params.response = _ajax_params.xmlhttp.responseText;
				            if (typeof afterClosure == 'function') {
				            	try{
				            		_ajax_params.response = $.parseJSON(_ajax_params.response);
				            	}catch(e){

				            	}
				            	afterClosure(_ajax_params.response);
				            }
				            COMMON.always(_ajax_params.response);
				        } else if (_ajax_params.xmlhttp.status==404){
				        	throw 'error not found';
				        }
				    };

					if (/get/i.test(_ajax_params.type)) {

						_ajax_params.xmlhttp.open(_ajax_params.type,_ajax_params.url + privateF.data,_ajax_params.async);

					    _ajax_params.xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						
						/* xmlhttp.timeout = 10000; */

					    _ajax_params.xmlhttp.send();

					} else {

						_ajax_params.xmlhttp.open(_ajax_params.type,_ajax_params.url,_ajax_params.async);
					    _ajax_params.xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

					    _ajax_params.xmlhttp.send(privateF.data);
					}
				}(),
			};

			obj.initRequest;
			try {
		    	obj.send;
			} catch (s) {
				console.warn('not found ya');
				console.warn(s);
			}

		    return _ajax_params.response;
		},
		include   : {
			json : function(url, data, options) {

				var res = COMMON.ajax(url, data, (options) ? options : {async : false});

				try {
					res = $.parseJSON(res);
				} catch (e) {
					res = res;
				}

				return res;
			},
			
			js : function(url){
				var script = document.createElement('script');
				script.onload = function() {
				  return true;
				};
				script.src = url;
				script.type = "text/javascript";
				script.language = "javascript";
				document.getElementsByTagName('head')[0].appendChild(script);		
			},
			
			css : function(url){
			
			}
		},
		refreshContent   : function (args) {
			var targetEl = '';
			if (args.target && typeof args.target == 'object' && args.target.length > 0) {
				targetEl = $(args.target);
			} else if (args.targetId && typeof args.targetId == 'string' && args.targetId.length > 0) {
				targetEl = $('#'+args.targetId);
			}

			if (targetEl.length > 0 && args.url.length > 0) {
				
				targetEl.css({position : 'relative'});
				targetEl.append($(document.createElement('div')).addClass('loading-cover').css('display', 'block'));

				var res = COMMON.ajax(args.url, (args.data) ? args.data : {}, (args.options) ? args.options : {}, function (res) {
					targetEl.find('.loading-cover').fadeOut().remove();
				});

				targetEl.html(res);
			}
		},
		appendContent    : function (args) {
			var targetEl = '';
			if (args.target && typeof args.target == 'object' && args.target.length > 0) {
				targetEl = $(args.target);
			} else if (args.targetId && typeof args.targetId == 'string' && args.targetId.length > 0) {
				targetEl = $('#'+args.targetId);
			}

			if (targetEl.length > 0 && args.url.length > 0) {
				
				targetEl.css({position : 'relative'});
				targetEl.append($(document.createElement('div')).addClass('loading-cover').css('display', 'block'));

				var res = COMMON.ajax(args.url, (args.data) ? args.data : {}, (args.options) ? args.options : {}, function (res) {
					targetEl.find('.loading-cover').fadeOut().remove();
				});

				targetEl.append(res);
			}
		},
		always    : function (response) {
			try {
				if (response) {
					try {
						response = $.parseJSON(response);
					} catch (e_parse) {

					}
					console.log('COMMON.always : has been called');
				}
			} catch (e) {
				console.error(e);
			}
		},
		isEmpty   : function (obj) {
			return $.isEmptyObject(obj);
		},
		isJson    : function (data) {
			var isJson = false;
		    try {
		       var json = $.parseJSON(data);
		       isJson = (typeof json == 'object' && json != null) ? true : false;
		    } catch (ex) {
		        isJson = (typeof data === 'object') ? true : false ;
		    }
		    return isJson;
		},
		count     : function (_obj) {
			var count = 0;
			for (var i in _obj) {
				count++;
			}
			return count;
		},
		init      : function () {

			COMMON.cookie.init(); 			/* 1 - must init cookie first, because it is used it all other objects */

			$(document).ready(function(){

			});
		}
	};

	return com_obj;
}();

COMMON.init();

/**
 * A plugin used to conver a form to json format.
 * @return Json
 */
$.fn.formToJson = function()
{
    var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
	    if (o[this.name] !== undefined) {
	        if (!o[this.name].push) {
	            o[this.name] = [o[this.name]];
	        }
	        o[this.name].push(this.value || '');
	    } else {
	        o[this.name] = this.value || '';
	    }
	});
	return o;
};
