// 性别选择
function clk(el) {
    document.getElementsByTagName('section')[0].style.display = 'none';
    document.getElementsByTagName('tt')[0].innerHTML = document.getElementsByName('sex')[2].value = el.value;
}

// 同步日期
function chg(el) {
    document.getElementsByTagName('time')[0].innerHTML = el.value;
}

// 初始化日历
var cxCalendarApi;
$("#element_id").cxCalendar({}, function(api){
    cxCalendarApi = api;
});

// 性别选中
npt = document.getElementsByTagName('input')
for (var i = 0; i < 2; i++) {
    if (npt[5].value == npt[i].value) {
        npt[i].setAttribute('checked', 'checked')
        break
    }
}
