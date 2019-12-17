// 全局变量
global = {
    uid: null,
    oid: 0,
    page: 1,
    focus: 1,
    overflow: 0,
    scroll: 0,
    from: null,
    to: null,
    sign: null,
    total: 60,
    totals: [60,60],
    timeinterval: null,
    interval: [null, null],
    sms: 0,
    phoneChanged: 0,
    userObj: null
}

// 初始化日历
var cxCalendarApi, cxCalendarApi2, cxCalendarApi3;

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
    phone: document.getElementById('phone'),
    account: document.getElementById('account'),
    bindPhone: document.getElementById('bindPhone'),
    phoneTitle: document.getElementById('phoneTitle'),
    home: document.getElementById('home'),
    verify: document.getElementById('verify'),
    adLnk: document.getElementById('adLink'),
    start: document.getElementById('start_screen'),
    video: document.getElementById('video'),

    section: document.getElementsByTagName('section'),
    time: document.getElementsByTagName('time'),
    tt: document.getElementsByTagName('tt'),
    p: document.getElementsByTagName('p'),

    sex: document.getElementsByName('sex'),
    telephone: document.getElementsByName('phone'),
    code: document.getElementsByName('code'),
    verifies: document.getElementsByName('verify'),

    consumeLst: document.getElementById('consumes_list'),
    logLst: document.getElementById('logs_list'),
    usrLst: document.getElementById('users_list')
}

elt = {
    logMsg: ele.log.getElementsByTagName('p')[0],
    consMsg: ele.consume.getElementsByTagName('p')[0],
    logMain: ele.log.getElementsByTagName('main')[0],
    consMain: ele.consume.getElementsByTagName('main')[0],
    lossLinks: ele.loss.getElementsByTagName('a'),
    homeLinks: ele.home.getElementsByTagName('a'),
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
BTN = ['acc', 'rec', 'log', 'con', 'los', 'inf', 'pho', 'car']

// 配置
config = {
    api_host: '/',
    cdn_host: ''
}

ele.query.onfocus = function (e) {
	e.preventDefault()
	console.log('focus')
}
document.getElementById('query').focus()
interval = null
// setInterval(keep, server.interval)

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
    global.uid = global.oid = 0
    // console.log(obj.value)
    // document.getElementsByTagName('section')[0].style.display = 'block'
    hideSection(0)
    // 返回主屏幕
    hideDiv('start_screen')
    hideWidget()

    uri = 'swipe'
    formData = {}
    formData['no'] = obj.value
    formData['test'] = server.test
    _.api( uri, formData, 'post' )
    obj.value = ''
}

function choice(obj) {
    global.userObj = obj
    global.uid = obj.getAttribute('data-uid')
    global.oid = obj.getAttribute('data-oid')
    p = obj.getElementsByTagName('p')[0].innerHTML
    if (!p) {
        ele.section[2].style.display = 'block'
    }
    choice_user.style.display = 'none'
    if (global.sign) {
        showSign()
    } else {
        home.style.display = 'block'
    }
    home.getElementsByTagName('b')[0].innerHTML = obj.getElementsByTagName('u')[0].innerHTML
}

function show(obj, d) {
    d = d || 'block'
    if (obj) {
       obj.style.display = d
    }
}

function hide(obj) {
    if (obj) {
       obj.style.display = 'none'
    }
}

function sex() {
    ele.section[1].style.display = 'block'
}

