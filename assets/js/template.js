/**
 * _JS TEMPLATE LOADER.
 * @author Rabih Abou Zaid <rabihsyw@gmail.com>
 * Template plugin
 * 
 * Dependency : Jquery
 * 				_Underscore JS
 *
 *
 * @type Object
 */

var TEMPLATE = new function (){

	refreshTemplate = {};

	var params 	  = {
		ajax : COMMON.ajax
	};
	
	var templates = {};
	var jsonData = {};

	var _private  = {
		getIndex    : function (paramsToSend) {
			var index = 'null';
			if (typeof paramsToSend == 'object') {
				index = JSON.stringify(paramsToSend);
			}
			return index;
		},
		jsonDataGet : function (url_or_data, paramsToSend, options, forceGet) {
			if (! url_or_data ){
				return {};
			}

			var index = this.getIndex(paramsToSend);

			if(/string/i.test(typeof url_or_data)){
				if (! jsonData.hasOwnProperty(url_or_data) || ! jsonData[url_or_data].hasOwnProperty(index) || forceGet) {
					if (! jsonData[url_or_data]) {
						jsonData[url_or_data] = {};
					}
					jsonData[url_or_data][index] = COMMON.include.json(url_or_data, paramsToSend, options);
				}
				return jsonData[url_or_data][index];
			} else {
				return url_or_data;
			}
		}
	};
	
	var obj       = {
		refreshTemplate : function (name, refresh) {
			refreshTemplate[name] = (typeof refresh == 'boolean') ? refresh : false;
		},
		get : function (name) {
			if(templates[name] && !refreshTemplate[name]) {
				return templates[name];
			}
			
			var template = params.ajax(name);

			if(template) {
				templates[name] = template;
			}

			this.refreshTemplate(name, false);

			return templates[name];
		},
		getParsed: function (name, jsonD, paramsToSend, options, forceGet) {
			var tpl = this.get(name);
			
			jsonD   = _private.jsonDataGet(jsonD, paramsToSend, options, forceGet);

			if (tpl) {
				tpl = $(tpl).html();

				return _.template(tpl,jsonD);
			}
			return false;
		},
		parse : function (name, jsonD, target, paramsToSend, options, forceGet) {

			var res = this.getParsed(name, jsonD, paramsToSend, options, forceGet);

			if (res) {
				$('#'+target).html(res);
			}
		},
		parseGrid : function (name, jsonD, target, form, forceGet) {

			if (form) {

				if (forceGet && $('#'+form).reset) {
					$('#'+form).reset();
				}
				var x = $('#'+form).formToJson();
				x.pageNum = (x.pageNum) ? x.pageNum : 1;
				jsonD += '?' + $.param(x);

			}

			var res = this.getParsed(name, jsonD, x, {async : false, type : 'post'}, forceGet);

			if (res) {
				$('#'+target).html(res);
			}
		},
		setAjax   : function (ajaxFunc) {
			params.ajax = ajaxFunc;
		},
		init      : function () {

		}
	};

	return obj;
}();

TEMPLATE.init();