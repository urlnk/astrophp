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

// 同步日期
function chg(el, idx) {
    idx = idx === undefined ? 0 : idx;
    document.getElementsByTagName('time')[idx].innerHTML = el.value
}

// 初始化日历
var cxCalendarApi, cxCalendarApi2;
