UserProductReview = {

	tableContainer: $('#review-table-container'),
	table: null,

	ajaxAllowed: true,

	filters: {
		productId: null,
		userId: null,
		pageSize: null,
		currentPage: null,
	},

	init: function(userId, productId){

		if (userId === undefined){ userId = null;}
		if (productId === undefined){ productId = null;}

		this.table = this.tableContainer.find('> table');
		this.filters.userId = userId;
		this.filters.productId = productId;
		this.getReviews();

		$(UserProductReview.tableContainer).on('click', 'ul.pagination a:not(.active)', function (event){
			event.preventDefault();
			UserProductReview.getReviews(true);
		});

		$(UserProductReview.tableContainer).on('change', 'select[name=reviewStatus]', function(event) {
			selectBox = $(this);
			status = selectBox.val();

			objectId = [parseInt(selectBox.parents('tr').attr('data-id'))];

			UserProductReview.changeStatus(selectBox, objectId, status);
		});

		$(UserProductReview.tableContainer).on('click', '.bulk-apply-btn', function(event) {
			event.preventDefault();

			selectBox = UserProductReview.tableContainer.find('.bulk-change');
			status = selectBox.val();

			objectIds = [];
			UserProductReview.table.find('tr.selected').each(function(index, el) {
			    id = parseInt($(this).attr('data-id'));
			    objectIds.push(id);
			});

			if(objectIds.length < 1){
			    BackendFramework.showNotif('warning', TableUtil.message.no_row_selected);
			    return 0;
			}

			UserProductReview.changeStatus(selectBox, objectIds, status);
		});
	},

	changeStatus: function(caller, obejectIds, status){
		
		$.ajax({
			url: Routing.generate('panel_user_product_review_change_status'),
			type: 'POST',
			data: {
				objectIds: obejectIds,
				status: status,
			},
		})
		.done(function(data) {
			if(data['status']){
				for(i in obejectIds){
					row = UserProductReview.table.find('tr[data-id='+obejectIds[i]+']')
					row.attr('data-status', status);
					row.find('select[name=reviewStatus] option').prop('disabled', false);
					label = row.find('select[name=reviewStatus] option[value='+status+']').prop('disabled', true).html();
					row.find('td[data-field=status] span').html(label);
				}
				BackendFramework.showNotif('success');
				
			}
		})
		.fail(function() {
			BackendFramework.showNotif('error');
		})		
	},

	getReviews: function(page_changed){

		if (page_changed === undefined) { page_changed = false; }

		if (this.ajaxAllowed) {

		    if(!page_changed){
		        UserProductReview.table.attr('data-currentPage', 0);
		    }

		    this.ajaxAllowed = false;
			// $('#main-loader').css('display', 'flex');

			UserProductReview.filters.pageSize = UserProductReview.table.attr('data-pagesize');
			UserProductReview.filters.currentPage = UserProductReview.table.attr('data-currentPage');

			$.ajax({
				url: Routing.generate('panel_user_product_review_list'),
				type: 'POST',
				data: {
					filters: UserProductReview.filters
				}
			})
			.done(function(data) {
				UserProductReview.createTable(data);
			})
			.fail(function() {
				BackendFramework.showNotif('error');
			})
			.always(function() {

				setTimeout(function(argument) {
					$('#main-loader').css('display', 'none');
				}, 200)
				UserProductReview.ajaxAllowed = true;
				
			})
		}
	},

	createTable: function(data){
		recordsTotal = parseInt(data.reviews.recordsTotal);
		recordsFiltered = parseInt(data.reviews.recordsFiltered);
		TableUtil.paginationHandler(this.table, recordsTotal, recordsFiltered);

		bulkChange = this.tableContainer.find('.bulk-change');
		bulkChange.empty();
		for(key in data.reviewStatuses){
			bulkChange.append('<option value="'+key+'">'+Translator.trans(data.reviewStatuses[key], {}, 'labels')+'</option>')
		}

		this.addRows(data.reviews.result , data.reviewStatuses);
	},

	addRows: function(reviews, reviewStatuses){

		UserProductReview.table.find('tbody tr:not(.cloneable-row)').remove();
		pageSize = parseInt(UserProductReview.table.attr('data-pagesize'));
		currentPage = parseInt(UserProductReview.table.attr('data-currentPage'));

		for (i in reviews) {
			row = UserProductReview.table.find('tbody tr.cloneable-row').clone();
			row.removeClass().attr({
				'data-status': reviews[i].status,
				'data-id': reviews[i].id
			});

			index = pageSize * currentPage + parseInt(i) + 1;
			username = reviews[i].user.firstname +' '+ reviews[i].user.lastname
			sumbitDate = BackendFramework.gToj(reviews[i].createdAt.date);
			reviewDate = BackendFramework.gToj(reviews[i].updatedAt.date);
			
			productTitle = reviews[i].product.title.substr(0, 12);
			if(reviews[i].product.title.length > 12){
				productTitle += ' ...';
			}


			actions = '';
			for(key in reviewStatuses){
				disabled = ''
				if( key == reviews[i].status){
					disabled = 'disabled'
					row.find('[data-field=status] span').html(Translator.trans(reviewStatuses[key], {}, 'labels'));
				}
				actions += '<option value="'+key+'" '+disabled+'>'+Translator.trans(reviewStatuses[key], {}, 'labels')+'</option>';
			}

			row.find('[data-field=rowId]').html(index);
			row.find('[data-field=id]').html(reviews[i].id);
			row.find('[data-field=userName] a').attr('href', Routing.generate('panel_user_edit', {'id': reviews[i].user.id})).html(username);
			row.find('[data-field=productTitle] a').attr('href', Routing.generate('panel_product_edit', {'id': reviews[i].product.id})).html(productTitle);
			row.find('[data-field=title]').html(reviews[i].title);
			row.find('[data-field=comment]').html(reviews[i].comment);
			row.find('[data-field=rating]').html(reviews[i].rating);
			row.find('[data-field=sumbitDate]').html(sumbitDate);
			row.find('[data-field=reviewDate]').html(reviewDate);
			row.find('[data-field=actions] select').append(actions);

			UserProductReview.table.find('tbody').append(row);
			row.find('input[type=checkbox]').iCheck(BackendFramework.iCheckOptions);
		}
	}


}