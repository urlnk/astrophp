// 切换过滤面板
function flt() {
    el = document.getElementById('filter')
    if (el.style.display != 'none') {
        el.style.display = 'none'
    } else {
        el.style.display = 'block'
    }
}

// 同步日期
function chg(el, idx) {
    idx = idx === undefined ? 0 : idx;
    document.getElementsByTagName('time')[idx].innerHTML = el.value
}

// 初始化日历
var cxCalendarApi, cxCalendarApi2;
$("#element_id").cxCalendar({}, function(api){
    cxCalendarApi = api;
});
$("#element_id2").cxCalendar({}, function(api){
    cxCalendarApi2 = api;
});
