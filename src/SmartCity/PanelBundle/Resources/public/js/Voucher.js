Voucher = {

	init: function() {
		this.typeHandler();
		this.customerHandler();
		this.productHandler();
		this.prepareCategoryTree();

		// $('#jahadPlatform_marketingbundle_voucher_submit').click(function(event) {
		// 	event.preventDefault();

		// 	var tree = $('#cetegories-wrapper').jstree(true);
		// 	checkedCategories_Obj = tree.get_checked(true);

		// 	for(i in checkedCategories_Obj){
		// 		catId = checkedCategories_Obj[i].li_attr['data-id']
		// 		$('form[name=jahadPlatform_marketingbundle_voucher]').append('<input name="jahadPlatform_marketingbundle_voucher[categories]['+i+']" class="hidden" value="'+catId+'">')
		// 	}
		// 	$('form[name=jahadPlatform_marketingbundle_voucher]').submit();

		// });
	},

	typeHandler: function(){
		$('select.type').change(function(event) {
			type = $(this).val();

			if(type == 'FIXED_AMOUNT'){
				$('.totalRatio').parents('.form-group').hide();
				$('.value').parents('.form-group').show();
				$('.maximumDiscountAmount').parents('.form-group').hide();
			}
			else if(type == 'PERCENTAGE'){
				$('.totalRatio').parents('.form-group').show()
				$('.value').parents('.form-group').hide();
				$('.maximumDiscountAmount').parents('.form-group').show();
			}
		});
		$('select.type').trigger('change');

	},

	customerHandler: function(){
		$(document).on('change ifChanged', '.publicCustomer', function(event) {
			if ($(this).is(':checked')) {
				$('.customers').parents('.form-group').hide();
			}
			else{
				$('.customers').parents('.form-group').show();
			}
		});

		$('.publicCustomer').trigger('ifChanged');
	},

	productHandler: function(){
		$(document).on('change ifChanged', '.publicProduct', function(event) {
			if ($(this).is(':checked')) {
				$('.products').parents('.form-group').hide();
				$('.category-container').hide();
			}
			else{
				$('.products').parents('.form-group').show();
				$('.category-container').show();
			}
		});
		$('.publicProduct').trigger('ifChanged');
	},

	// category
	prepareCategoryTree: function(initTree){
		
		$('#cetegories-wrapper ul').children('li').each(function(index, el) {
			parent_id = $(this).attr('data-parent');
			parent_el = $('#cetegories-wrapper ul').find('li[data-id='+parent_id+']');
			if(parent_el.children('ul').length == 0){
				parent_el.append('<ul></ul>');
			}
			$(this).appendTo(parent_el.children('ul'));
		});
		this.initTree();
	},

	initTree: function(){
		$('#cetegories-wrapper').jstree({
			core: {
				check_callback : true,
				// animation: true,
				themes : {
			      	variant : "large"
			    }
			},
			types: {
                default: {
                    icon: "fa fa-folder"
                }, 
                file: {
                    icon: "fa fa-file icon-state-warning icon-lg"
                }
            },
			checkbox: {
				keep_selected_style : false,
				tie_selection: false,
				three_state: false
			},
			search: {
				show_only_matches: true
			},
			plugins : [
				"search", "types", "checkbox"
			]
		});
		$('#cetegories-wrapper').jstree('open_all');

		var to = false;
		$('#cetegory-search').keyup(function () {
			if(to) { 
				clearTimeout(to); 
			}
			to = setTimeout(function () {
		    	var v = $('#cetegory-search').val();
		    	$('#cetegories-wrapper').jstree(true).search(v);
			}, 250);
		});
	},

}