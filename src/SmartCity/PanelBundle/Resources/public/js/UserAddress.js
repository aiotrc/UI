UserAddress = {
	userId: null,
	tableContainer: $('#address-table-container'),
	table: null,
	ajaxAllowed: true,
	provinceSuggestionEngine: null,
	citySuggestionEngine: null,

	message:{
		no_address: 'آدرسی وجود ندارد',
		select_city: 'شهر را انتخاب کنید',
		enter_address: 'آدرس را وارد کنید',
		phone: 'تلفن را وارد کنید',
		phone_code: 'کد شهر را وارد کنید',
		postal_code: 'کد پستی را وارد کنید',
	},

	init: function(userId){
		this.table = this.tableContainer.find('> table');
		this.userId = userId;
		this.getAddresses();
		this.initProvinceSuggestionEngines();

		$('#create-address').click(function(event) {
			data = {
				province: {
					title: '',
				},
				city:{
					title: ''
				},
				userAddress:{
					id: '',
					address:'',
					
					phone:'',
					postalCode:'',
					createdAt: {
						date: ''
					}
				}
			};
			UserAddress.addRow(data);
		});

		$('#save-addresses').click(function(event) {
			UserAddress.saveAddresses();
		});

		UserAddress.tableContainer.on('click', 'tr button.delete', function(event) {
			row = $(this).parents('tr');
			url = Routing.generate('panel_user_address_delete', {user_address_id: row.attr('data-id')})
			TableUtil.deleteRow(row, url);
		});
	},

	initProvinceSuggestionEngines: function () {

	    //init Bloodhound engine for province Suggestion
	    this.provinceSuggestionEngine = new Bloodhound({
	        identify: function (o) {
	            return o.id;
	        },
	        queryTokenizer: Bloodhound.tokenizers.whitespace,
	        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
	        dupDetector: function (a, b) {
	            return a.id === b.id;
	        },
	        remote: {
	            url: Routing.generate('panel_province_suggest'),
	            prepare: function (query, settings) {
	            	settings.data = {query: query}
	                return settings;
	            }, 
                wildcard: '_QUERY'
            },

	    });

	    this.provinceSuggestionEngine.initialize();
	},

	initCitySuggestionEngines: function(input, province_id){

		input.unbind();
		input.typeahead('destroy');

		//init Bloodhound engine for city Suggestion
		this.citySuggestionEngine = new Bloodhound({
		    identify: function (o) {
		        return o.id;
		    },
		    queryTokenizer: Bloodhound.tokenizers.whitespace,
		    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
		    dupDetector: function (a, b) {
		        return a.id === b.id;
		    },
		    remote: {
		        url: Routing.generate('panel_city_suggest', {query: '_QUERY', province_id: province_id}),
		        wildcard: '_QUERY'
		    },
		});
	    this.citySuggestionEngine.initialize();
	    this.initCitySuggestion(input);

	    $('#create-address').click(function(event) {
	    	
	    });
	},

	initProvinceSuggestion: function (element) {
	    element.typeahead({
            highlight: false,
            minLength: 0,
            hint: false
        },
        {
            limit: Infinity,
            name: 'province',
            display: 'title',
            source: UserAddress.provinceSuggestionEngine.ttAdapter(),
        })
        .on('typeahead:select', function (event, suggestion) {
        	row = $(this).parents('tr');
            row.attr('data-province-id', suggestion.id)
            row.attr('data-city-id', '')

        	city_input = row.find('input[name=city]');
            city_input.val('');

        	UserAddress.initCitySuggestionEngines(city_input, suggestion.id);

            city_input.focus();
        })
        .on('typeahead:change', function(event, suggestion) {
        	console.log($(this).val());
        });
	},

	initCitySuggestion: function (element) {
	    element.typeahead({
            highlight: false,
            minLength: 0,
            hint: false,
        },
        {
            limit: Infinity,
            name: 'city',
            display: 'title',
            source: UserAddress.citySuggestionEngine.ttAdapter(),
        })
        .on('typeahead:select', function (event, suggestion) {
        	row = $(this).parents('tr');
            row.attr('data-city-id', suggestion.id);
            row.find('input[name=address]').focus();
        })
	},

	getAddresses: function(){

		if (this.ajaxAllowed) {

		    this.ajaxAllowed = false;
			// $('#main-loader').css('display', 'flex');

			$.ajax({
				url: Routing.generate('panel_user_address_list', {user_id : UserAddress.userId}),
				type: 'POST',
			})
			.done(function(data) {
				UserAddress.createTable(data);
			})
			.fail(function() {
				BackendFramework.showNotif('error');
			})
			.always(function() {

				setTimeout(function(argument) {
					$('#main-loader').css('display', 'none');
				}, 200)
				UserAddress.ajaxAllowed = true;
				
			})
		}
	},

	saveAddresses: function(){

		var addressObj = [];
		var addresses = [];
		var index = 0;

		isValid = true;
	
		UserAddress.table.find('tbody tr:not(.cloneable-row)').each(function(index, el) {
			row = $(this);
			
			id = row.attr('data-id');
			cityId = row.attr('data-city-id');
			cityTitle = row.find('input[name=city]').val();
			address = row.find('input[name=address]').val();
			phone = row.find('input[name=phone]').val();
			phoneCode = row.find('input[name=phoneCode]').val();
			postalCode = row.find('input[name=postalCode]').val();

			addressObj = {
			    id : id,
			    cityId : cityId,
			    address : address,
			    phone : phone,
			    phoneCode : phoneCode,
			    postalCode : postalCode
			}
			
			if(cityId == '' || cityTitle == ''){
				BackendFramework.showNotif('warning', UserAddress.message.select_city);
				isValid = false;
				return false;
			}

			if(address == ''){
				BackendFramework.showNotif('warning', UserAddress.message.enter_address);
				isValid = false;
				return false;
			}

			if(phone == ''){
				BackendFramework.showNotif('warning', UserAddress.message.enter_phone);
				isValid = false;
				return false;
			}

			if(phoneCode == ''){
				BackendFramework.showNotif('warning', UserAddress.message.enter_phone_code);
				isValid = false;
				return false;
			}

			if(postalCode == ''){
				BackendFramework.showNotif('warning', UserAddress.message.enter_postal_code);
				isValid = false;
				return false;
			}

			addresses[index] = addressObj;
			index++;
		});

		if(isValid){
			if(addresses.length){
				$.ajax({
					url: Routing.generate('panel_user_address_save', {user_id: UserAddress.userId}),
					type: 'POST',
					data: {
						userAddresses: addresses
					},
				})
				.done(function(data) {
					if(data.status){
						BackendFramework.showNotif('success');
						// setTimeout(function(){location.reload();}, 1000)
						
					}
				})
				.fail(function(data) {
					BackendFramework.showNotif('error');
				})
			}
			else{
				BackendFramework.showNotif('warning', UserAddress.message.no_address)
			}
		}	
	},

	createTable: function(data){
		UserAddress.table.find('tbody tr:not(.cloneable-row)').remove();

		if(data.addresses.length > 0){
			for (i in data.addresses) {
				this.addRow(data.addresses[i]);
			}
		}	
	},

	addRow: function(data){
		row = UserAddress.table.find('tbody tr.cloneable-row').clone();
		row.removeClass('cloneable-row hidden');

		if(data.id != ''){
			row.attr('data-id', data.id);
		}
		row.attr('data-city-id', data.cityId);
		row.attr('data-province-id', data.provinceId);
		row.find('[data-field=id]').html(data.id);
		row.find('[data-field=province] input').val(data.provinceTitle);
		row.find('[data-field=city] input').val(data.cityTitle);
		row.find('[data-field=address] input').val(data.address);
		row.find('[data-field=phone] input').val(data.phone);
		row.find('[data-field=phoneCode] input').val(data.phoneCode);
		row.find('[data-field=postalCode] input').val(data.postalCode);
		// row.find('[data-field=createdAt]').html(BackendFramework.gToj(data.createdAt.date));
		this.initProvinceSuggestion(row.find('[data-field=province] input'));
		this.initCitySuggestionEngines(row.find('[data-field=city] input'), data.provinceId);

		UserAddress.table.find('tbody').prepend(row);
	}


}