// 全局变量
global = {
    uid: null,
    page: 1,
    focus: 1,
}

ele = {
    searchForm: document.getElementById('search_form'),
    startDate: document.getElementById('element_id'),
    endDate: document.getElementById('element_id2'),
    log: document.getElementById('log')
}

elt = {
    logMsg: ele.log.getElementsByTagName('p')[0]
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

document.getElementById('query').focus()
interval = setInterval(keep, 500)

function keep() {
    if (global.focus) {
        document.getElementById('query').focus()
        console.log(Date())
    }
}

function detect(obj) {
    global.uid = null
    console.log(obj.value)
    document.getElementsByTagName('section')[0].style.display = 'block'
    article = document.getElementsByTagName('article')
    leng = article.length
    for (j = 0; j < leng; j++) {
        div = article[j].getElementsByTagName('div')
        len = div.length
        for (i = 0; i < len; i++) {
            div[i].style.display = 'none'
        }
    }

    cx = document.getElementsByClassName('cxcalendar')
    len = cx.length
    for (k = 0; k < len; k++) {
        cx[k].style.display = 'none'
    }

    cx = document.getElementsByClassName('cxcalendar_lock')
    len = cx.length
    for (k = 0; k < len; k++) {
        cx[k].style.display = 'none'
    }

    filter.style.display = 'none'
    start_screen.style.display = 'block'

    uri = 'swipe'
    formData = {}
    formData['no'] = obj.value
    _.api( uri, formData, 'post' )
    obj.value = ''
}

function choice(obj) {
    global.uid = obj.getAttribute('data-uid')
    choice_user.style.display = 'none'
    home.style.display = 'block'
    home.getElementsByTagName('b')[0].innerHTML = obj.getElementsByTagName('u')[0].innerHTML
}

function show(i, id) {
    back(i, id)
}

function showAccount() {
    document.getElementsByTagName('section')[0].style.display = 'block'

    uri = 'account'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData, 'post' )
}

function showLog() {
    document.getElementsByTagName('section')[0].style.display = 'block'
    global.page = 1
    ele.searchForm.setAttribute('data-type', 0)

    uri = 'order'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
}

function filterSubmit() {
    document.getElementsByTagName('section')[0].style.display = 'block'
    flt()
    global.page = 1
    type = ele.searchForm.getAttribute('data-type')
    uri = 'log'
    if (0 < type) {
        uri = 'consume'
    }
    formData = {}
    formData['uid'] = global.uid
    formData['start'] = ele.startDate.value
    formData['end'] = ele.endDate.value
    _.api( uri, formData )
    return false
}

function back(i, id) {
    o = document.getElementById(i)
    obj = document.getElementById(id)
    o.style.display = 'none'
    obj.style.display = 'block'
}

function exit() {
    global.uid = null
    back('home', 'start_screen')
}

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

    url = config.api_host + 'ka/api/' + uri
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
        if ( formData ) {
            params = new URLSearchParams
            for ( pair in formData ) {
               params.append( pair, formData[ pair ] )
            }
            queryString = params.toString()
        }
    }
    req.send( queryString )
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

function api_swipe(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['swipe']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    users_list.innerHTML = ''
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li><a href="javascript:" onclick="choice(this)" data-uid="' + row.user_id + '"><u>' + row.user_name + '</u><p>' + row.telephone + '</p><p>' + row.operator_name + '</p><p>' + row.organ_name + '</p></a></li>'

        users_list.insertAdjacentHTML(position, html)
    }

    if (len) {
        back('start_screen', 'choice_user')
    } else if(load_msg) {
        alert(load_msg)
    }
    document.getElementsByTagName('section')[0].style.display = 'none'
    console.log(json)
}

function api_account(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['account']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    dd = account.getElementsByTagName('dd')
    len = dd.length
    i = 0
    for (; i < len; i++) {
        row = dd[i]
        row.innerHTML = '-'
    }

    dd[0].innerHTML = data.card_code
    dd[1].innerHTML = data.effective_time
    dd[2].innerHTML = data.cash
    dd[3].innerHTML = data.subsidy
    dd[4].innerHTML = data.telephone || '无'

    document.getElementsByTagName('section')[0].style.display = 'none'
    show('home', 'account')
    console.log(json)
}

function api_order(arg) {
    arg = decodeURI(arg)
    arg = JSON.parse(arg)
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['order']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    time = document.getElementsByTagName('time')
    el = document.getElementById('element_id')
    el2 = document.getElementById('element_id2')
    len = 2
    i = 0
    for (; i < len; i++) {
        row = time[i]
        row.innerHTML = '-'
    }

    time[0].innerHTML = el.value = data.start_date
    time[1].innerHTML = el2.value = data.end_date
    el.setAttribute('data-start-date', data.start_date)
    el.setAttribute('data-end-date', data.end_date)
    el2.setAttribute('data-start-date', data.start_date)
    el2.setAttribute('data-end-date', data.end_date)

    $("#element_id").cxCalendar({}, function(api){
        cxCalendarApi = api;
    });
    $("#element_id2").cxCalendar({}, function(api){
        cxCalendarApi2 = api;
    });

    type = ele.searchForm.getAttribute('data-type')
    uri = 'log'
    if (0 < type) {
        uri = 'consume'
    }
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
    console.log(json)
    console.log(arg)
}

function api_log(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['log']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
        case 2:
            load_msg = msg
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    logs_lst = document.getElementById('logs_list')
    if (global.page <= 1) {
        logs_lst.innerHTML = ''
    }
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li><dfn>' + row.param_name + '</dfn><var>' + row.order_amount + '元</var><time>' + row.order_time + '</time></li>'

        logs_lst.insertAdjacentHTML(position, html)
    }
    elt.logMsg.innerHTML = load_msg
    global.page++

    document.getElementsByTagName('section')[0].style.display = 'none'
    show('home', 'log')
    console.log(json)
}
