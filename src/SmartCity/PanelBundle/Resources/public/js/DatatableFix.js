var timeout = null;
var XHRPool = new Array();
var sendAjax = false;

function abortAllAjax() {
    for (var i = 0; i < XHRPool.length; i++) {
        XHRPool[i].abort();
    }
}

function callLastAjaxWithTimeout(_jqXHR) {
    try {
        clearTimeout(timeout);
    }
    catch (e) {
        console.log('cannot clear timeout');
    }
    timeout = setTimeout(function(){
        $.ajax(_jqXHR);
        sendAjax = true;
    }, 1000)
}

function isDataTableAjax(_url) {
    return (_url.indexOf('results?draw') > -1);
}

setTimeout(function() {
    $.ajaxSetup({
        beforeSend: function(jqXHR) {
            //timeOut = setTimeout(function() {
            //    $.ajax($.extend({beforeSend: $.noop}));
            //    cnt++;
            //    console.log(cnt);
            //}, 500);
            if(isDataTableAjax(this.url)) {
                if (sendAjax) {
                    sendAjax = false;
                    return true;
                }
                else {
                    //abortAllAjax();
                    //XHRPool.push(jqXHR);
                    callLastAjaxWithTimeout(this);
                    return false;
                }
            }
            else {
                return true;
            }
        },
        complete: function(jqXHR) {
            //var index = XHRPool.indexOf(jqXHR);
            //if (index > -1) {
            //    XHRPool.splice(index, 1);
            //}
            if(isDataTableAjax(this.url)) {
                sendAjax = false;
            }
        }
    });
}, 2000)