User = {
	numberRegex: /^[0-9۰۱۲۳۴۵۶۷۸۹]+$/,

	init: function(page){
		
		BackendFramework.initDatePicker('#user_birthday', '#user_jalaliBirthday');
		this.formValidation(page);

		cellphone = $('#user_cellphone').val();
		$('#user_cellphone').val(cellphone.replace(/\s/g, ''));

	},

	formValidation: function(page){

		rules = {
			'user[cellphone]': {
				required: true,
				minlength: 11,
				maxlength: 11,
				regex: User.numberRegex,
			},
			'user[nationalCode]': {
				required: true,
				minlength: 10,
				maxlength: 10,
				regex: User.numberRegex,
			},
		}

		messages = {
			'user[cellphone]': {
				required: "تکمیل این فیلد اجباری است.",
				number: "معتبر نیست",
				minlength: "معتبر نیست",
				maxlength: "معتبر نیست",
			},
			'user[nationalCode]': {
				required: "تکمیل این فیلد اجباری است.",
				minlength: "کد ملی باید ۱۰ رقم باشد",
				maxlength: "کد ملی باید ۱۰ رقم باشد",
			},
		}


		if(page == 'new'){
			rules['user[plainPassword][first]'] = {
				required: true,
				minlength: 6
			}
			rules['user[plainPassword][second]'] = {
				required: true,
				equalTo: "#user_plainPassword_first"
			}
			messages['user[plainPassword][first]'] = {
				required: "تکمیل این فیلد اجباری است.",
				minlength: "حداقل ۶ کاراکتر"
			}
			messages['user[plainPassword][second]'] = {
				required: "تکمیل این فیلد اجباری است.",
				equalTo: "رمز عبور یکسان نیست"
			}
		}

		if(page == 'edit'){
			rules['user[plainPassword][first]'] = {
				minlength: 6
			}
			rules['user[plainPassword][second]'] = {
				equalTo: "#user_plainPassword_first"
			}
			messages['user[plainPassword][first]'] = {
				minlength: "حداقل ۶ کاراکتر"
			}
			messages['user[plainPassword][second]'] = {
				equalTo: "رمز عبور یکسان نیست"
			}
		}

		$('form[name=user]').validate({
			rules: rules,
			messages: messages,
			ignore: "",
			
			errorElement: "span",
			// errorPlacement: function ( error, element ) {
			// 	// Add the `help-block` class to the error element
			// 	error.addClass( "help-block");

			// 	// Add `has-feedback` class to the parent div.form-group
			// 	// in order to add icons to inputs
			// 	element.parents('.input-wrapper').addClass( "has-feedback" );
			// 	// tabId = element.parents('.tab-pane').attr('id');
			// 	// $('ul.nav-tabs').find('a[target='+tabId+']').addClass('has_error')

			// 	if ( element.prop( "type" ) === "checkbox" ) {
			// 		error.insertAfter( element.parent( "label" ) );
			// 	} else {
			// 		error.insertAfter( element );
			// 	}

			// 	// Add the span element, if doesn't exists, and apply the icon classes to it.
			// 	if ( !element.next( "span" )[ 0 ] ) {
			// 		$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
			// 	}
			// },

			success: function ( label, element ) {
				
				// Add the span element, if doesn't exists, and apply the icon classes to it.
				if ( !$( element ).next( "span" )[ 0 ] ) {
					$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
				}				
			},

			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents('.input-wrapper').addClass( "has-error" ).removeClass( "has-success" );
				// $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
			},

			unhighlight: function ( element, errorClass, validClass ) {
				$( element ).parents('.input-wrapper').addClass( "has-success" ).removeClass( "has-error" );
				// $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
			},

			invalidHandler: function(event, validator) {
			    var errors = validator.numberOfInvalids();
			    if (errors) {
					BackendFramework.showNotif('error', 'موارد مشخص شده را اصلاح نمایید')
				}
			},

			submitHandler: function(event){
				$('#user_submit').prop('disabled', true);
				
			    $.ajax({
			    	url: window.location.href,
			    	type: 'POST',
			    	data: $('form[name=user]').serialize(),
			    })
			    .done(function(response) {
			    	if(!response.status){
			    		BackendFramework.showNotif('error', response.error)
			    		$('#user_submit').prop('disabled', false)
			    	}
			    	else{
			    		BackendFramework.showNotif('success')
			    		setTimeout(function(){
	                        window.location = Routing.generate('panel_user_edit', {'id': response.data.id});
			    		}, 1000)
			    	}
			    })
			    .fail(function() {
			    	BackendFramework.showNotif('error')
			    	$('#user_submit').prop('disabled', false)
			    })

			}
		});
	},
}