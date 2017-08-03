Discount = {

	init: function() {
		this.typeHandler();
	},

	typeHandler: function(){
		$('select.type').change(function(event) {
			type = $(this).val();

			if(type == 'FIXED_AMOUNT'){
				$('.totalRatio').parents('.form-group').hide();
				$('.value').parents('.form-group').show();
			}
			else if(type == 'PERCENTAGE'){
				$('.totalRatio').parents('.form-group').show()
				$('.value').parents('.form-group').hide();
			}
		});
		$('select.type').trigger('change');

	},

}