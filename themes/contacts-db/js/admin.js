$(function(){ 
	var phone_fields = ['acf-field-mobile_phone', 'acf-field-home_phone', 'acf-field-business_phone', 'acf-field-other_phone'];
    phone_fields.forEach(function(field) {
        $('#' + field).inputmask("1 (999) 999-9999 [EXT:99999]");
    });
});