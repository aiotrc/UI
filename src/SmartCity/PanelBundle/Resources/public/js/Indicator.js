Indicator = {
	tableContainer: $('#indicator-table-container'),
	table: null,
	ajaxAllowed: true,

	message:{
		change_products_price: 'قیمت تمام محصولات به روز شوند ؟',
	},

	init: function(){
		this.table = this.tableContainer.find('> table');

		$('#save-indicators').click(function(event) {

			preparedData = Indicator.prepareData();

			if(preparedData){
				bootbox.dialog({
				    message: Indicator.message.change_products_price,
				    size: 'small',
				    onEscape: true,
				    buttons: {
				        confirm: {
				            label: Translator.trans('label.yes', {}, 'labels'),
				            className: "btn-sm blue",
				            callback: function () {
				            	Indicator.save(preparedData, true);
				            }
				        },
				        cancel: {
				            label: Translator.trans('label.no', {}, 'labels'),
				            className: "btn-sm grey",
				            callback: function () {
				            	Indicator.save(preparedData, false);
				            }
				        },
				    },
				});
			}
			
		});

		this.tableContainer.on('change', 'input[name=value]', function(event) {
			$(this).parents('tr').addClass('changed');
		});
	},

	save: function(data, updateProductsPrice){
   		
		this.ajaxAllowed = false;
		BackendFramework.loading(true, $('.page-content'), true);

		$.ajax({
			url: Routing.generate('panel_indicator_update'),
			type: 'POST',
			data: {
				indicators: data,
				updateProductsPrice: updateProductsPrice,
			},
		})
		.done(function(data) {
			if(data.status){
				BackendFramework.showNotif('success');
				setTimeout(function(){
					location.reload();
				}, 1000)
			}
		})
		.fail(function(data) {
			BackendFramework.showNotif('error');
		})
		.always(function() {
			Indicator.ajaxAllowed = true;
			// BackendFramework.loading(false, $('body'));
		});
	},

	prepareData: function(){
		var indicatorObj = [];
		var indicators = [];
		isValid = true;
		
		Indicator.table.find('tbody tr.changed:not(.cloneable-row)').each(function(index, el) {
			row = $(this);
			new_value = row.find('input[name=key]').val();
			orig_value = row.find('td[data-field=value]').attr('orig_value');

			if(new_value != orig_value){

				indicatorObj = {
			    	id : row.attr('data-id'),
			    	key : new_value,
			    	value : row.find('input[name=value]').val(),
				}

				indicators[index] = indicatorObj;
			}
		});

		if(indicators.length <= 0){
			BackendFramework.showNotif('info');
			return false
		}

		return indicators;
	}
}