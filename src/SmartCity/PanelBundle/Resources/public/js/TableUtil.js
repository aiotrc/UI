TableUtil = {

    message: {
        no_row_selected : 'سطری انتخاب نشده است',
        are_you_sure : 'آیا مطمئن هستید',
        please_wait : 'لطفا صبر کنید',
    },
	
	init: function(argument) {

        $(document.body).on('click', 'ul.pagination a:not(.active)', function (event) {
            event.preventDefault();
            num_el = $(this);
            currentPageNum = num_el.text();
            tableContainer = num_el.parents('.table-container');
            tableContainer.find('table').attr('data-currentPage', parseInt(num_el.html()-1));
        })

        tableAction = [
            '<div class="table-action row">'+
                '<div class="col-md-6">'+
                    '<div class="table-info">'+
                        '<div class="record-info"></div>'+
                        '<div class="selection-info"></div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-6 pull-left text-left">'+
                    
                '</div>'+
            '</div>'
        ];

        tablePagination = [
            '<nav class="table-pagination pull-left" aria-label="Page navigation">'+
                '<ul class="pagination"></ul>'+
            '</nav>'
        ]

        tableBulkAction = [
            '<div class="table-bulk-action form-inline pull-left inline-block">'+
                '<select class="form-control input-sm inline bulk-change"></select> '+
                '<button class="btn btn-sm blue bulk-apply-btn">'+Translator.trans('button.submit', {}, 'buttons')+'</button>'+
            '</div>'
        ];


        $('table[data-hasInfo=true]').each(function(index, el) {
            table = $(this);
            table.after(tableAction).before(tableAction);
            table.parents('.table-container').find('.table-action:last-child > div:last-child').append(tablePagination);
            table.parents('.table-container').find('.table-action:first-child > div:last-child').append(tablePagination);

            if(table.attr('data-hasBulkAction') == 'true'){
                table.parents('.table-container').find('.table-action:first-child > div:last-child').append(tableBulkAction);
            }
            else{
            }
        });
        
        
        this.checkableTableHandler();   
    },

    checkableTableHandler: function(){

        checkableTable = $('table.table-checkable');
        checkableTable.find('thead tr.table-head').prepend('<th width="2px"><input type="checkbox" class="group-checkbox"></th>');
        checkableTable.find('tbody tr.cloneable-row').prepend('<td><input type="checkbox" class="row-checkbox"></td>');
        checkableTable.find('thead tr.table-head').iCheck(BackendFramework.iCheckOptions);

        checkableTable.on('change, ifChanged', 'thead .group-checkbox', function(event) {
            groupCheckbox = $(this);
            tableBody = groupCheckbox.parents('table').find('tbody');

            if (groupCheckbox.is(':checked')) {
                tableBody.find('tr:not(.cloneable-row)')
                    .addClass('selected')
                    .find('.row-checkbox')
                    .iCheck('check')
                ;
            }
            else{
                tableBody.find('tr:not(.cloneable-row)')
                    .removeClass('selected')
                    .find('.row-checkbox')
                    .iCheck('uncheck')
                ;
            }
        });

        checkableTable.on('change, ifChanged', 'tbody .row-checkbox', function(event) {
            rowCheckbox = $(this);

            if (rowCheckbox.is(':checked')) {
                rowCheckbox.parents('tr').addClass('selected');
            }
            else{
                rowCheckbox.parents('tr').removeClass('selected');
            }
        });
    },
    
	deleteRow: function(row, url){

        if(confirm(this.message.are_you_sure)){
            // -1 means new entity which is not exist in database
            if(row.attr('data-id') == -1 || row.attr('data-id') == ''){
                BackendFramework.animateDelete(row);
            }
            else{
                $.ajax({
                    url: url,
                    type: 'DELETE',
                })
                .done(function(data) {
                    if(data.status){
                        BackendFramework.showNotif('success', 'با موفقیت حذف شد');
                        BackendFramework.animateDelete(row);
                    }
                    else{
                        BackendFramework.showNotif('error')
                    }
                })
                .fail(function() {
                    BackendFramework.showNotif('error');
                })
            }
        }
		
	},

	paginationHandler: function (table, recordsTotal, recordsFiltered) {

        if(recordsTotal <= 0){
            // return 0;
        }

        pageSize = parseInt(table.attr('data-pageSize'));
		currentPage = parseInt(table.attr('data-currentPage'))+1;
        totalPages = Math.ceil(recordsFiltered / pageSize);

        // table pagination
        p_el = table.siblings('.table-action').find('ul.pagination');
        p_el.empty();

        TableUtil.insertPage(p_el, 1);
        if(currentPage > 3){
            TableUtil.insertPage(p_el, '...');
        }
        if(totalPages > 1){
            for (var i = currentPage -1 ; i <= currentPage +1; i++) {
                if(i > 1 && i < totalPages){
                    TableUtil.insertPage(p_el, i);
                }
            }

            if(currentPage < parseInt(totalPages-2)){
                TableUtil.insertPage(p_el, '...');
            }
            TableUtil.insertPage(p_el, totalPages);
        }
        p_el.find('a').each(function(index, el) {
            page = $(this).text();
            if(currentPage == page){
                $(this).parents('li').addClass('active');
            }
        });

        // table info
        i_el = table.siblings('.table-action').find('.record-info');
        i_el.empty();

        from_record = parseInt(pageSize*(currentPage-1)+1);
        to_record = pageSize*currentPage;
        if(recordsFiltered == 0){
            from_record = 0;
            to_record = 0;
        }
        else if(recordsFiltered < pageSize || recordsFiltered < to_record){
            to_record = recordsFiltered;
        }

        i_el.append('نمایش '+from_record+' تا '+to_record+' از مجموع '+recordsFiltered+' سطر ');

        if(recordsFiltered != recordsTotal && recordsFiltered != 0){
            i_el.append('(فیلتر شده از مجموع '+recordsTotal+' سطر)');
        }

    },

    insertPage: function(wrapper, pageNum){
        if(pageNum == '...'){
            wrapper.append('<li><span href="#">...</span></li>');
        }
        else{
            wrapper.append('<li><a href="#">'+pageNum+'</a></li>');
        }
    }
}