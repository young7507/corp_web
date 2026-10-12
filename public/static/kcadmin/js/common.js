var API_BASE = '/kcadmin';

function getToken() {
    return localStorage.getItem('token') || '';
}

function axiosPost(url, data, callback) {
    var formData = data;
    if (!(data instanceof FormData)) {
        formData = new FormData();
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key]);
            }
        }
    }
    axios.post(url, formData, {
        headers: {
            'token': getToken(),
            'Content-Type': 'multipart/form-data'
        }
    }).then(function(res) {
        if (res.data.code === 200) {
            if (callback) callback(res.data);
        } else if (res.data.code === 401) {
            alert('登录已过期，请重新登录');
            top.window.location.href = 'index.html';
        } else {
            alert(res.data.msg || '操作失败');
        }
    }).catch(function(err) {
        alert('网络错误，请重试');
    });
}

function axiosGet(url, params, callback) {
    axios.get(url, {
        params: params || {},
        headers: { 'token': getToken() }
    }).then(function(res) {
        //alert("OK");
        if (res.data.code === 200) {
            if (callback) callback(res.data);
        } else if (res.data.code === 401) {
            alert('登录已过期，请重新登录');
            top.window.location.href = 'index.html';
        } else {
            alert(res.data.msg || '操作失败');
        }
    }).catch(function(err) {
        alert(err.message+'错误，请重试');
    });
}

function formatTimestamp(ts) {
    if (!ts) return '';
    var d = new Date(ts * 1000);
    return d.getFullYear() + '-' +
        String(d.getMonth() + 1).padStart(2, '0') + '-' +
        String(d.getDate()).padStart(2, '0') + ' ' +
        String(d.getHours()).padStart(2, '0') + ':' +
        String(d.getMinutes()).padStart(2, '0') + ':' +
        String(d.getSeconds()).padStart(2, '0');
}