// 全局变量
global = {
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
    document.getElementById('query').focus()
    console.log(Date())
}

function detect(obj) {
    console.log(obj.value)
    document.getElementsByTagName('section')[0].style.display = 'block'

    uri = 'swipe'
    formData = {}
    formData['no'] = obj.value
    _.api( uri, formData, 'post' )
    obj.value = ''
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

    choice_user.style.display = 'block'
    start_screen.style.display = 'none'

    data = json.data
    users_list.innerHTML = ''
    len = data.length
    i = 0
    for (; i < len; i++) {
        row = data[i]

        html = '<li><a href="javascript:">' + row.user_name + '</a></li>'

        users_list.insertAdjacentHTML(position, html)
    }

    document.getElementsByTagName('section')[0].style.display = 'none'
    console.log(json)
}
