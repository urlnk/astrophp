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
    document.getElementsByTagName('tt')[0].innerHTML = el.value
    ele.info.setAttribute('data-sex', el.value)
}

// 同步日期
function chg(el, id) {
    document.getElementById(id).innerHTML = el.value
}

// 视频静音
function allClk() {
    if ('none' != ele.start.style.display) {
        ele.video.muted = false
    }
    ad.count = server.adFullscreen
    console.log('allClk')
}


/* 广告 */
var ad = function () {

}

var bg = function () {

}

ad.timeout = ad.interval = bg.interval = ad.interva2 = ad.url = null
ad.len = bg.len = 0
ad.index = bg.index = 0
ad.img = ele.section[5].getElementsByTagName('img')[0]
ad.dl = ele.start.getElementsByTagName('dl')
ad.isFullscreen = 0
ad.count = server.adFullscreen

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
        setTimeout('ad.hide()', 2000)
        s = 1
    } else {
        ad.show()
        ele.section[5].style.display = 'none'
    }
    console.log({s:s})
}

ad.show = function () {
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
    console.log({show:'show', url:url})
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

ad.change = function () {
    ad.url = url = server.ads[ad.index]
    if (eval('url.match(/\.(' + server.videoType + ')$/i)')) {
        if ('none' == ele.start.style.display) {
            ele.video.muted = true
        }
        ele.video.src = url
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
        ad.show()
        ad.count = server.adFullscreen + server.adHide
    }
    console.log({s:s, count:ad.count, isFullscreen:ad.isFullscreen})
}

ad.init()
bg.init()
