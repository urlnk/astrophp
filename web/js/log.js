// 显示条目详情
function showDetail(obj) {
    sec = ele.section[7]
    sec.style.display = 'block'
    a = sec.getElementsByTagName('i')
    s = sec.getElementsByTagName('s')
    v = sec.getElementsByTagName('var')[0]
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
        global.focus = 1
    } else {
        el.style.display = 'block'
        global.focus = 0
    }
}

// 性别选择
function clk(el) {
    ele.section[1].style.display = 'none'
    document.getElementsByTagName('tt')[1].innerHTML = el.value
    ele.info.setAttribute('data-sex', el.value)
}

// 同步日期
function chg(el, id) {
    document.getElementById(id).innerHTML = el.value
}

// 视频不静音和连续播放
function allClk() {
    ad.count = server.adFullscreen
    console.log({paused:ele.video.paused})
    if (eval('ad.url.match(/\.(' + server.videoType + ')$/i)')) {
        ele.video.muted = false
        if (ele.video.paused) {
            ele.video.play()
        }
        if ('none' != ele.start.style.display) {

        } else {
            // ele.video.muted = true
            // ele.video.pause()
        }

    } else {
        ele.video.pause()
    }
    console.log('allClk')
}

// 限制字符长度
function limit(element) {
    max = element.getAttribute('maxlength')
    if(element.value.length > max) {
        element.value = element.value.substr(0, max)
    }
    console.log({limit: max})
}

function maxlength(el) {
    limit(el)
    allClk()
}

// 隐藏键盘
function hideNum() {
    kb = document.querySelectorAll('.mykb-box')
    len = kb.length
    for (i = 0; i < len; i++) {
        kb[i].style.display = 'none'
    }

    kb = document.querySelectorAll('.virtualkeyboard')
    len = kb.length
    for (i = 0; i < len; i++) {
        kb[i].style.display = 'none'
    }
}

/* 广告 */
var ad = function () {

}

var bg = function () {

}

ad.timeout = ad.interval = bg.interval = ad.interva2 = ad.url = ad.intervalExit = null
ad.len = bg.len = 0
ad.index = bg.index = ad.urlIndex = 0
ad.img = ele.section[5].getElementsByTagName('img')[0]
ad.dl = ele.start.getElementsByTagName('dl')
ad.isFullscreen = 0
ad.count = server.adFullscreen
ad.countS = server.exitTime * 1000
ad.refresh = server.refresh
ad.durations = {}

bg.init = function () {
    bg.len = server.bg.length
    bg.index = 0
    bg.change()
    if (server.bgChange) {
        bg.interval = setInterval(bg.change, server.bgChange)
    }
}

