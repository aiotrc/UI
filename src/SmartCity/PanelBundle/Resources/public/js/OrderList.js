OrderList = {

	tableContainer: $('#order-table-container'),
	table: null,

	ajaxAllowed: true,

	filters: {
		pageSize: null,
		currentPage: null,
	},

	init: function(){

		this.table = this.tableContainer.find('> table');
		this.getOrders();

		$(OrderList.tableContainer).on('click', 'ul.pagination a:not(.active)', function (event){
			event.preventDefault();
			OrderList.getOrders(true);
		});
	},

	getOrders: function(page_changed){

		if (page_changed === undefined) { page_changed = false; }

		if (this.ajaxAllowed) {

		    if(!page_changed){
		        OrderList.table.attr('data-currentPage', 0);
		    }

		    this.ajaxAllowed = false;
			// $('#main-loader').css('display', 'flex');

			OrderList.filters.pageSize = OrderList.table.attr('data-pagesize');
			OrderList.filters.currentPage = OrderList.table.attr('data-currentPage');

			$.ajax({
				url: Routing.generate('panel_order_list'),
				type: 'POST',
				data: {
					filters: OrderList.filters
				}
			})
			.done(function(data) {
				OrderList.createTable(data);
			})
			.fail(function() {
				BackendFramework.showNotif('error');
			})
			.always(function() {

				setTimeout(function(argument) {
					$('#main-loader').css('display', 'none');
				}, 200)
				OrderList.ajaxAllowed = true;
				
			})
		}
	},

	createTable: function(data){
		recordsTotal = parseInt(data.orders.recordsTotal);
		recordsFiltered = parseInt(data.orders.recordsFiltered);
		TableUtil.paginationHandler(this.table, recordsTotal, recordsFiltered);
		this.addRows(data.orders.result);
	},

	addRows: function(orders){

		OrderList.table.find('tbody tr:not(.cloneable-row)').remove();
		pageSize = parseInt(OrderList.table.attr('data-pagesize'));
		currentPage = parseInt(OrderList.table.attr('data-currentPage'));

		for (i in orders) {
			row = OrderList.table.find('tbody tr.cloneable-row').clone();
			row.removeClass('cloneable-row hidden');

			index = pageSize * currentPage + parseInt(i) + 1;
			createdAt = BackendFramework.gToj(orders[i].createdAt.date);

			row.find('[data-field=rowId]').html(index);
			row.find('[data-field=id]').html(orders[i].id);
			row.find('[data-field=customerFullName] a').attr('href', Routing.generate('panel_user_edit', {'id': orders[i].customer.id})).html(orders[i].customerFullName);
			row.find('[data-field=city]').html(orders[i].recipientAddressCity.title);
			row.find('[data-field=totalPrice]').html(BackendFramework.toFaNum(BackendFramework.applyCommas(orders[i].totalPrice)));
			row.find('[data-field=status]').html(orders[i].status);
			row.find('[data-field=paymentStatus]').html(orders[i].paymentStatus);
			row.find('[data-field=createdAt]').html(createdAt);
			row.find('[data-field=source]').html(orders[i].source);
			row.find('[data-field=actions] .edit').attr('href', Routing.generate('panel_order_edit', {'id': orders[i].id}));

			OrderList.table.find('tbody').append(row);
		}
	}
}