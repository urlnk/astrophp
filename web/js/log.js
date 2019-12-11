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


/* 广告 */
var ad = function () {

}

var bg = function () {

}

ad.timeout = ad.interval = bg.interval = null
ad.len = bg.len = 0
ad.index = bg.index = 0
ad.img = ele.section[5].getElementsByTagName('img')[0]

bg.init = function () {
    bg.len = server.bg.length
    bg.index = 0
    bg.change()
    bg.interval = setInterval(bg.change, server.bgChange)
}

bg.change = function () {
    url = server.bg[bg.index]
    document.getElementsByTagName('article')[0].style.backgroundImage = 'url(' + url + ')'
    bg.index++
    if (bg.index >= bg.len) {
        bg.index = 0
    }
}

ad.show = function () {
    ele.section[5].style.display = 'block'
    ad.top()
    ad.timeout = setTimeout(ad.hide, server.adHide)
    console.log({h1:h1, h2:h2, h3:h3, t:t})
}

ad.hide = function () {
    ele.section[5].style.display = 'none'
    clearTimeout(ad.timeout)
}

ad.init = function () {
    ad.len = server.ads.length
    ad.index = 0
    ad.change()
    ad.interval = setInterval(ad.change, server.adChange)
}

ad.change = function () {
    url = server.ads[ad.index]
    ele.adLnk.style.backgroundImage = 'url(' + url + ')'
    ad.img.src = url
    ad.top()
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
}

ad.init()
bg.init()
