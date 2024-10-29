jQuery("#awp_background_color").val(jQuery("#awp_background_color").val());
jQuery("#awp_background_color").spectrum({
  color: jQuery("#awp_background_color").val(), // will it not mess up the chosen colors, if you are always doning this?
  preferredFormat: "hex6",
  clickoutFiresChange: true,
  showInput: true,
  allowEmpty:true,
  showInitial: true,
  chooseText: "Set Background Color",
  change: function(color,c) {
  }
});

  jQuery("#awp_section_color").val(jQuery("#awp_section_color").val());
  jQuery("#awp_section_color").spectrum({
  // color: jQuery("#sec_color").val(),
  color: jQuery("#awp_section_color").val(),
  preferredFormat: "hex6",
  clickoutFiresChange: true,
  showInput: true,
  allowEmpty:true,
  showInitial: true,
  chooseText: "Set Section Color",
  change: function(color,c) {
    // jQuery('.c_awp_section h1').css('color', jQuery(this).val());
  }
});

jQuery("#awp_headline_color").val(jQuery("#awp_headline_color").val());
jQuery("#awp_headline_color").spectrum({
  // color: jQuery("#hdl_color").val(),
  color: jQuery("#awp_headline_color").val(),
  preferredFormat: "hex6",
  clickoutFiresChange: true,
  showInput: true,
  allowEmpty: true,
  showInitial: true,
  chooseText: "Set Headline Color",
  change: function(color,c) {
    // jQuery('.c_awp_headline').css('color', jQuery(this).val());
  }
});
