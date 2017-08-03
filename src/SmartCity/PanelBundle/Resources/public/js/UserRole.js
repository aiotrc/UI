UserRole = {
    
    init: function () {
        $(document.body).on('click', '.role_edit_button', UserRole.getRoleData);
        $('#myModal').on('click', '.update_button', UserRole.updateRoleData);
    },
    
    getRoleData: function (event) {
        event.preventDefault();

        var username = $(this).parent().parent().find('.user_fullname').text();

        $.ajax({
            url: $(this).attr('href')
        })
        .done(function (data) {
            console.log(data);
            $('#myModal').find('.modal-header').empty().html(username);
            $('#myModal').find('.modal-body').empty().html(data);
            $('.modal_role_form').find('#form_roles label:nth-child(4n)').after('<br>');
            
            $('#btn-modal').trigger('click');
        })
        .fail(function (data) {
            BackendFramework.showNotif('error', data.responseJSON.error.message)
            // BackendFramework.loading(false, $('#myModal').find('.modal-content'), true, data);
        })
    },

    updateRoleData: function (event) {
        event.preventDefault();
        BackendFramework.loading(true, $('#myModal').find('.modal-content'), true);
        
        form = $(this).parents('#myModal').find('form');
        
        $.ajax({
            url: form.attr('action'),
            type: 'PUT',
            data: form.serialize(),
        })
        .done(function () {
            BackendFramework.loading(false, $('#myModal').find('.modal-content'), true, BackendFramework.messages.updated_successfully);
        })
        .fail(function (data) {
            BackendFramework.loading(false, $('#myModal').find('.modal-content'), true, data);
        })   
    },

};

