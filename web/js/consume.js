// 显示条目详情
function show(obj) {
    document.getElementsByTagName('section')[0].style.display = 'block'
    a = document.getElementsByTagName('i')
    s = document.getElementsByTagName('s')
    v = document.getElementsByTagName('var')[0]
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
