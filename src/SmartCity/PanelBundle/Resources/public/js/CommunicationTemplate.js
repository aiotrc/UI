CommunicationTemplate = {
    init: function(){
        this.initSummerNote();
    },

    initSummerNote: function(){
        $('.summernote').summernote({
            height: 385,// set editor height
        });
    },

    initPlaceHolder: function(){
        var placeHolderItem = document.getElementsByClassName("placeholder-item");

        for (var i=0; i < placeHolderItem.length; i++) {
            placeHolderItem[i].onclick = function(){
                document.getElementsByClassName('note-editable')[0].focus();

                CommunicationTemplate.pasteHtmlAtCaret($(this).val(), false);
                return false;
            };
        }
    },

    pasteHtmlAtCaret: function (html, selectPastedContent) {
        var sel, range;
        if (window.getSelection) {
            // IE9 and non-IE
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();

                // Range.createContextualFragment() would be useful here but is
                // only relatively recently standardized and is not supported in
                // some browsers (IE9, for one)
                var el = document.createElement("div");
                el.innerHTML = html;
                var frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                var firstNode = frag.firstChild;
                range.insertNode(frag);
                
                // Preserve the selection
                if (lastNode) {
                    range = range.cloneRange();
                    range.setStartAfter(lastNode);
                    if (selectPastedContent) {
                        range.setStartBefore(firstNode);
                    } else {
                        range.collapse(true);
                    }
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            }
        } 
        else if ( (sel = document.selection) && sel.type != "Control") {
            // IE < 9
            var originalRange = sel.createRange();
            originalRange.collapse(true);
            sel.createRange().pasteHTML(html);
            if (selectPastedContent) {
                range = sel.createRange();
                range.setEndPoint("StartToStart", originalRange);
                range.select();
            }
        }
    },

}