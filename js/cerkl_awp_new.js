/**
* cerkl_awp.js v1.0.1
* Cerkl Automated Personalized Web.
*
* Copyright 2016, Cerkl
*
*/

cerkl_awp = function(params) {
	// var DOMAIN =  'http://staging-cerkl.gopagoda.io';
	var DOMAIN =  'https://cerkl.com';
	// var DOMAIN =  'http://localhost/cerkl';
	var VERSION = '1.0.1';
	var that;
	var attrs;
	var jQuery;
	var vid;
	var link = '<div class="c_awp_item_wrapper" style="width:{{image_width}}"><a href="{{href}}" class="c_awp_item" data-ue="{{ue}}"> <div style="margin-bottom:10px; position:relative;">{{image}}{{headline}}</div></a></div>'; //currently serves as an externally-unmodifiable wrapper
  var cookie_email = null,
	drop_name= 'Aptly&nbsp;{{display_name}}<img style="display: inline; float: right; width: 20px;" src="'  + DOMAIN + '/img/aptly_cog_32.png"/>';
	cookie_id = null;

	(function(){
		that = params.div;
		attrs  = {
			organization_id: null,
			wide: false,
			image_size: 'sm',
			image_type: 'sq',
			item_width: '300px',
			headline: {
				font_size: '.9em',
				font_weight: 'bolder'
			},
			for_you: {
				enabled: true,
				style: '<div class="c_awp_section c_awp_for_you"><h1 style="color:{{colors.section_color}}">Just For {{name}}</h1>{{content}}</div>',
				colors: {}
			},
			popular: {
				enabled: true,
				style: '<div class="c_awp_section c_awp_popular"><h1 style="color:{{colors.section_color}}">What\'s Trending</h1>{{content}}</div>',
				colors: {}
			},
			newest: {
				enabled: true,
				style: '<div class="c_awp_section c_awp_newest"><h1 style="color:{{colors.section_color}}">Latest</h1>{{content}}</div>',
				colors:{}
			},
			last: {
				enabled: true,
				style: '<div class="c_awp_section c_awp_last"><h1 style="color:{{colors.section_color}}">Recently Viewed</h1>{{content}}</div>',
				colors: {}
			},
			defaults: {
				image: '<div class="c_awp_story_img" style="background: url({{image_src}}); " alt=""></div>',
				headline: '<div style="color:{{colors.headline}};font-size:{{font_size}};font-weight:{{font_weight}};" class="c_awp_headline">{{headline}}</div>',
				colors: {
					headline: "#000000",
					accent: "#ffffff",
					section_color: "#000000"
				}
			},
			background: "#ffffff",
		};

		if (params && (typeof params === "object")){
			extend(attrs, params);
		}
	})();

	if (window.jQuery === undefined) {
		var script_tag = document.createElement('script');
		script_tag.setAttribute("type","text/javascript");
		script_tag.setAttribute("src",
			"http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
		if (script_tag.readyState) {
			script_tag.onreadystatechange = function () {
				if (this.readyState == 'complete' || this.readyState == 'loaded') {
					scriptLoadHandler();
				}
			};
		} else {
			script_tag.onload = scriptLoadHandler;
		}

		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
	} else {
		jQuery = window.jQuery;
		ready();
	}

	function scriptLoadHandler() {
		jQuery = window.jQuery.noConflict(true);
		ready();
	}
	function request_new_id(callback){
		var jsonp_url = DOMAIN + "/awp/" + VERSION + "/cerkl_awp.php?callback=?&action=nau";
		jQuery.ajax({
			url: jsonp_url,
			dataType: 'jsonp',
			error: function (jqXHR, textStatus) {
				callback(null);
			},
			success: function(response) {
				var to_return = null;
				if (response.cerkl_u_c == null || response.cerkl_u_c == undefined) {

				}else{
					cookie_id = response.cerkl_u_c;
					setCookie('cerkl_u_c', cookie_id, 365);
					to_return = cookie_id;
				}

				callback(to_return);
			}

		});

	}

	var win_log_interval = null;
	function buttonEvents(){
		if (cookie_email != null){
			jQuery('.c_awp_sign_in_mnu').hide();
			jQuery('.c_awp_user_mnu').show();
		}else{
			jQuery('.c_awp_sign_in_mnu').show();
			jQuery('.c_awp_user_mnu').hide();
		}

		if (attrs.email != null){
			// jQuery('.c_awp_btn_sign_out').parents('li').remove();
			// jQuery('.c_awp_sign_in_mnu').remove();
		}

		jQuery('.c_awp_btn_sign_out').click(function(e){
			attrs.email = null; // Karthik Added
			var jsonp_url = DOMAIN + "/awp/" + VERSION + "/cerkl_awp.php?callback=?&action=so";
			e.preventDefault();
			jQuery.ajax({
				url: jsonp_url,
				dataType: 'jsonp',
				error: function (jqXHR, textStatus) {

				},
				success: function(response) {
					jQuery('.c_awp_item').removeAttr('data-ue');
					// jQuery('#c_awp_dropname').text('Login');
					jQuery('.c_awp_sign_in_mnu').show();
					jQuery('.c_awp_user_mnu').hide();
					setCookie('cerkl_u_c', null, -1);
					setCookie('cerkl_e_c', null, -1);
					cookie_id = null;
					cookie_email = null;
					drop_name = 'Aptly&nbsp;{{display_name}}<img style="display: inline; float: right; width: 20px;" src="' + DOMAIN + '/img/aptly_cog_32.png" style="float: left;"/>';
					getData(true);
				}

			});
		})
		var open_dialog = function(uri, name, options, closeCallback) {
			var win = window.open(uri, name, options);
			win_log_interval = window.setInterval(function() {
				try {
					if (win == null || win.closed) {
						window.clearInterval(win_log_interval);
						closeCallback(win);
					}
				}
				catch (e) {
				}
			}, 1000);

			return win;
		};

		jQuery('.c_awp_btn_toggle_login').click(function(e){
			e.preventDefault();
			if (win_log_interval != null) window.clearInterval(win_log_interval);
			if (cookie_id == null){
				cookie_id = request_new_id(function(data){
					if (data==null){

					}else{
						open_dialog(DOMAIN + '/awp/account.php?cerkl_u_c=' + cookie_id, 'c_awp_win', 'width=550px,height=400px', function(win){
							getData(true);
						})
					}

				});
			}else{
				open_dialog(DOMAIN + '/awp/account.php?cerkl_u_c=' + cookie_id, 'c_awp_win', 'width=550px,height=400px', function(win){
					getData(true);
				});
			}

		})
		jQuery('.c_awp_btn_register').data('r_type', 'l');

		jQuery('.c_awp_btn_settings').click(function(e){
			jQuery(this).parents('.dropdown').toggleClass('open');
			e.stopPropagation();
		});

		jQuery(document).click(function(e){
			jQuery('.c_awp_btn_settings').parents('.dropdown').removeClass('open');
		})

		jQuery('.c_awp_btn_register').click(function(e){
			if (jQuery(this).text().trim()=='Register'){
				jQuery(this).data('r_type', 'r');
				jQuery('#c_awp_modal_login h1').text('Register Account');
				jQuery('#c_awp_p_full_name').fadeIn();
				jQuery('.c_awp_btn_submit').text('Create');
				jQuery(this).text('Already Registered')
			}else{
				c_awp_reset_login();
			}
			jQuery('.c_awp_modal input:visible:first').focus();
		});


		jQuery('.c_awp_btn_toggle').click(function(e){
			if (jQuery('.c_awp_modal-backdrop').length == 0){
				var backdrop = jQuery('<div class="c_awp_modal-backdrop fade"></div>');
				jQuery(document).find("body").append(backdrop);
			}
			jQuery('.c_awp_modal-backdrop').addClass('in');
			jQuery('#' + jQuery(this).attr('data-target')).addClass('in').show();
			jQuery('#' + jQuery(this).attr('data-target')).click(function(e){

				jQuery('.c_awp_modal').removeClass('in').hide();
				jQuery('.c_awp_modal-backdrop').removeClass('in');

			})

			jQuery('.c_awp_modal-dialog').click(function(e){
				e.stopPropagation();
			})

			jQuery('.c_awp_modal input:visible:first').focus();
		});


		jQuery('button[data-dismiss="c_awp_modal"]').click(function(e){
			jQuery('.c_awp_modal').removeClass('in').hide();
			jQuery('.c_awp_modal-backdrop').removeClass('in');

		})

		//called inside a JQuery ready context
		jQuery(".c_awp_item").not('.c_awp_hidden').click(function (e){
			var that = jQuery(this);
			var ue = jQuery(this).attr('data-ue');
			var url = jQuery(this).attr('href');
			if (ue.trim() != ""){
				e.preventDefault();
				jQuery.ajax({
					url: DOMAIN + "/awp/" + VERSION + "/cerkl_awp.php?callback=?&action=c&cerkl_ue=" + ue,
					dataType: 'jsonp',
					error: function (jqXHR, textStatus) {
						window.location.href=url;
					},
					success: function(data) {
						if (data.length>0){
							if(url.indexOf('?') !== -1){
								url += '&cerkl_ue=' + data;
							}else{
								url += '?cerkl_ue=' + data;
							}
						}
						window.location.href=url;
					}

				});
			}
			//alert(jQuery(this).find('.c_awp_headline').text() + ' was clicked;');

		});

		function resize_image(){
			var wrapper_width = jQuery('.c_awp_item_wrapper:first()').width();
			if (attrs.image_type == 'rect'){
				jQuery('.c_awp_item_wrapper .c_awp_story_img').css('height', wrapper_width * .65);
			}else{
				jQuery('.c_awp_item_wrapper .c_awp_story_img').css('height', wrapper_width );
			}
		}

		jQuery(window).resize(function(e){
			resize_image();
		})

		resize_image();
	}


	function getData(r){
		if (r==undefined) r = false;
		cookie_email = getCookie("cerkl_e_c");
		cookie_id = getCookie("cerkl_u_c");
		var thru_email = null;
		var thru_id = null;
		var jsonp_url;

		if(attrs.email != undefined){
			if(cookie_email !== null){
				if(cookie_email == attrs.email){
					thru_email = attrs.email;
					thru_id = cookie_id;
				} else {
					thru_email = attrs.email;
				}
			}else{
				if(cookie_id !== null){
					thru_email = attrs.email;
					thru_id = cookie_id;
				} else{
					thru_email = attrs.email;
				}
			}
		} else{
			if(cookie_id !== null){

				if(cookie_email !== null){
					thru_email = cookie_email;
					thru_id = cookie_id;
				} else{
					thru_id = cookie_id;
				}
			} else{
				//pass nothing
			}
		}

		var append="";
		if (thru_email !== null) append += "&email=" + thru_email;
		if (thru_id !== null) append += "&user_id=" + thru_id;

		var action = (r ? 'r' : 'v');

		if (attrs.organization_id !== null) append += "&organization_id=" + params.organization_id; else return;
		if (attrs.popular.enabled==false) append += "&popular=false";
		if (attrs.newest.enabled==false) append += "&newest=false";
		if (attrs.last.enabled==false) append += "&last=false";
		jsonp_url = DOMAIN + "/awp/" + VERSION + "/cerkl_awp.php?callback=?&action=" + action + append;
		//jsonp(jsonp_url);
		jQuery.ajax({
			url: jsonp_url,
			dataType: 'jsonp',
			error: function (jqXHR, textStatus) {
			},
			success: function(response) {
				process(response);
			}

		});
	}
	function setCookie(cname, cvalue, exdays) {
		//independent of jquery
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var append = "; expires="+ d.toUTCString() + "; path=/";
		document.cookie = cname + "=" + cvalue + "; " + append;
	}
	function getCookie(cname) {
		//independent of jquery
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length,c.length);
			}
		}
		return null;
	}
	function process(response){
		jQuery(new function(){
			if (response.error == undefined){
				jQuery(that).html('<div style="color:red">Request failed</div>');
			}else if (response.error > 0 ){
				jQuery(that).html('<div style="color:red">' + response.message + "</div>");
			}else{
				user_id = response.user_id;
				cookie_id = user_id;
				setCookie("cerkl_u_c",user_id,365);
				if(response.email!=undefined) {
					email = response.email;
					setCookie("cerkl_e_c", email,365);
					cookie_email = email;
				}
				var for_you_news = response.data.for_you;
				var popular_news = response.data.popular;
				var newest_news = response.data.newest;
				var last_news = response.data.last;
				var display_name = '';

				if (response.full_name != null && (response.full_name.split(' ')[0]).length > 0){
					if ((for_you_news == null || for_you_news.length == 0)){
						display_name = '<span style="display: inline-block;font-size: .8em;margin-right: 5px;">(' + (response.full_name.split(' ')[0]).trim() + ')</span>';
					}
				}else if (attrs.email != undefined && attrs.email != null && attrs.email.trim() != '') {
					display_name = '<span style="display: inline-block;font-size: .8em;margin-right: 5px;">(' + attrs.email.trim() + ')</span>';
				}

				vid = response.vid;
				jQuery(that).html('');
				jQuery(that).css("background-color",attrs.background);
				jQuery(that).css('visibility','hidden');
				var html = '';
				function section(object,spec_param){
					if (object != null && object.length > 0){
						var html_now = spec_param.style;
						var news ='';
						jQuery.each(object, function (i, item){
							var headline = spec_param.headline||attrs.defaults.headline;
							headline = headline.replace("{{headline}}", item.headline).replace('{{font_size}}', attrs.headline.font_size).replace('{{font_weight}}', attrs.headline.font_weight).replace('{{line_height}}', attrs.headline.line_height);
							var image = spec_param.image||attrs.defaults.image;
							var img = item.image_src;
							var img_width;
							if (img == null){
								img = "";
								image = image.replace("background: url({{image_src}});", "");
							}else{
								image = image.replace("{{image_src}}", img);
							}

							if (attrs.image_size == 'md'){
								img_width = '33%';
							}else if (attrs.image_size == 'lg'){
								img_width = '50%';
							}else{
								img_width = '25%';
							}

							news += link.replace("{{href}}", item.url).replace("{{image}}",image).replace("{{headline}}",headline).replace("{{ue}}", item.ue).replace(/{{image_width}}/g, img_width);
						});
						html_now = html_now.replace("{{content}}", news).replace(/{{colors.headline}}/g, spec_param.colors.headline||attrs.defaults.colors.headline);
						html_now = html_now.replace(/{{colors.section_color}}/g, spec_param.colors.section_color||attrs.defaults.colors.section_color).replace(/{{name}}/g,(response.full_name||"You").split(" ")[0]);

						drop_name = 'Aptly&nbsp;{{display_name}}<img style="display: inline; float: right; width:20px;" src="' + DOMAIN + '/img/aptly_cog_32.png"/>';

						return html_now;
					}
					return "";
				}

				html += section(for_you_news,attrs.for_you);
				html += section(popular_news,attrs.popular);
				html += section(newest_news,attrs.newest);
				html += section(last_news,attrs.last);

				if (html == '') {
									html += '<div class="c_awp_section c_awp_processing"><div class="c_awp_loading" style="border-color:{{colors.section_color}};border-top-color:{{colors.headline}};"></div><div style="color:{{colors.headline}}">Aptly is hard at work, personalizing this site\'s content. It may take up to an hour to get the personalization in place but you are going to love it!</div></div>';

									var spinner_bg = attrs.defaults.colors.section_color.trim();
									if (spinner_bg == attrs.defaults.colors.headline.trim()) spinner_bg = attrs.background;
									html = html.replace(/{{colors.headline}}/g,attrs.defaults.colors.headline);
									html = html.replace('{{colors.section_color}}',spinner_bg);
								}
				{
					var html_now; //popup logic
				}
				var wrapper = 'c_awp_wrapper';
				if (attrs.wide) wrapper += ' c_awp_wide';

				var modal_about = '<div id="c_awp_modal_about" class="c_awp_modal fade" tabindex="-1" role="dialog">' +
				'<div class="c_awp_modal-dialog">' +
				'<div class="c_awp_modal-content">' +
				'<div class="c_awp_modal-header">' +
				'<h4 class="c_awp_modal-title" style="text-align:center"><img src="'+ DOMAIN + '/img/aptly_head.png" style="max-height:40px"/></h4>' +
				'</div>' +
				'<div class="c_awp_modal-body" style="padding: 20px;text-align: center;">' +
				'<div class="c_awp_cerkl_login">' +
				'<p>' +
				'Add the power of personalization to your site in just 30 seconds with Aptly, powered by <a href="https://cerkl.com" target="_blank">Cerkl.</a>' +
				'</p>' +
				'</div>' +
				'</div>' +
				'<div class="c_awp_modal-footer">' +
				'<button type="button" class="btn btn-default" data-dismiss="c_awp_modal"> Close</button>' +
				'</div>' +
				'</div><!-- /.c_awp_modal-content -->' +
				'</div><!-- /.c_awp_modal-dialog -->' +
				'</div><!-- /.c_awp_modal -->';

				/* var dropdown = '<div ><div class="dropdown" style="float:right;  width: 93px; height:34px;"><a class="dropdown-toggle c_awp_btn_settings"  data-toggle="dropdown" style="margin-right:0px;  width: 92px; height:31px; border-bottom: 0;">';
				dropdown += '<span id="c_awp_dropname" style="display:inline-block; width: 78px; height: 32px;">' +  drop_name + '</span> <span class="caret"></span></a>' +
				'<ul class="dropdown-menu pull-right">' +
				'<li class="c_awp_sign_in_mnu" style="display:none;"><a href="#" class="c_awp_btn_toggle_login" data-target=\'c_awp_modal_login\'>Sign In</a></li>' +
				'<li class="divider c_awp_sign_in_mnu"></li>' +
				'<li><a href="#" class="c_awp_btn_toggle" data-target="c_awp_modal_about">About Aptly</a></li>' +
				'<li class="c_awp_user_mnu" style="display:none;"><a href="#" class="c_awp_btn_sign_out">Logout</a></li>' +
				'</ul>' +
				'</div></div>'; */

				var dropdown = '<div style="height:30px;"><div class="dropdown" style="float:right;"><a class="dropdown-toggle c_awp_btn_settings"  data-toggle="dropdown" style="margin-right:0px; border-bottom: 0; position: relative; text-transform: none;">';
				dropdown += '<span id="c_awp_dropname" style="display:inline-block;">' +  drop_name + '</span> <span class="caret"></span></a>' +
				'<ul class="dropdown-menu pull-right">' +
				'<li class="c_awp_sign_in_mnu" style="display:none;"><a href="#" class="c_awp_btn_toggle_login" data-target=\'c_awp_modal_login\'>Sign In</a></li>' +
				'<li class="divider c_awp_sign_in_mnu"></li>' +
				'<li><a href="#" class="c_awp_btn_toggle" data-target="c_awp_modal_about">About Aptly</a></li>' +
				'<li class="c_awp_user_mnu" style="display:none;"><a href="#" class="c_awp_btn_sign_out">Logout</a></li>' +
				'</ul>' +
				'</div></div>';

				//<li class="c_awp_sign_in_mnu"  style="display:none;"><a href="#">Facebook</a></li>
				//<li class="c_awp_sign_in_mnu" style="display:none;"><a href="#">Twitter</a></li>

				dropdown = dropdown.replace('{{display_name}}', display_name);
				var poweredby = '<div><div style="font-size: 10px; padding: 5px; border-top: 1px solid #eee;font-style: italic;text-align:right;">Powered by <a href="https://cerkl.com" target="_blank"><img src="' + DOMAIN + '/img/cerkl-64.png" style="width: 32px;"></a></div></div>'

				//var awp_settings = '<div><a href="#" class="c_awp_btn_settings"><i class="c_awp_icon c_icon_cog c_awp_icon_lg"></i></a></div>';
				jQuery(that).html('<div class="'+ wrapper +'">' + modal_about + dropdown + html + poweredby + '</div>');
				jQuery(that).css("visibility", "visible");

				//var url_no_cerkl_ue = remove_url_param("cerkl_ue", location.href);
				//window.history.replaceState('Object', 'Title', url_no_cerkl_ue );
				buttonEvents();
			}
		});
}