function showAccount() {
    document.getElementsByTagName('section')[0].style.display = 'block'
    ele.telephone[0].value = ele.code[0].value = ''

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
    formData['identity_card'] = elt.infoNpt[2].value
    formData['address'] = elt.infoNpt[3].value
    formData['license_plate_no'] = elt.infoNpt[4].value
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

function showSignTip(id) {
    global.sign = id
    global.from = elt.homeLinks[id].getAttribute('data-id')
    ele.section[4].style.display = 'block'
	document.getElementById('query').focus()
}

function showSign() {
    ele.section[0].style.display = 'block'
    elt.homeLinks[global.sign].click()
    global.sign = null
}

function hideSection(idx) {
    len = ele.section.length
    for (i = 0; i < len; i++) {
        if (i !== idx) {
            ele.section[i].style.display = 'none'
        } else if (i === idx) {
            ele.section[i].style.display = 'block'
        }
    }
}

// 隐藏所有分区
function hideDiv(id) {
    article = document.getElementsByTagName('article')
    leng = article.length
    for (j = 0; j < leng; j++) {
        div = article[j].getElementsByTagName('div')
        len = div.length
        for (i = 0; i < len; i++) {
            ids = div[i].id
            if (id && ids == id) {
                div[i].style.display = 'block'
            } else{
                div[i].style.display = 'none'
            }
        }
    }
}


function hideWidget() {
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

    // 隐藏筛选日期
    filter.style.display = 'none'
}

function showSwipe() {
    global.sign = global.from = null
    tip('请将您的卡片放在读卡位置', '', 'swipe')
	document.getElementById('query').focus()
}

function showLogin(tip) {
    if (!tip) {
        global.sign = global.from = null
    }
    ele.telephone[1].value = ele.code[1].value = ''
    back('start_screen', 'login')
    global.focus = 0
    global.sms = 1
    ele.telephone[1].focus()
}

function hideLogin() {
    back('login', 'start_screen')
}

function login() {
    no = ele.telephone[1].value
    code = ele.code[1].value.trim()
    console.log({no:no, code:code})
    if (!isPhone(no)) {
        tip('请输入正确的手机号码', 'ele.telephone[1].focus()', 'tip-phone')
        return false
    } else if (4 != code.length) {
        tip('请正确输入验证码', 'ele.code[1].focus()', 'tip-verify')
        return false
    }

    uri = 'login'
    formData = {}
    formData['phone'] = no
    formData['code'] = code
    formData['captcha'] = server.captcha
    _.api( uri, formData, 'post' )
}

function bind() {
    no = ele.telephone[0].value
    code = ele.code[0].value.trim()
    console.log({no:no, code:code})
    if (!isPhone(no)) {
        tip('请输入正确的手机号码', 'ele.telephone[0].focus()', 'tip-phone')
        return false
    } else if (4 != code.length) {
        tip('请正确输入验证码', 'ele.code[0].focus()', 'tip-verify')
        return false
    }

    uri = 'phone'
    formData = {}
    formData['uid'] = global.uid
    formData['phone'] = no
    formData['code'] = code
    formData['captcha'] = server.captcha
    _.api( uri, formData, 'post' )
}

function showPhone(id, text) {
    if (!global.from) {
        global.from = id
    }
    global.focus = global.sms = 0
    if (text) {
        ele.bindPhone.innerHTML = text + '手机号'
        ele.bindPhone.setAttribute('data-title', text + '手机')
        ele.telephone[0].value = ele.code[0].value = ''
    }
    ele.phoneTitle.innerHTML = ele.bindPhone.getAttribute('data-title')
    hide(ele.section[2])
    hideDiv('phone')
}

function hidePhone() {
    global.focus = 1
    global.from = global.from || 'account'
    if ('account' == global.from && global.phoneChanged) {
        global.to = 'phone'
        global.phoneChanged = 0
        showAccount()
    } else {
        back('phone', global.from)
    }
    global.from = null
    console.log(global)
}

function hidePhoneTip() {
    global.from = null
    hide(ele.section[2])
}

function hideTip() {
    code = ele.section[3].getAttribute('data-code')
    hide(ele.section[3])
    ele.section[3].className = 'tip'
    if (code) {
        eval(code)
    }
}

function tip(info, code, cls) {
    if (cls) {
        cls += ' tip'
    } else {
        cls = 'tip'
    }
    ele.p[1].innerHTML = info
    ele.section[3].setAttribute('data-code', code)
    ele.section[3].className = cls
    show(ele.section[3])
}

/* 发送短信验证码 */
function sendsms(obj) {
    console.log(Date())
    idx = global.sms
    uri = 'sms'
    no = ele.telephone[idx].value
    oid = global.oid
    console.log({no:no})
    if (!isPhone(no)) {
        tip('请输入正确的手机号码', 'ele.telephone[' + idx + '].focus()', 'tip-phone')
        return false
    }

    global.totals[idx] = 60
    global.interval[idx] = setInterval(countdown, 1000)
    ele.verifies[idx].setAttribute('disabled', 'disabled')

    formData = {phone:no, oid:oid, find:1}
    formData['captcha'] = server.captcha
    _.api( uri, formData )
}

function isPhone(str) {
    return str.match(/^1\d{10}$/)
}

function countdown() {
    idx = global.sms
    global.totals[idx]--
    ele.verifies[idx].innerHTML = global.totals[idx] + 's'
    console.log(global.totals[idx])
    if (global.totals[idx] < 1) {
        clearInterval(global.interval[idx])
        ele.verifies[idx].innerHTML = '发送验证码'
        ele.verifies[idx].removeAttribute('disabled')
        console.log(Date())
    }
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
    console.log('showConsume')
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

function previous() {
    to = global.sms ? 'login' : 'start_screen'
    back('choice_user', to)
    if (global.sms) {
        global.focus = 0
    }
}

function back(i, id, cx) {
    console.log([i, id, cx])
    hideDiv(id)
    global.focus = 1
    hideWidget()
}

function exit() {
    global.uid = global.oid = 0
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
    allClk()
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

/**
 * 获取元素样式
 * @param {object} el - 元素对象
 * @param {string} [prop] - 属性名称
 * @returns {object} 样式对象
 */
_.getStyle = function ( el, prop ) {
    var style = false;
    if ( el.currentStyle ) {
        style = el.currentStyle;
    } else if ( window.getComputedStyle ) {
        style = document.defaultView.getComputedStyle( el, null ); //IE获取不了样式表定义
    }
    if ( prop ) {
        return style[ prop ];
    }
    return style;
};

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
    cls = ''
    json = RESP['swipe']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
            load_msg = msg
            break
        case 2:
            load_msg = msg
            cls = 'swipe'
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
        btn = BTN[i]
        userName = row.user_name || ''
        telephone = row.telephone || ''
        operatorName = row.operator_name || ''
        organName = row.organ_name || ''

        html = '<li><a class="btn-' + btn + '" href="javascript:" onclick="choice(this)" data-uid="' + row.user_id + '" data-oid="' + row.operator_id + '"><u>' + userName + '</u><p>' + telephone + '</p><p>' + operatorName + '</p><p>' + organName + '</p></a></li>'

        users_list.insertAdjacentHTML(position, html)
    }

    document.getElementsByTagName('section')[0].style.display = 'none'
    // console.log(json)
    global.focus = 1
    if (len) {
        global.sms = 0
        back('start_screen', 'choice_user')
        links = ele.usrLst.getElementsByTagName('a')
        if (1 == data.length) {
            links[0].click()
        }
        console.log([data.length, links])
    } else if(load_msg) {
        tip(load_msg, '', cls)
    }
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

    if(load_msg) {
        tip(load_msg)
        return false
    }

    dd[0].innerHTML = data.operator_name
    dd[1].innerHTML = data.organ_name
    dd[2].innerHTML = data.user_no
    dd[3].innerHTML = data.card_code
    dd[4].innerHTML = data.create_time
    dd[5].innerHTML = data.effective_time
    dd[6].innerHTML = data.cash
    dd[7].innerHTML = data.subsidy
    dd[8].innerHTML = data.telephone || '无'

    if (data.telephone) {
        ele.bindPhone.innerHTML = '换绑手机号'
        ele.bindPhone.setAttribute('data-title', '换绑手机')
    } else {
        ele.bindPhone.innerHTML = '绑定手机号'
        ele.bindPhone.setAttribute('data-title', '绑定手机')
    }

    document.getElementsByTagName('section')[0].style.display = 'none'
    back(global.to || 'home', 'account')
    global.to = null
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
    elt.lossLinks[1].className = ('LOST' == data.card_status) ? 'btn-log' : 'btn-los'

    ele.section[0].style.display = 'none'
    back('home', 'loss')
    if (load_msg) {
        setTimeout("tip(load_msg)", 500)
    }
    // console.log(json)
}

function api_phone(arg) {
    text = cls = ''
    json = RESP['phone']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            text = "hidePhone()"
            global.userObj.getElementsByTagName('p')[0].innerHTML = ele.telephone[0].value
            ele.telephone[0].value = ele.code[0].value = ''
            global.phoneChanged = 1
            break
        case 1:
            text = "ele.telephone[0].focus()"
            cls = 'tip-phone'
            break
        case 2:
            text = "ele.code[0].focus()"
            break
        case 3:
            text = "ele.code[0].focus()"
            cls = 'prompt-bind'
            break
        default:
            alert(code + ': ' + msg)
            return
    }

    if (msg) {
        tip(msg, text, cls)
    }
}

