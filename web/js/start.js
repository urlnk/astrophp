// 全局变量
global = {
    uid: null,
    page: 1,
    focus: 1,
    overflow: 0,
    scroll: 0
}

ele = {
    searchForm: document.getElementById('search_form'),
    startDate: document.getElementById('element_id'),
    endDate: document.getElementById('element_id2'),
    log: document.getElementById('log'),
    consume: document.getElementById('consume'),
    section: document.getElementsByTagName('section')[0],
    consumeLst: document.getElementById('consumes_list'),
    logLst: document.getElementById('logs_list'),
}

elt = {
    logMsg: ele.log.getElementsByTagName('p')[0],
    consMsg: ele.consume.getElementsByTagName('p')[0],
    logMain: ele.log.getElementsByTagName('main')[0],
    consMain: ele.consume.getElementsByTagName('main')[0]
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
    AJAX = []
    global.scroll = 1
    elt.logMain.scrollTo(0, 0)
    setTimeout("setScroll()", 1000)
    global.overflow = 0

    uri = 'order'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
}

function showConsume() {
    ele.section.style.display = 'block'
    global.page = 1
    ele.searchForm.setAttribute('data-type', 1)
    AJAX = []
    global.scroll = 1
    elt.consMain.scrollTo(0, 0)
    setTimeout("setScroll()", 1000)
    global.overflow = 0

    uri = 'order'
    formData = {}
    formData['uid'] = global.uid
    formData['type'] = 1
    _.api( uri, formData )
}

function filterSubmit() {
    document.getElementsByTagName('section')[0].style.display = 'block'
    flt()
    global.page = 1
    global.overflow = 0
    global.scroll = 1
    type = ele.searchForm.getAttribute('data-type')
    uri = 'log'
    if (0 < type) {
        uri = 'consume'
        elt.consMain.scrollTo(0, 0)
    } else {
        elt.logMain.scrollTo(0, 0)
    }

    setTimeout("setScroll()", 1000)
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

function setScroll() {
    global.scroll = 0
}

function fix() {
    v = _.viewport()
    h = v.height
    m = h - 81
    elt.consMain.style.height = elt.logMain.style.height = m + 'px'
    console.log({h:h, m:m})
}

// 滚动条事件
document.body.onresize = fix
elt.logMain.onscroll = scroll
elt.consMain.onscroll = scroll

function scroll(e) {
    el = e.target
    scrollTop = el.scrollTop
    clientHeight = el.clientHeight
    height = el.scrollHeight - 144
    console.log({scrollTop:scrollTop, clientHeight:clientHeight, scrollHeight:el.scrollHeight, height:height, h:clientHeight + scrollTop})
    if (height <= clientHeight + scrollTop) {
        load()
    }
}

function load() {
    type = ele.searchForm.getAttribute('data-type')
    uri = 'log'
    loadInfo = elt.logMsg
    if (0 < type) {
        uri = 'consume'
        loadInfo = elt.consMsg
    }
    if (!global.scroll) {
        loadData(uri, null, loadInfo)
    }
}

function loadData(uri, formData, loadInfo) {
    formData = formData || {}
    formData['uid'] = global.uid
    key = uri + ':' + global.page + ':' + ele.startDate.value + ':' + ele.endDate.value
    console.log(key)
    console.log(AJAX)
    if ( ! global.overflow && ! AJAX[ key ] ) {
        AJAX[ key ] = 1
        loadInfo.innerHTML = '玩命加载中……'

        npt = ele.searchForm.getElementsByTagName( 'input' )
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
    console.log(AJAX)
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

    global.overflow = 0
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
            global.overflow = 1
            break
        case 3:
            alert(msg)
            return
        default:
            alert(code + ': ' + msg)
            return
    }

    if (global.page <= 1) {
        ele.logLst.innerHTML = ''
    }
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li><dfn>' + row.param_name + '</dfn><var>' + row.order_amount + '元</var><time>' + row.order_time + '</time></li>'

        ele.logLst.insertAdjacentHTML(position, html)
    }
    elt.logMsg.innerHTML = load_msg
    global.page++
    if (!global.overflow) {
        h1 = ele.logLst.clientHeight
        h2 = elt.logMsg.clientHeight
        h3 = h1 + h2
        h4 = elt.logMain.clientHeight
        if (h3 <= h4) {
            load()
        }
        console.log({h1:h1, h2:h2, h3:h3, h4:h4})
    }

    document.getElementsByTagName('section')[0].style.display = 'none'
    show('home', 'log')
    console.log(json)
}

function api_consume(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['consume']
    code = json.code
    msg = json.msg
    data = json.data
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

    if (global.page <= 1) {
        ele.consumeLst.innerHTML = ''
    }
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li><dfn>' + row.param_name + '</dfn><var>' + row.order_amount + '元</var><address>' + row.device_name + '</address><time>' + row.order_time + '</time></li>'

        ele.consumeLst.insertAdjacentHTML(position, html)
    }
    elt.consMsg.innerHTML = load_msg
    global.page++
    if (!global.overflow) {
        h1 = ele.consumeLst.clientHeight
        h2 = elt.consMsg.clientHeight
        h3 = h1 + h2
        h4 = elt.consMain.clientHeight
        if (h3 <= h4) {
            load()
        }
        console.log({h1:h1, h2:h2, h3:h3, h4:h4})
    }

    ele.section.style.display = 'none'
    show('home', 'consume')
    console.log(json)
}

// 高度修正
setTimeout("fix()", 1000)