function remove_url_param(key, source_url) {
	var rtn = source_url.split("?")[0],
	param,
	params_arr = [],
	queryString = (source_url.indexOf("?") !== -1) ? source_url.split("?")[1] : "";
	if (queryString !== "") {
		params_arr = queryString.split("&");
		for (var i = params_arr.length - 1; i >= 0; i -= 1) {
			param = params_arr[i].split("=")[0];
			if (param === key) {
				params_arr.splice(i, 1);
			}
		}
		rtn = rtn + "?" + params_arr.join("&");
	}
	return rtn;
}


	/*
	function jsonp(url){
		var script = document.createElement('script');
		script.src = url;
		(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script);
	}*/
	function extend(a, b){
		for (var property in b) {
			if (b[property] && b[property].constructor && b[property].constructor === Object) {
				a[property] = a[property] || {};
				arguments.callee(a[property], b[property]);
			} else {
				a[property] = b[property];
			}
		}
		return a;
	}

	function ready(){
		getData();
	}

	window.onbeforeunload = function(){
		//xhttp = new XMLHttpRequest();
		var url = DOMAIN + "/awp/" + VERSION + "/cerkl_awp.php?action=l&vid=" + vid;

		jQuery.ajax({
			url: url,
			dataType: 'jsonp',
			error: function (jqXHR, textStatus) {

			},
			success: function(response) {
			}

		});

	}

	var styles = document.createElement('link');
	styles.setAttribute("rel","stylesheet");
	styles.setAttribute("type","text/css");
	styles.setAttribute("href", DOMAIN+"/awp/" + VERSION + "/cerkl_awp.css");
	(document.getElementsByTagName("head")[0] || document.documentElement).appendChild(styles);
	function preparePopup(){
		var html = '';
		jQuery(that).append(html);
	}
}
// jQuery('.c_awp_user_mnu').show();