function api_login(arg) {
    text = cls = ''
    json = RESP['login']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            swipe()
            ele.telephone[1].value = ele.code[1].value = ''
            break
        case 1:
            text = "ele.telephone[1].focus()"
            cls = 'prompt-exit'
            break
        case 2:
            text = "ele.code[1].focus()"
            break
        case 3:
            text = "ele.code[0].focus()"
            cls = 'prompt-bind'
            break
        default:
            alert(code + ': ' + msg)
            return
    }

    if (msg) {
        tip(msg, text, cls)
    }
}

function swipe(arg) {
    position = 'beforeEnd'
    load_msg = ''
    json = RESP['login']
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
        btn = BTN[i]
        userName = row.user_name || ''
        telephone = row.telephone || ''
        operatorName = row.operator_name || ''
        organName = row.organ_name || ''

        html = '<li><a class="btn-' + btn + '" href="javascript:" onclick="choice(this)" data-uid="' + row.user_id + '" data-oid="' + row.operator_id + '"><u>' + userName + '</u><p>' + telephone + '</p><p>' + operatorName + '</p><p>' + organName + '</p></a></li>'

        users_list.insertAdjacentHTML(position, html)
    }

    ele.section[0].style.display = 'none'
    // console.log(json)
    global.focus = 1
    if (len) {
        back('login', 'choice_user')
        links = ele.usrLst.getElementsByTagName('a')
        if (1 == data.length) {
            links[0].click()
        }
        console.log([data.length, links])
    } else if(load_msg) {
        tip(load_msg, text)
    }
}

