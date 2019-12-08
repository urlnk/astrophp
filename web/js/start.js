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
    birthday: document.getElementById('element_id3'),
    birthdays: document.getElementById('birthdays'),
    query: document.getElementById('query'),
    log: document.getElementById('log'),
    consume: document.getElementById('consume'),
    loss: document.getElementById('loss'),
    info: document.getElementById('info'),
    section: document.getElementsByTagName('section'),
    time: document.getElementsByTagName('time'),
    tt: document.getElementsByTagName('tt'),
    sex: document.getElementsByName('sex'),
    consumeLst: document.getElementById('consumes_list'),
    logLst: document.getElementById('logs_list')
}

elt = {
    logMsg: ele.log.getElementsByTagName('p')[0],
    consMsg: ele.consume.getElementsByTagName('p')[0],
    logMain: ele.log.getElementsByTagName('main')[0],
    consMain: ele.consume.getElementsByTagName('main')[0],
    lossLinks: ele.loss.getElementsByTagName('a'),
    infoNpt: ele.info.getElementsByTagName('input')
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
interval = setInterval(keep, server.interval)

// 保持聚焦，便于刷卡
function keep() {
    if (global.focus) {
        document.getElementById('query').focus()
        // console.log(Date())
        if (server.hta && ele.query.value) {
            global.focus = 0
            detect(ele.query)
        }
    }
}

function detect(obj) {
    global.uid = null
    // console.log(obj.value)
    document.getElementsByTagName('section')[0].style.display = 'block'

    // 隐藏所有分区
    article = document.getElementsByTagName('article')
    leng = article.length
    for (j = 0; j < leng; j++) {
        div = article[j].getElementsByTagName('div')
        len = div.length
        for (i = 0; i < len; i++) {
            div[i].style.display = 'none'
        }
    }

    // 隐藏日历
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

    // 隐藏筛选日期，返回主屏幕
    filter.style.display = 'none'
    start_screen.style.display = 'block'

    uri = 'swipe'
    formData = {}
    formData['no'] = obj.value
    formData['test'] = server.test
    _.api( uri, formData, 'post' )
    obj.value = ''
}

function choice(obj) {
    global.uid = obj.getAttribute('data-uid')
    choice_user.style.display = 'none'
    home.style.display = 'block'
    home.getElementsByTagName('b')[0].innerHTML = obj.getElementsByTagName('u')[0].innerHTML
}

function show(i, id, cx) {
    back(i, id, cx)
}

function sex() {
    ele.section[1].style.display = 'block'
}

function showAccount() {
    document.getElementsByTagName('section')[0].style.display = 'block'

    uri = 'account'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData, 'post' )
}

function showInfo() {
    ele.section[0].style.display = 'block'

    uri = 'info'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
}

function info() {
    ele.section[0].style.display = 'block'

    uri = 'info'
    formData = {}
    formData['uid'] = global.uid
    formData['sex'] = ele.info.getAttribute('data-sex')
    formData['user_name'] = elt.infoNpt[0].value
    formData['birthday'] = elt.infoNpt[1].value
    // console.log(formData)
    _.api( uri, formData, 'post' )
}

function showLoss() {
    ele.section[0].style.display = 'block'

    uri = 'loss'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
}

function loss() {
    ele.section[0].style.display = 'block'

    uri = 'loss'
    formData = {}
    formData['uid'] = global.uid
    formData['card_status'] = ele.loss.getAttribute('data-status')
    _.api( uri, formData, 'post' )
}

function showLog() {
    document.getElementsByTagName('section')[0].style.display = 'block'
    global.page = 1
    ele.searchForm.setAttribute('data-type', 0)
    AJAX = []
    global.scroll = 1
    // elt.logMain.scrollTo(0, 0)
    ele.logLst.innerHTML = elt.logMsg.innerHTML = ''
    setTimeout("setScroll()", 1000)
    global.overflow = 0

    uri = 'order'
    formData = {}
    formData['uid'] = global.uid
    _.api( uri, formData )
}

function showConsume() {
    ele.section[0].style.display = 'block'
    global.page = 1
    ele.searchForm.setAttribute('data-type', 1)
    AJAX = []
    global.scroll = 1
    // elt.consMain.scrollTo(0, 0)
    ele.consumeLst.innerHTML = elt.consMsg.innerHTML = ''
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
    // global.scroll = 1
    AJAX = []
    type = ele.searchForm.getAttribute('data-type')
    uri = 'log'
    if (0 < type) {
        uri = 'consume'
        // elt.consMain.scrollTo(0, 0)
        ele.consumeLst.innerHTML = elt.consMsg.innerHTML = ''
    } else {
        // elt.logMain.scrollTo(0, 0)
        ele.logLst.innerHTML = elt.logMsg.innerHTML = ''
    }

    setTimeout("setScroll()", 1000)
    formData = {}
    formData['uid'] = global.uid
    formData['start'] = ele.startDate.value
    formData['end'] = ele.endDate.value
    _.api( uri, formData )
    return false
}

