Product = {
	id: null,
	table : $('#diamond_search_table'),
	isReviewInitialized: false,

	filters:{
		diamond_type: null,
		diamond_carat: null,
		diamond_cut: null,
		diamond_clarity: null,
	},

	message:{
		enter_diamond_amount: 'تعداد نگین را وارد کنید',
		enter_diamond_setting_fee: 'فی مخراجی را وارد کنید',
		enter_diamond_carat: 'قیراط نگین را وارد کنید',
	},

	init: function (PRODCUT_ID) {
		this.id = PRODCUT_ID

		this.filtersHandler();
		$('.diamond_filter').change(Product.search);

		$(document.body).on('click', '.delete', function(event) {
			event.preventDefault();
			row = $(this).parents('tr');
			row_id = $(this).parents('tr').attr('data-id');
			entity = $(this).parents('table').data('entity');
			url = Routing.generate('panel_'+entity+'_delete', {'id': row_id });

			TableUtil.deleteRow(row, url);	
		});

		$(document.body).on('click', '.select-diamond', function(event) {
			event.preventDefault();
			row = $(this).parents('tr');

			diamondAmount = row.find('td[data-field=amount] input').val();
			diamondSettingFee = row.find('td[data-field=settingFee] input').val();
			diamondCarat = row.find('td[data-field=carat] input').val();

			if(diamondAmount == ''){
				BackendFramework.showNotif('warning', Product.message.enter_diamond_amount);
			}
			else if(diamondSettingFee == ''){
				BackendFramework.showNotif('warning', Product.message.enter_diamond_setting_fee);
			}
			else if(diamondCarat == ''){
				BackendFramework.showNotif('warning', Product.message.enter_diamond_carat);
			}
			else{
				Product.addDiamond(row);
			}
		});

		$(document.body).on('change', 'input[type=file]', function(event) {
			$('#product_image_table tbody .newImage').remove();

			for(i in event.target.files){
				loadImage(
	        		event.target.files[i],
		        	function (img) {
		        		$(img).addClass('img-responsive');

		        		row = $('#product_image_table thead .table-head').clone();
		        		row.addClass('newImage');
		        		row.attr('data-id', -1);

		        		row.find('[data-field=image]').html(img);
		        		row.find('[data-field=position]').empty();
		        		row.find('[data-field=action]').empty();
		        		row.find('[data-field=primary]').empty();
		        		row.find('[data-field=active]').empty();
		        		// row.find('[data-field=action]').html('<button class="delete btn red btn-sm"><i class="fa fa-times"></i> حذف </button>');
		        		
		        		$('#product_image_table tbody').prepend(row);

		        	},
		        	{maxHeight: 80} // Options
		    	);
			}
		});

		$('#product_image_table').on('ifChecked', 'input[name*=isPrimary]', function(event) {

			$('#product_image_table').find('input[name*=isPrimary]').not($(this)).iCheck('uncheck');
			// $(this).iCheck('check')
            // tableBody = groupCheckbox.parents('table').find('tbody');

            // if (groupCheckbox.is(':checked')) {
            //     tableBody.find('tr:not(.cloneable-row)')
            //         .addClass('selected')
            //         .find('.row-checkbox')
            //         .iCheck('check')
            //     ;
            // }
            // else{
            //     tableBody.find('tr:not(.cloneable-row)')
            //         .removeClass('selected')
            //         .find('.row-checkbox')
            //         .iCheck('uncheck')
            //     ;
            // }
        });

		$('a[data-target=#reviewData]').on('shown.bs.tab', function (e) {
			if(!Product.isReviewInitialized){
				Product.isReviewInitialized = true
				UserProductReview.init(null, Product.id);
			}
		});

		$('.product_submit').click(function(event) {

			if(Product.id){
				event.preventDefault();
				checkedCategories = [];

				var tree = $('#cetegories-wrapper').jstree(true);
				checkedCategories_Obj = tree.get_checked(true);

				for(i in checkedCategories_Obj){
					catId = checkedCategories_Obj[i].li_attr['data-id']
					checkedCategories.push(catId)
				}
				
				$('form[name=product]')
					.find('select[name="diamondTypes"], select[name="diamondCut"], select[name="diamondClarity"], select[name="reviewStatus"]').remove()
					.end()
					.append('<input name="product[categories]" class="hidden" value="'+checkedCategories+'">')
					.submit()
				;
			}
		});

		if(this.id){
			this.prepareCategoryTree();
		}
	},


	// diamonds
	filtersHandler: function(argument) {
		$('[name=diamondTypes]').change(function(event) {
			Product.filters.diamond_type = $(this).val();
		});

		$('[name=diamondCarat]').change(function(event) {
			Product.filters.diamond_carat = $(this).val();
		});

		$('[name=diamondCut]').change(function(event) {
			Product.filters.diamond_cut = $(this).val();
		});

		$('[name=diamondClarity]').change(function(event) {
			Product.filters.diamond_clarity = $(this).val();
		});
	},

	search: function() {
		
		// BackendFramework.loading(true, $('.page-content'), true);

		$.ajax({
			url: Routing.generate('panel_diamond_search'),
			type: 'POST',
			data: {
				filters: Product.filters,
			},
		})
		.done(function(data) {
			console.log(data);
			if(data['status']){
				Product.table.find('tbody').empty();

				for (i in data['diamonds']) {
					Product.addRow(data['diamonds'][i]);
				}
			}
			else{
				BackendFramework.showNotif('error')
			}
		})
		.fail(function() {
			BackendFramework.showNotif('error')
		})
		.always(function() {
			// BackendFramework.loading(false, $('.page-content'));
		})
	},

	addRow: function(data) {

		isExist = Product.ifDiamondAlreadyAdded(data.id);

		if(!isExist){
			row = Product.table.find('thead .table-head').clone();

			row.attr('data-id', data.id);
			row.find('[data-field=id]').html(data.id);
			row.find('[data-field=type]').html(data.type);
			row.find('[data-field=cut]').html(data.cut);
			row.find('[data-field=clarity]').html(data.clarity);
			row.find('[data-field=carat]').html('<input class="form-control"/>');
			row.find('[data-field=fee]').html(data.price);
			row.find('[data-field=amount]').html('<input class="form-control"/>');
			row.find('[data-field=settingFee]').html('<input class="form-control"/>');
			row.find('[data-field=action]').html('<button class="select-diamond btn btn-xs green icon-only"><i class="fa fa-plus"></i></button>');

			Product.table.find('tbody').append(row);
		}
	},

	addDiamond: function(diamond) {
		newDiamond = diamond.clone();

		newDiamond.attr('data-id', -1);
		currentIndex = $('#product_diamond_table').find('tbody tr').length;
		diamondId = newDiamond.find('[data-field=id]').text();
		id_elements = [
			'<input type="text" name="product[diamonds]['+currentIndex+'][id]" value="-1">',
			'<input type="text" name="product[diamonds]['+currentIndex+'][diamondId]" data-field="diamondId" value="'+diamondId+'">',
		].join('\n')

		newDiamond.find('[data-field=id]').html(id_elements);
		newDiamond.find('[data-field=amount] input').attr('name', 'product[diamonds]['+currentIndex+'][amount]');
		newDiamond.find('[data-field=settingFee] input').attr('name', 'product[diamonds]['+currentIndex+'][settingFee]');
		newDiamond.find('[data-field=carat] input').attr('name', 'product[diamonds]['+currentIndex+'][carat]');
		
		newDiamond.find('[data-field=action] .select-diamond i').toggleClass('fa-plus fa-times');
		newDiamond.find('[data-field=action] .select-diamond').toggleClass('select-diamond delete green red');

		$('#product_diamond_table').find('tbody').append(newDiamond);
		diamond.remove();
	},

	ifDiamondAlreadyAdded: function(id) {
		
		isMatch = false
		$('#product_diamond_table tbody tr').each(function(index, el) {
			console.log(id);
			productDiamondId = $(this).find('input[data-field=diamondId]').val();
			if(productDiamondId == id){
				isMatch = true;
				return false;
			}
		});

		if(isMatch == true){
			return 1;
		}
		
		return 0;
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
		Product.initTree();
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