BackendFramework = {

	loadingGif: '<img width="16" height="16" src="/bundles/smartcitypanel/images/loading2.gif" />',
	iCheckOptions:{
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
	},

	messages:{
	    updated_successfully: Translator.trans('label.updated.successfully', {}, 'labels'),
	    are_you_sure: 'آیا مطمئنید ؟'
	},
	modal: $('#myModal'),

	init: function(){
		$('.page-sidebar .sub-menu li.active')
			.parents('li').addClass('active')
			.find('.arrow').addClass('open')
		;
		var path = window.location.pathname;
		
		$('.nav-item > a[href="'+path+'"]').parents('li').addClass('active');
		// $('.fancybox-button').fancybox();

		$.validator.addMethod("regex", function(value, element, regexpr) {          
		    return regexpr.test(value);
		}, "نا معتبر");

		jQuery.fn.extend({
		    directText: function () {
		    	text = $(this)
		    	    .clone()    //clone the element
		    	    .children() //select all the children
		    	    .remove()   //remove all the children
		    	    .end()  //again go back to selected element
		    	    .text();

				return text;
		    }
		});

		// $.fn.select2entityAjax = function(action) {
		//     var action = action || {};
		//     var template = function (item) {
		//         var img = item.img || null;
		//         if (!img) {
		//             if (item.element && item.element.dataset.img) {
		//                 img = item.element.dataset.img;
		//             } else {
		//                 return item.text;
		//             }
		//         }
		//         return $(
		//             '<span><img src="' + img + '" class="img-circle img-sm"> ' + item.text + '</span>'
		//         );
		//     };
		//     this.select2entity($.extend(action, {
		//         templateResult: template,
		//         templateSelection: template,
		//         dir: "ltr",
		//         tokenSeparators: [',']
		//     }));
		//     return this;
		// };
		// $('.select2entity').select2entityAjax();
		
	},

	toFaNum: function(str) {
    	return String(str).replace(/\d/g, this.persianNumberMapper);
	},
	
	// number and date utils
	applyCommas: function(str) {
	    return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	},

	persianNumberMapper: function(num) { 
		return ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'][num]; 
	},

	getQueryVariable: function(variable){
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
	},

	showNotif: function(type, msg, title, timeOut){

		if (timeOut === undefined) {
            timeOut = 3000;
        }
	    // type : info, success, error, warning
	    if(!msg){
	        if(type == "success"){
	            msg = 'با موفقیت ثبت شد';
	        }
	        else if(type == "error"){
	            msg = 'خطایی رخ داده است';
	        }
	        else if(type == "info"){
	            msg = 'تغییری صورت نگرفته است'
	        }
	        else if(type == "warning"){
	            msg = 'موارد خواسته شده را وارد کنید'
	        }
	        else{
	            type = 'error'
	            msg = 'نا مشخص';
	        }
	    }

	    toastr.options = {
	        closeButton: true,
	        positionClass: 'toast-top-right',
	        onclick: null,
	        showDuration: 500,
	        hideDuration: 500,
	        timeOut: timeOut,
	        extendedTimeOut: 1000,
	        showEasing: 'swing',
	        hideEasing: 'linear',
	        showMethod: 'fadeIn',
	        hideMethod: 'fadeOut'
	    };

	    toastr[type](msg, title);
	},

	loading: function(status, parent, fullWidth, message){
        loaderElement = '<div class="loader-wrapper" data-full-width="'+fullWidth+'"><div class="loader loading"><svg><circle class="loader-circle" cx="50%" cy="50%" r="40%"></circle></svg></div></div>';

        if(status){
            parent.append(loaderElement)
        }
        else{
            setTimeout(function(){
                parent.find('.loader svg').remove();
                parent.find('.loader').append('<span class="loading-message">'+message+'</span> ');

                // setTimeout(function(){
                    parent.find('.loader-wrapper').remove();
                // }, 1000);

            }, 600
            );
        }
    },

    initDatePicker: function(input, altField, hasTimePicker){

		if (hasTimePicker === undefined) {
            hasTimePicker = false;
        }
		
		$(input).persianDatepicker({
			
			format: 'YYYY-MM-DD',
			altField: altField,
			altFormat: "g",
			position: [33, 85],
			autoClose: true,
			timePicker: {
			   enabled: hasTimePicker
			},
			onSelect: function(){
				$(input).blur()
			}
		});
	},

	animateDelete: function(el) {
	    el.animate({
	        'backgroundColor': 'rgba(203, 90, 94, 0.8)',
	        'opacity': '0',
	    }, 600, function(){
	        el.remove();
	    });
	},

	gToj: function(date){
		if(date != '' && date != null){
			return this.toFaNum(moment(date, 'YYYY-M-D HH:mm:ss').format('jD jMMMM jYY HH:mm'));
		}
		return '';
	}

}