function back(i, id, cx) {
    o = document.getElementById(i)
    obj = document.getElementById(id)
    o.style.display = 'none'
    obj.style.display = 'block'
    global.focus = 1

    // 移除多余日历
    div = document.getElementsByTagName('div')
    len = div.length -1
    for (i = len; i > 0; i--) {
        el = div[i]
        cls = el.className
        if ('cxcalendar' == cls || 'cxcalendar_lock' == cls) {
            if (!server.hta && !cx) {
                el.remove()
            }
        }
    }
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
    // console.log({h:h, m:m})
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
    // console.log({scrollTop:scrollTop, clientHeight:clientHeight, scrollHeight:el.scrollHeight, height:height, h:clientHeight + scrollTop})
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
    // console.log({scroll:global.scroll})
    if (!global.scroll) {
        loadData(uri, null, loadInfo)
    }
}

function loadData(uri, formData, loadInfo) {
    formData = formData || {}
    formData['uid'] = global.uid
    key = uri + ':' + global.page + ':' + ele.startDate.value + ':' + ele.endDate.value
    // console.log({key:key, ajax:AJAX, k:AJAX[ key ], overflow:global.overflow})
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
    // console.log(AJAX)
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
    queryString = queryString || ''
    arg = arg || {}
    d = new Date()
    time = d.getTime()

    if ( 'GET' == method && ! queryString && formData ) {
        formData['_'] = time
        params = new URLSearchParams
        for ( pair in formData ) {
           params.append( pair, formData[ pair ] )
        }
        queryString = params.toString()
    }

    if ( 'POST' == method ) {
        queryString += '&_=' + time
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
        userName = row.user_name || ''
        telephone = row.telephone || ''
        operatorName = row.operator_name || ''
        organName = row.organ_name || ''

        html = '<li><a href="javascript:" onclick="choice(this)" data-uid="' + row.user_id + '"><u>' + userName + '</u><p>' + telephone + '</p><p>' + operatorName + '</p><p>' + organName + '</p></a></li>'

        users_list.insertAdjacentHTML(position, html)
    }

    if (len) {
        back('start_screen', 'choice_user')
    } else if(load_msg) {
        alert(load_msg)
    }
    document.getElementsByTagName('section')[0].style.display = 'none'
    // console.log(json)
    global.focus = 1
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
    // console.log(json)
}

function api_loss(arg) {
    load_msg = ''
    json = RESP['loss']
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

    dd = ele.loss.getElementsByTagName('dd')
    dd[0].innerHTML = data.card_code
    dd[1].innerHTML = data.param_name
    ele.loss.setAttribute('data-status', data.card_status)
    elt.lossLinks[1].innerHTML = ('LOST' == data.card_status) ? '解挂' : '挂失'

    ele.section[0].style.display = 'none'
    show('home', 'loss')
    if (load_msg) {
        setTimeout("alert(load_msg)", 500)
    }
    // console.log(json)
}

function api_info(arg) {
    load_msg = ''
    json = RESP['info']
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

    elt.infoNpt[0].value = data.user_name
    elt.infoNpt[1].value = data.birthday
    ele.birthdays.innerHTML = data.birthday || '点击选择'
    if (data.sex) {
        ele.tt[0].innerHTML = data.sex
        ele.info.setAttribute('data-sex', data.sex)
    }

    // 性别选中
    npt = ele.sex
    for (i = 0; i < 2; i++) {
        if (data.sex == npt[i].value) {
            npt[i].setAttribute('checked', 'checked')
            break
        }
    }

    ele.birthday.setAttribute('data-end-date', data.today)
    ele.birthday.setAttribute('data-start-date', data.year - 100)

    ele.section[0].style.display = 'none'
    show('home', 'info')
    global.focus = 0
    $("#element_id3").cxCalendar({}, function(api){
        cxCalendarApi3 = api;
    });
    if (load_msg) {
        setTimeout("alert(load_msg)", 500)
    }
    // console.log(json)
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
    // console.log(json)
    // console.log(arg)
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
            global.scroll = 0
            load()
        }
        // console.log({h1:h1, h2:h2, h3:h3, h4:h4})
    }

    document.getElementsByTagName('section')[0].style.display = 'none'
    show('home', 'log', 1)
    // console.log(json)
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
            global.scroll = 0
            load()
        }
        // console.log({h1:h1, h2:h2, h3:h3, h4:h4})
    }

    ele.section[0].style.display = 'none'
    show('home', 'consume', 1)
    // console.log(json)
}

// 高度修正
setTimeout("fix()", 1000)