bg.change = function () {
    url = server.bg[bg.index]
    if (url.match(/\//)) {
        url = 'url(' + url + ')'
    } else if(!url) {
        url = 'linear-gradient(to bottom right, #077CEC, #0970D5)'
    }
    document.getElementsByTagName('article')[0].style.backgroundImage = url
    bg.index++
    if (bg.index >= bg.len) {
        bg.index = 0
    }
}

ad.fullscreen = function () {
    s = 0
    if ('auto' != _.getStyle(ele.video, 'top') || document.fullscreenElement) {
        ad.hide()
        // setTimeout('ad.hide()', 2000)
        s = 1
    } else {
        ad.show()
    }
    console.log({s:s})
}

ad.show = function () {
    hideSection()
    url = ad.url
    ad.isFullscreen = 1
    if (eval('url.match(/\.(' + server.videoType + ')$/i)')) {
        if ('auto' == _.getStyle(ele.video, 'top')) { // 通过样式全屏
            ele.video.className = 'fullscreen'
        }
    } else {
        ele.section[5].style.display = 'block'
        ad.top()
    }
    if (server.adHide) {
        ad.timeout = setTimeout(ad.hide, server.adHide)
    }
    ele.query.blur()
    hideNum()
    console.log({show:'show', url:url, count:ad.count})
}

ad.hide = function () {
    ad.isFullscreen = 0
    ele.video.className = ''
    ele.section[5].style.display = 'none'
    // document.webkitCancelFullScreen()
    if (document.fullscreenElement) {
        document.exitFullscreen()
    }
    clearTimeout(ad.timeout)
    ad.count = server.adFullscreen
    ele.query.blur()
    hideNum()
    console.log('hide')
}

ad.init = function () {
    ad.len = server.ads.length
    ad.index = 0
    ad.change()
    if (server.adChange) {
        ad.interval = setInterval(ad.change, server.adChange)
    }
    if (server.countdown) {
        ad.interva2 = setInterval(ad.countdown, server.countdown)
    }
}

ad.duration = function () {
    ad.durations['_' + ad.urlIndex] = ele.video.duration
    console.log({'durations':ad.durations, 'duration':ele.video.duration, 'currentTime':ele.video.currentTime})
}

ad.change = function () {
    ad.url = url = server.ads[ad.index]
    ad.urlIndex = ad.index
    if (eval('url.match(/\.(' + server.videoType + ')$/i)')) {
        if ('none' == ele.start.style.display) {
            ele.video.muted = true
        }
        ele.video.src = url
        // ele.video.load()
        console.log({'duration':ele.video.duration, 'currentTime':ele.video.currentTime})
        ad.dl[0].style.display = 'none'
        ad.dl[1].style.display = 'block'
        ele.section[5].style.display = 'none'
        if (ad.isFullscreen) {
            if ('auto' == _.getStyle(ele.video, 'top')) { // 通过样式全屏
                ele.video.className = 'fullscreen'
            }
        } else if ('auto' != _.getStyle(ele.video, 'top')) { // 视频全屏功能
            ad.isFullscreen = 1
        }

    } else {
        ad.img.src = url
        ele.adLnk.style.backgroundImage = 'url(' + url + ')'
        // 全屏显示
        if (ad.isFullscreen) {
            ele.section[5].style.display = 'block'
        }
        ad.dl[0].style.display = 'block'
        ad.dl[1].style.display = 'none'
        ad.top()
        ele.video.pause()
    }

    ad.index++
    if (ad.index >= ad.len) {
        ad.index = 0
    }
    console.log({url:url})
}

ad.top = function () {
    h1 = _.viewport().height
    h2 = ad.img.clientHeight
    t = h3 = 0
    if (h2 && h1 >= h2) {
        h3 = h1 - h2
        t = h3 / 2 + 'px'
    }
    ele.section[5].style.paddingTop = t
    console.log({top:'top', h1:h1, h2:h2, h3:h3, t:t})
}

ad.countdown = function () {
    ad.count = ad.count - 1000
    s = 0
    if (0 >= ad.count) {
        s = -1
        clearTimeout(ad.timeout)
        ad.auto()
        ad.count = server.adFullscreen
        if ('none' == ele.start.style.display) {
            ad.count += ad.countS
        }
        if (ad.isFullscreen) {
            ad.count += server.adHide
        }
    }
    console.log({paused:ele.video.paused, s:s, count:ad.count, isFullscreen:ad.isFullscreen})
}

ad.countdownS = function () {
    ad.countS = ad.countS - 1000
    exitTime.innerHTML = ad.countS / 1000
    s = 0
    if (0 >= ad.countS) {
        s = -1
        ad.exit()
    }
    console.log({s:s, countS:ad.countS})
}

ad.auto = function () {
    ad.refresh--
    if ('none' == ele.start.style.display) {
        ad.countS = server.exitTime * 1000
        exitTime.innerHTML = server.exitTime
        ele.section[6].style.display = 'block'
        ad.intervalExit = setInterval(ad.countdownS, 1000)
    } else {
        ad.exit()
    }
}

// 退出当前操作界面，返回主屏幕
ad.exit = function () {
    if (server.refresh && 0 >= ad.refresh) {
        window.location.href = '/ka/init'
    }
    ad.save()
    hideDiv('start_screen')
    hideWidget()
    ad.show()
}

// 保留在当前操作界面
ad.save = function () {
    clearInterval(ad.intervalExit)
    ele.section[6].style.display = 'none'
}

ad.init()
bg.init()

window.oncontextmenu = function (event) {
    event.preventDefault()
}

// 数字键盘
$(".num-input").mynumkb()
$(".zh-input").virtualkeyboard()
