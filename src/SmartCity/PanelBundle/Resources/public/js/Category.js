Category = {
	
	init: function(){
		this.prepareTree();
		
		$('button.category-update').click(function(event) {
			event.preventDefault();
			Category.updateTree();
		});

		$('button.category-item-save').click(function(event) {
			categoryId = $('#category-form').attr('data-id');
			elId = $('#category-form').attr('id');
			Category.update(categoryId);
		});

	},

	prepareTree: function(initTree){
		
		$('#cetegories-wrapper ul').children('li').each(function(index, el) {
			parent_id = $(this).attr('data-parent');
			parent_el = $('#cetegories-wrapper ul').find('li[data-id='+parent_id+']');
			if(parent_el.children('ul').length == 0){
				parent_el.append('<ul></ul>');
			}
			$(this).appendTo(parent_el.children('ul'));
		});
		Category.initTree();
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
				three_state: true
			},
			search: {
				// show_only_matches: true
			},
			contextmenu: {
				items: function($node) {
				    var tree = $('#cetegories-wrapper').jstree(true);
				    return {
				        "Create": {
				            "separator_before": false,
				            "separator_after": false,
				            "label": "ساخت",
				            icon: "fa fa-plus",
				            "action": function (obj) { 
				                parentId = $node.li_attr['data-id'];
				            	$node = tree.create_node(
				            		$node, 
				            		'', 
				            		'first', 
				            		function(el){
				            			id = $(el).attr('id');
				            			
				            			$(el)[0].li_attr = {
				            				'id' : id,
				            				'data-id': -1,
				            				'data-parent': parentId
				            			}
				            			$('#cetegories-wrapper').find('li#'+id)
				            				.attr('id', id)
				            				.attr('data-id', -1)
				            				.attr('data-parent', parentId)
				            				.trigger('click');
				            		}
				            	);	

				            	tree.deselect_all();
				            	tree.select_node($node);
				            	
				            }
				        },
				        // "Rename": {
				        //     "separator_before": false,
				        //     "separator_after": false,
				        //     "label": "تغییر نام",
				        //     icon: "fa fa-pencil",
				        //     "action": function (obj) { 
				        //         tree.edit($node);
				        //     }
				        // },                         
				        "Remove": {
				            "separator_before": false,
				            "separator_after": false,
				            "label": "حذف",
				            icon: "fa fa-times",
				            "action": function (obj) {
				            	id = $node.li_attr['data-id'];
				            	Category.delete(id, $node, tree);
				            }
				        }
				    };
				}
			},
			plugins : [
				"contextmenu", "dnd", "search", "state", "types", "unique", 
			]
		});
		$('#cetegories-wrapper').jstree('open_all');
	

		$(document).on('dnd_stop.vakata', function(event) {
			// alert();
		});

		$(document).on('click', '#cetegories-wrapper li', function(event) {
			event.stopPropagation();
			categoryId = $(this).attr('data-id');
			parentId = $(this).attr('data-parent');
			elementId = $(this).attr('id');
			
			Category.getData(categoryId, parentId, elementId);
		});

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

	updateTree: function(){
		var categroyObj = [];
		var categries = [];
		var index = 0;

		$('#cetegories-wrapper ul').find('li').each(function(index, el) {

			id = $(this).attr('data-id');
			title = $(this).children('a').find('.category-title').directText().trim();
			position = $(this).index();
			parentId = $(this).parents('li').attr('data-id');
			code = $(this).attr('code');
			if (parentId == undefined) {
				parentId = null;
			}
		
			addressObj = {
			    id : id,
			    title : title,
			    parentId : parentId,
			    position : position,
			    code : code,
			}

			categries[index] = addressObj;
			index++;
		});

		$.ajax({
			url: Routing.generate('panel_category_update_tree'),
			type: 'POST',
			data: {
				categories: categries
			},
		})
		.done(function(response) {
			if(response.status){
				BackendFramework.showNotif('success');
			}
			else{
				BackendFramework.showNotif('error', response.error);
			}
		})
		.fail(function(response) {
			BackendFramework.showNotif('error', response.error);
		})	
	},

	getData: function(categoryId, parentId, elementId){
		catForm = $('#category-form');
		catForm.attr('data-id', categoryId);
		catForm.attr('data-parent', parentId);
		catForm.attr('data-element-id', elementId);
		
		if(categoryId == -1){
			title = $('#'+elementId).children('a').text();
			catForm.find('input[name=title]').val(title);
			catForm.find('input[name=code]').val('');
			catForm.find('textarea[name=description]').val('');
			catForm.find('.active')
				.prop('checked', true)
				.iCheck(BackendFramework.iCheckOptions);
		}
		else{
			$.ajax({
				url: Routing.generate('panel_category_show', {'id': categoryId}),
			})
			.done(function(response) {
				catForm.find('input[name=title]').val(response.category.title);
				catForm.find('input[name=code]').val(response.category.code);
				catForm.find('textarea[name=description]').val(response.category.description);
				catForm.find('input[name=position]').val(response.category.position);
				catForm.find('.active')
					.prop('checked', response.category.active)
					.iCheck(BackendFramework.iCheckOptions);

			})
			.fail(function() {
			})
		}
	},

	update: function(id){
		catForm = $('#category-form');
		elementId = catForm.attr('data-element-id');
		title = catForm.find('input[name=title]').val();
		
		active = 0;
		if(catForm.find('.active').is(':checked')){
			active = 1;
		}

		$.ajax({
			url: Routing.generate('panel_category_update', {'id': id}),
			type: 'POST',
			data: {
				title: title,
				code: catForm.find('input[name=code]').val(),
				description: catForm.find('textarea[name=description]').val(),
				position: catForm.find('input[name=position]').val(),
				parentId: catForm.attr('data-parent'),
				active: active
			},
		})
		.done(function(response) {
			if(response.status){
				location.reload();
				BackendFramework.showNotif('success');
			}
			else{
				BackendFramework.showNotif('error')
			}
		})
		.fail(function() {
			BackendFramework.showNotif('error')
		})
	},

	delete: function(id, $node, tree){
		
		bootbox.dialog({
		    message: BackendFramework.messages.are_you_sure,
		    size: 'small',
		    onEscape: true,
		    buttons: {
		        confirm: {
		            label: Translator.trans('label.yes', {}, 'labels'),
		            className: "btn-sm blue",
		            callback: function () {
		           		$.ajax({
		           			url: Routing.generate('panel_category_delete', {'id': id}),
		           			type: 'DELETE',
		           		})
		           		.done(function(response) {
		           			if(response.status){
		           				BackendFramework.showNotif('success', response.data.message);
		           				tree.delete_node($node);
		           			}
		           			else{
		           				BackendFramework.showNotif('error')
		           			}
		           		})
		           		.fail(function() {
		           			BackendFramework.showNotif('error')
		           		});
		            }
		        },
		        cancel: {
		            label: Translator.trans('label.no', {}, 'labels'),
		            className: "btn-sm grey",
		            callback: function () {
		            	return 0;
		            }
		        },
		    },
		});
	}
}