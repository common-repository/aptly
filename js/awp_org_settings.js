jQuery(function(e){

	jQuery("#awp_no_main_border").click(function(e){
		if (jQuery(this).is(':checked')){
			jQuery('.c_awp_wrapper').css('border-color', 'transparent')
		}else{
			jQuery('.c_awp_wrapper').css('border-color', jQuery('#awp_border_color').val());
		}
	})

	jQuery("#awp_background_color").val('#ffffff');
	jQuery("#awp_background_color").spectrum({
		color: "#ffffff",
		preferredFormat: "hex",
		clickoutFiresChange: true,
		showInput: true,
		allowEmpty:false,
		showInitial: true,
		chooseText: "Set Background Color",
		change: function(color,c) {
			jQuery('#cerkl_awp_container').css('background-color', jQuery(this).val());
		}
	});

	jQuery('#cerkl_awp_container').css('background-color', 'transparent');
	jQuery('#awp_background_color').parents('.awp_color_section').find('.sp-light').hide();
	jQuery('#awp_switch_color').change(function(e){
		if (jQuery(this).is(':checked')){
			jQuery('#awp_background_color').parents('.awp_color_section').find('.sp-light').show();
			jQuery('#cerkl_awp_container').css('background-color', jQuery("#awp_background_color").val());
		}else{
			jQuery('#awp_background_color').parents('.awp_color_section').find('.sp-light').hide();
			jQuery('#cerkl_awp_container').css('background-color', 'transparent');
		}
	})


	jQuery("#btn_awp_code").click(function(e){
		var item_width, image_height, font_size, font_weight, line_height;
 		var bg_color = 'transparent';
		if (jQuery('#awp_switch_color').is(':checked')){
			bg_color = jQuery("#awp_background_color").val();
		}

		item_width = parseInt(jQuery('#item_width').val());
		if (item_width==0) item_width=300;

		image_height = parseInt(jQuery('#image_height').val());
		if (image_height==0) image_height=200;

		font_size = parseInt(jQuery('#slt_font_size').val());
		if (font_size==0) font_size=14;

		font_weight = jQuery('#slt_font_weight').val();
		if (font_weight != "lighter" && font_weight != "normal" && font_weight != "bolder") font_weight="bolder";

		line_height = parseInt(jQuery('#line_height').val());
		if (line_height==0) line_height=19;

		var awp_code = jQuery('.awp_code').html();

		awp_code = awp_code.replace('{{background_color}}', bg_color);
		awp_code = awp_code.replace('{{headline_color}}', jQuery('#awp_headline_color').val());
		awp_code = awp_code.replace('{{section_title_color}}', jQuery('#awp_section_color').val());

		awp_code = awp_code.replace('{{item_width}}', item_width);
		awp_code = awp_code.replace('{{image_height}}', image_height);
		awp_code = awp_code.replace('{{font_size}}', font_size);
		awp_code = awp_code.replace('{{font_weight}}', font_weight);
		awp_code = awp_code.replace('{{line_height}}', line_height);


		jQuery('.awp_code_snip').html(awp_code);
		jQuery('#modal_awp_code').modal('show');
	})


	jQuery('.c_awp_wrapper .dropdown-menu a').click(function(e){e.preventDefault();})




	jQuery('.awp_layout_select').click(function(e){
		if (jQuery(this).find('img').hasClass('layout_selected')) return false;
		jQuery('.awp_settings').find('.awp_layout_select img').removeClass('layout_selected');
		jQuery(this).find('img').addClass('layout_selected');
		toggle_layout(jQuery(this).attr('data-layout'));
	})

	jQuery('#item_width').keyup(function(e){
		jQuery(this).trigger('change');
	})

	jQuery('#item_width').change(function(e){
		var width = parseInt(jQuery(this).val());
		if (width==0) return false;
		width = width + 'px';
		jQuery('.c_awp_wrapper .c_awp_item_wrapper').css({'max-width':width,'min-width':width});
	});

	jQuery('#image_height').keyup(function(e){
		jQuery(this).trigger('change');
	})

	jQuery('#image_height').change(function(e){
		var height = parseInt(jQuery(this).val());
		if (height==0) return false;
		height = height + 'px';
		jQuery('.c_awp_wrapper .c_awp_story_img').css({'height':height});
	});

	jQuery('#slt_font_size').change(function(e){
		var font_size = parseInt(jQuery(this).val());
		if (font_size==0) return false;
		jQuery('#line_height').val(font_size + 1);
		jQuery('#line_height').attr('min', font_size);
		font_size = font_size + 'px';
		jQuery('.c_awp_wrapper .c_awp_headline').css({'font-size':font_size});
	});

	jQuery('#slt_font_weight').change(function(e){
		var font_weight = jQuery(this).val();
		if (font_weight != "lighter" && font_weight != "normal" && font_weight != "bolder") font_weight="bolder";
		jQuery('.c_awp_wrapper .c_awp_headline').css({'font-weight':font_weight});
	});

	jQuery('#line_height').keyup(function(e){
		jQuery(this).trigger('change');
	})
	jQuery('#line_height').change(function(e){
		var line_height = parseInt(jQuery(this).val());
		if (line_height==0) return false;
		var font_size = parseInt(jQuery('#slt_font_size').val());
		if (line_height < font_size) {
			jQuery(this).val(font_size);
			jQuery(this).trigger('change');
			return false;

		}
		line_height = line_height + 'px';
		jQuery('.c_awp_wrapper .c_awp_headline').css({'line-height':line_height});
	});

	jQuery('#toggle_sample').click(function(e){
		e.preventDefault();
		jQuery('.cerkl_awp_sample .well').toggle();
	})


	function toggle_layout(layout){

		jQuery('.cerkl_awp_sample').toggleClass('col-md-8 col-md-12');
		jQuery('.cerkl_awp_preview').toggleClass('col-md-4 col-md-12');
		jQuery(window).trigger('resize');
	}

})
