let dom = {
    loginBtn: document.querySelector('#loginBtn'),
    loginPanel: document.querySelector('#loginPanel'),
    regBtn: document.querySelector('#regBtn'),
    regPanel: document.querySelector('#regPanel'),
    closeBtn: document.querySelector('#closeBtn'),
    closeReg: document.querySelector('#closeReg'),
    toLogin: document.querySelector('#toLogin'),
    toReg: document.querySelector('#toReg'),
    body: document.querySelector('body')
}
let method = {
    toggle(dom, status) {
        dom.style.display = status
    },
    setStyle(dom, attr, value) {
        dom.style[attr] = value
    }
}
// 打开登陆面板
dom.loginBtn.addEventListener('click', () => {
    method.toggle(dom.loginPanel, 'block')
    method.setStyle(dom.body, "overflowY", "hidden")
})
// 关闭登陆
dom.closeBtn.addEventListener('click', () => {
    method.toggle(dom.loginPanel, 'none')
    method.setStyle(dom.body, "overflowY", "scroll")
})
// 打开注册面板
dom.regBtn.addEventListener('click', () => {
    method.toggle(dom.regPanel, 'block')
    method.setStyle(dom.body, "overflowY", "hidden")
})
// 关闭注册
dom.closeReg.addEventListener('click', () => {
    method.toggle(dom.regPanel, 'none')
    method.setStyle(dom.body, "overflowY", "scroll")
})
// 登陆切换注册
dom.toReg.addEventListener('click', () => {
    method.toggle(dom.loginPanel, 'none')
    method.setStyle(dom.body, "overflowY", "scroll")
    method.toggle(dom.regPanel, 'block')
    method.setStyle(dom.body, "overflowY", "hidden")
})
// 注册切换登陆
dom.toLogin.addEventListener('click', () => {
    method.toggle(dom.regPanel, 'none')
    method.setStyle(dom.body, "overflowY", "scroll")
    method.toggle(dom.loginPanel, 'block')
    method.setStyle(dom.body, "overflowY", "hidden")
})