function api_sms(arg) {
    load_msg = text = cls = ''
    json = RESP['sms']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            break
        case 1:
            load_msg = msg
            text = "ele.telephone[global.sms].focus()"
            cls = 'prompt-exit'
            break
        case 2:
            load_msg = msg
            text = "ele.code[global.sms].focus()"
            cls = 'prompt-exit'
            break
        case 3:
            load_msg = msg
            cls = 'tip-sms'
            break
        default:
            alert(code + ': ' + msg)
            return
    }

    if(load_msg) {
        tip(load_msg, text, cls)
    }
    console.log(json)
}

function api_info(arg) {
    load_msg = text = ''
    json = RESP['info']
    code = json.code
    msg = json.msg
    data = json.data
    switch (code) {
        case 0:
            home.getElementsByTagName('b')[0].innerHTML = global.userObj.getElementsByTagName('u')[0].innerHTML = data.user_name
            break
        case 1:
            text = 'elt.infoNpt[0].focus()'
            break
        case 2:
            text = 'sex()'
            break
        case 3:
            text = 'cxCalendarApi3.show()'
            break
        default:
            alert(code + ': ' + msg)
            return
    }

    elt.infoNpt[0].value = data.user_name
    elt.infoNpt[1].value = data.birthday
    elt.infoNpt[2].value = data.identity_card
    elt.infoNpt[3].value = data.address
    elt.infoNpt[4].value = data.license_plate_no
    ele.birthdays.innerHTML = data.birthday || '点击选择'
    if (data.sex) {
        ele.tt[1].innerHTML = data.sex
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
    back('home', 'info')
    global.focus = 0
    cxCalendarApi3 = null
    $("#element_id3").cxCalendar({}, function(api){
        cxCalendarApi3 = api;
    });
    if (msg) {
        setTimeout("tip(msg, text)", 500)
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
    /*
    el.setAttribute('data-start-date', data.start_date)
    el.setAttribute('data-end-date', data.end_date)
    el2.setAttribute('data-start-date', data.start_date)
    el2.setAttribute('data-end-date', data.end_date)
    */

    cxCalendarApi = null
    cxCalendarApi2 = null

    $("#element_id").cxCalendar({
        startDate: data.start_date,
        endDate: data.end_date
    }, function(api){
        cxCalendarApi = api;
    });

    $("#element_id2").cxCalendar({
        startDate: data.start_date,
        endDate: data.end_date
    }, function(api){
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
    back('home', 'log', 1)
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

        html = '<li ' + row.attr + ' onclick="showDetail(this)" data-amount="' + row.order_amount + '"><dfn>' + row.param_name + '</dfn><var>' + row.order_amount + '元</var><address>' + row.device_name + '</address><time>' + row.order_time + '</time></li>'

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
    back('home', 'consume', 1)
    // console.log(json)
}

function showRecharge() {
	back('home', 'recharge')
	ele.section[0].style.display = 'none'
}

// 高度修正
setTimeout("fix()", 1000)
