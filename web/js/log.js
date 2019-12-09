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
