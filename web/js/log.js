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
function chg(el, idx) {
    idx = idx === undefined ? 0 : idx;
    document.getElementsByTagName('time')[idx].innerHTML = el.value
    // console.log({idx:idx, val:el.value})
}

// 初始化日历
var cxCalendarApi, cxCalendarApi2, cxCalendarApi3;
