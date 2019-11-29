// 全局变量
global = {
    page: server.page + 1,
    overflow: server.overflow
}

XHR = []
AJAX = []
RESP = []
DATA = {
    category: {
        '': {'': '子类'}
    }
}

// 配置
config = {
    api_host: '/',
    cdn_host: ''
}

// 显示条目详情
function show(obj) {
    document.getElementsByTagName('section')[0].style.display = 'block'
    a = document.getElementsByTagName('i')
    s = document.getElementsByTagName('s')
    v = document.getElementsByTagName('var')[0]
    for (i = 0; i < 3; i++) {
        a[i].innerHTML = ''
        s[i].innerHTML = ''
    }

    cash = obj.getAttribute('data-cash')
    if (cash) {
        c = JSON.parse(cash)
        a[0].innerHTML = '现金账户'
        a[1].innerHTML = c.order_amount + '元'
        a[2].innerHTML = c.balance + '元'
    }

    row = obj.getAttribute('data-row')
    if (row) {
        r = JSON.parse(row)
        s[0].innerHTML = '补贴账户'
        s[1].innerHTML = r.order_amount + '元'
        s[2].innerHTML = r.balance + '元'
    }

    amount = obj.getAttribute('data-amount')
    v.innerHTML = amount + '（元）'
}

// 切换过滤面板
function flt() {
    el = document.getElementById('filter')
    if (el.style.display != 'none') {
        el.style.display = 'none'
    } else {
        el.style.display = 'block'
    }
}

// 同步日期
function chg(el, idx) {
    idx = idx === undefined ? 0 : idx;
    document.getElementsByTagName('time')[idx].innerHTML = el.value
}

// 初始化日历
var cxCalendarApi, cxCalendarApi2;
$("#element_id").cxCalendar({}, function(api){
    cxCalendarApi = api;
});
$("#element_id2").cxCalendar({}, function(api){
    cxCalendarApi2 = api;
});

/*---- polyfill ------*/
if ( 'undefined' == typeof URLSearchParams ) {
    var URLSearchParams = function ( init ) {
        obj = new Object
        obj.data = {}

        obj.append = function ( key, value ) {
            obj.data[ key ] = value
        }

        obj.toString = function () {
            arr = []
            for ( key in obj.data ) {
                arr.push( key + '=' + encodeURIComponent( obj.data[ key ] ) )
            }
            return arr.join( '&' )
        }
        return obj
    }
}

var _ = function () {
}

_.viewport = function () {
    doc = document.body
    if ('BackCompat' == document.compatMode) {
        return {
            width: doc.clientWidth,
            height: doc.clientHeight,
            left: doc.scrollLeft,
            top: doc.scrollTop,
            scrollWidth: doc.scrollWidth,
            scrollHeight: doc.scrollHeight
        }
    }
    el = document.documentElement
    return {
        width: el.clientWidth,
        height: el.clientHeight,
        left: el.scrollLeft || doc.scrollLeft,
        top: el.scrollTop || doc.scrollTop,
        scrollWidth: el.scrollWidth,
        scrollHeight: el.scrollHeight
    }
}

_.api = function ( uri, formData, method, queryString, arg ) {
    method = method || 'get'
    method = method.toUpperCase()
    arg = arg || {}

    if ( 'GET' == method && ! queryString && formData ) {
        params = new URLSearchParams
        for ( pair in formData ) {
           params.append( pair, formData[ pair ] )
        }
        queryString = params.toString()
    }

    url = config.api_host + 'card/api/' + uri
    if ( queryString ) {
        url += '?' + queryString
    }

    uri = uri.replace( /\//, '_' )
    req = XHR[ uri ] = new XMLHttpRequest
    req.onreadystatechange = function () {
        if ( 4 == req.readyState ) {
            if ( 200 == req.status ) {
                eval( "json = " + req.responseText + "; _.api.run( json, '" + uri + "', '" + encodeURI(JSON.stringify(arg)) + "' )" )
            } else {
                alert( 'Problem retrieving data: ' + req.statusText )
            }
        }
    }
    req.open( method, url, true )
    if ( 'POST' == method ) {
        req.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' )
    }
    req.send( formData )
}

_.api.run = function (json, func, arg) {
    RESP[func] = json
    if (json) {
        if (3 < json.code) {
            alert(json.msg)

        } else {
            eval("api_" + func + "('" + arg + "')")
        }

    } else {
        str = JSON.stringify([json, func, arg])
        alert('_.api.run() ERROR: ' + str)
    }
}

// 滚动条事件
window.onscroll = scroll

function scroll() {
    viewport = _.viewport()
    scrollTop = viewport.top
    clientHeight = viewport.height
    if (scrollTop > clientHeight) {
        got_top.style.display = 'block'
    } else {
        got_top.style.display = 'none'
    }

    height = viewport.scrollHeight - 144
    console.log({scrollTop:scrollTop, clientHeight:clientHeight, height:height})
    if (height <= clientHeight + scrollTop) {
        loadData()
    }
}

function goTop(step) {
    step = step || 3
    scrollTop = _.viewport().top
    per = Math.ceil(scrollTop / step) + 1
    i = 1
    len = step + 1
    for (; i < len; i++) {
        y = scrollTop - per * i
        setTimeout("window.scrollTo(0, " + y + ")", 100 * i)
    }
    return false
}

function loadData() {
    uri = server.uri
    key = uri + ':' + global.page
    if ( server.count && ! global.overflow && ! AJAX[ key ] ) {
        AJAX[ key ] = 1
        load_info.innerHTML = '玩命加载中……'

        formData = {}
        npt = search_form.getElementsByTagName( 'input' )
        len = npt.length
        i = 0
        for ( ; i < len; i++ ) {
            el = npt[ i ]
            if ( el.name && '' !== el.value ) {
                formData[ el.name ] = el.value
            }
        }
        if ( 1 < global.page ) {
            formData.page = global.page
        }
        _.api( uri, formData )
    }
}

function api_consume(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['consume']
    code = json.code
    msg = json.msg
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            global.overflow = 1
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    data = json.data
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li ' + row.attr + ' onclick="show(this)" data-amount="' + row.order_amount + '">'
                + '<span></span>'
                + '<div><dt><h4>' + row.param_name + '</h4><time>' + row.order_time + '</time></dt>'
                    + '<dd><em>金额:' + row.order_amount + '元</em><cite>' + row.device_name + '</cite></dd>'
                + '</div>'
            + '</li>'

        items_list.insertAdjacentHTML(position, html)
    }
    load_info.innerHTML = load_msg
    global.page++
}

function api_log(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['log']
    code = json.code
    msg = json.msg
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            global.overflow = 1
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    data = json.data
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li>'
                + '<span class="chongzhi"></span>'
                + '<div><dt><h4>' + row.param_name + '</h4><time>' + row.order_time + '</time></dt>'
                    + '<dd><em>金额:' + row.order_amount + '元</em><cite></cite></dd>'
                + '</div>'
            + '</li>'

        items_list.insertAdjacentHTML(position, html)
    }
    load_info.innerHTML = load_msg
    global.page++
}
