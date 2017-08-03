ItemFiltering = {

    actionObj : null,

    init: function(){
        var checkBoxList = new Array();
        var checkboxHeight = $(this.actionObj).children('.checkbox').eq(0).outerHeight() + 8;
        var cnt = 0;

        $(this.actionObj).children('.checkbox').each(function () {
            var name = $(this).text().toLowerCase();
            checkBoxList.push({
                'id': cnt,
                'checked': $(this).find('input').is(":checked"),
                'name': name,
                'visible': true
            });
            $(this).attr('id', 'checkbox_' + cnt).css({
                'position': 'absolute',
                'right': '0',
            });
            $(this).find('input').attr('data-id', cnt);
            cnt++;
        });

        function sortByChecked() {
            var hold;
            for (var i = 0; i < checkBoxList.length; i++) {
                for (var j = i; j < checkBoxList.length; j++) {
                    if (checkBoxList[i].visible < checkBoxList[j].visible) {
                        hold = checkBoxList[i];
                        checkBoxList[i] = checkBoxList[j];
                        checkBoxList[j] = hold;
                    }
                }
            }
            for (var i = 0; i < checkBoxList.length; i++) {
                for (var j = i; j < checkBoxList.length; j++) {
                    if (    checkBoxList[i].checked < checkBoxList[j].checked && checkBoxList[j].visible &&
                            checkBoxList[i].visible) {
                        hold = checkBoxList[i];
                        checkBoxList[i] = checkBoxList[j];
                        checkBoxList[j] = hold;
                    }
                }
            }
        }
        function searchByName(_name) {
            for (var i = 0; i < checkBoxList.length; i++) {
                if (_name == '' || checkBoxList[i].name.indexOf(_name) > -1) {
                    checkBoxList[i].visible = true;
                }
                else {
                    checkBoxList[i].visible = false;
                }
            }
        }
        function placeCheckBoxes() {
            $(ItemFiltering.actionObj).css({
                'height': (checkboxHeight*checkBoxList.length)+'px'
            });
            for (var i = 0; i < checkBoxList.length; i++) {
                $('#checkbox_'+checkBoxList[i].id).css({
                    'top': (checkboxHeight*i)+'px',
                    'opacity': (checkBoxList[i].visible? '1':'0.5')
                });
            }
        }
        function findById(_id) {
            for (var i = 0; i < checkBoxList.length; i++) {
                if (checkBoxList[i].id == _id) {
                    return i;
                }
            }
        }

        sortByChecked();
        placeCheckBoxes();

        $(document)
            .on('input', '#filter_input', function () {
                searchByName($(this).val());
                sortByChecked();
                placeCheckBoxes();
            })
            .on('click', ItemFiltering.actionObj+' input[type="checkbox"]', function () {
                var id = $(this).data('id');
                var index = findById(id);
                checkBoxList[index].checked = $(this).is(":checked");
            });
    }
}