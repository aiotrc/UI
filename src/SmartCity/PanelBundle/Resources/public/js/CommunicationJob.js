CommunicationJob = {

	tableContainer: $('#communication-job-table-container'),
	table: null,

	ajaxAllowed: true,

	filters: {
		pageSize: null,
		currentPage: null,
	},

	init: function(){

		this.table = this.tableContainer.find('> table');
		this.getCommunicationJobs();

		$(CommunicationJob.tableContainer).on('click', 'ul.pagination a:not(.active)', function (event){
			event.preventDefault();
			CommunicationJob.getCommunicationJobs(true);
		});

		$(CommunicationJob.tableContainer).on('click', '.showMessage', function (event){
			messageBody = $(this).parents('tr').find('td[data-field=messageBody]').html();

			BackendFramework.modal.find('.modal-header').empty().html('متن پیام');
			BackendFramework.modal.find('.modal-body').empty().html(messageBody);			
			BackendFramework.modal.modal('show');
			
		});

		
	},

	getCommunicationJobs: function(page_changed){

		if (page_changed === undefined) { page_changed = false; }

		if (this.ajaxAllowed) {

		    if(!page_changed){
		        CommunicationJob.table.attr('data-currentPage', 0);
		    }

		    this.ajaxAllowed = false;
			// $('#main-loader').css('display', 'flex');

			CommunicationJob.filters.pageSize = CommunicationJob.table.attr('data-pagesize');
			CommunicationJob.filters.currentPage = CommunicationJob.table.attr('data-currentPage');

			$.ajax({
				url: Routing.generate('panel_communication_job_list'),
				type: 'POST',
				data: {
					filters: CommunicationJob.filters
				}
			})
			.done(function(data) {
				CommunicationJob.createTable(data);
			})
			.fail(function() {
				BackendFramework.showNotif('error');
			})
			.always(function() {

				setTimeout(function(argument) {
					$('#main-loader').css('display', 'none');
				}, 200)
				CommunicationJob.ajaxAllowed = true;
				
			})
		}
	},

	createTable: function(data){
		console.log()
		recordsTotal = parseInt(data.communicationJobs.recordsTotal);
		recordsFiltered = parseInt(data.communicationJobs.recordsFiltered);
		TableUtil.paginationHandler(this.table, recordsTotal, recordsFiltered);
		this.addRows(data.communicationJobs.result);
	},

	addRows: function(communicationJobs){

		CommunicationJob.table.find('tbody tr:not(.cloneable-row)').remove();
		pageSize = parseInt(CommunicationJob.table.attr('data-pagesize'));
		currentPage = parseInt(CommunicationJob.table.attr('data-currentPage'));


		for (i in communicationJobs) {
			row = CommunicationJob.table.find('tbody tr.cloneable-row').clone();
			row.removeClass('cloneable-row hidden');

			scheduledAt = BackendFramework.gToj(communicationJobs[i].scheduledAt.date);
			index = pageSize * currentPage + parseInt(i) + 1;
			
			row.find('[data-field=rowId]').html(index);
			row.find('[data-field=id]').html(communicationJobs[i].id);
			row.find('[data-field=title]').html(communicationJobs[i].title);
			row.find('[data-field=status]').html(communicationJobs[i].status);
			row.find('[data-field=receiver] a').attr('href', Routing.generate('panel_user_edit', {'id' : communicationJobs[i].receiverId})).html(communicationJobs[i].receiverId);
			row.find('[data-field=messageType]').html(communicationJobs[i].messageType);
			row.find('[data-field=scheduledAt]').html(scheduledAt);
			row.find('[data-field=messageBody]').html(communicationJobs[i].message);

			CommunicationJob.table.find('tbody').append(row);
		}
	}
}