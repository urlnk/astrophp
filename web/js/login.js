global.timeinterval = null
global.total = 0
btnSms0 = document.getElementById('btnSms')

/* 发送短信验证码 */
function sendsms(obj) {
    console.log(Date())
    uri = 'sms'
    no = document.forms['loginForm']['phone'].value
    oid = document.forms['loginForm']['oid'].value
    if (!isPhone(no)) {
        alert('请输入正确的手机号码')
        return false
    }

    global.total = 60
    global.timeinterval = setInterval(countdown, 1000)
    btnSms0.setAttribute('disabled', 'disabled')

    formData = {phone:no, oid:oid}
    _.api( uri, formData )
}

function isPhone(str) {
    return str.match(/^1\d{10}$/)
}

function countdown() {
    global.total--
    btnSms0.innerHTML = global.total + 's'
    console.log(global.total)
    if (global.total < 1) {
        clearInterval(global.timeinterval)
        btnSms0.innerHTML = '验证码'
        btnSms0.removeAttribute('disabled')
        console.log(Date())
    }
}

function api_sms(arg) {
    load_msg = ''
    json = RESP['sms']
    code = json.code
    msg = json.msg
    data = json.data
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

    console.log(json)
}
