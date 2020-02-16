var domain = "/admin/";
var api = {
    login: domain + 'login',
    loginout: domain + 'loginout',
    upload: domain + 'upload/upload',
    updateMemberInfo: domain + 'member/editinfo',
    updateMemberPoints: domain + 'member/editpoints',
    uploadimg: domain + 'upload/img',
    addgoods: domain + 'goods/add',
    deletegoods: domain + 'goods/del',
    cancelOrder: domain + 'order/cancel',
    cancelSendOrder: domain + 'order/cancelsend',
    sendOrder: domain + 'order/send',
    rewaitOrder: domain + 'order/rewait',
};

function kong() {
    // "这是一个空方法"
}

var http = {
    get: function (url, data, successFunc, errorFunc, completeFunc) {
        completeFunc = completeFunc || kong;
        var token = localStorage.getItem('chunyun_token') || null;
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: data,
            headers: {'Authorization': 'Bearer ' + token},
            success: function (res, status) {
                status = status == 'success' ? 200 : status;
                successFunc(res, status);
            },
            error: function (err) {
                // if (err.status == 401) {
                //     console.log(111);
                //     // layer.msg('略略略');
                //     layer.alert('sdsdsc');
                //     console.log(layer);
                //     // parent.window.open('/admin');
                //     return;
                // }
                errorFunc(err.responseJSON, err.status);
            },
            complete: function () {
                completeFunc()
            }
        });
    },
    post: function (url, data, successFunc, errorFunc, completeFunc) {
        var completeFunc = completeFunc || kong;
        var token = localStorage.getItem('chunyun_token') || null;
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            headers: {'Authorization': 'Bearer ' + token},
            success: function (res, status) {
                status = status == 'success' ? 200 : status;
                successFunc(res, status);
            },
            error: function (err) {
                errorFunc(err.responseJSON, err.status);
            },
            complete: function () {
                completeFunc()
            }
        });
    }
};

var layout = {
    refresh: function () {
        setTimeout(function () {
            window.location.reload();
        }, 1000);
    },
    toLogin: function () {
        setTimeout(function () {
            parent.window.open('/login', '_self');
        }, 1000);
    },
    redirect: function (url) {
        setTimeout(function () {
            window.open(url, '_self');
        }, 1000);
    },
    close: function (time, func) {
        time = time || 1000;
        func = func || kong;
        setTimeout(function () {
            window.close();
            func();
        }, time);
    },
    layClose: function (time, func) {
        time = time || 1000;
        func = func || kong;
        setTimeout(function () {
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
            func();
        }, time);
    }
};
