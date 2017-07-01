define("wishsetting/WishSettingController", ["require", "pro", "global/controller/BaseController", "global/Broadcast", "global/model/BaseModel", "global/utils/Utils", "global/utils/Location", "global/model/GoodsModel", "global/model/WishSettingModel", "global/model/WishSettingCheckModel"],
function(e) {
    function t() {
        this.applySuper(arguments),
        this.checkModel = new S,
        this.init()
    }
    function i() {
        this.initListener(),
        this.view.msgbox = this.context.getParams("msgbox"),
        this.showSettingBox(this.context.getParams())
    }
    function n() {
        var e = this.view;
        this.listen(e, "btnOpenClick", this.onBtnOpenClick),
        this.listen(e, "btnSubmitClick", this.onBtnSubmitClick),
        this.listen(e, "btnThirdPartShareClick", this.onBtnThirdPartShareClick)
    }
    function s(e) {
        e = e || {};
        var t = this.view;
        app.isLogin() ? e.hasSet ? t.renderHasSetBox() : this.checkSetting(e) : app.login()
    }
    function o(e) {
        var t = this;
        this.checkModel.check({
            cid: app.getCID(),
            gid: e.gid
        },
        function(e) {
            t.onCheckSettingSuc(e)
        },
        function(e) {
            t.onCheckSettingErr(e)
        })
    }
    function r(e) {
        var t = this;
        this.model.create({
            gid: this.goodsModel.get("gid"),
            nickname: e.nickname,
            days: e.days,
            wishes: e.wishes,
            wishSourceId: b.getParam("wishSourceId")
        },
        function(e) {
            t.onSubmitSettingSuc(e)
        },
        function(e) {
            t.onSubmitSettingErr(e)
        })
    }
    function a() {
        this.view.renderStepSubmit(this.goodsModel)
    }
    function l(e) {
        var t = this.view,
        i = m.trim(e.nickname),
        n = m.trim(e.wishes);
        return "" === i ? void t.submitView.renderNickNameEmptyTip() : "" === n ? void t.submitView.renderWishesEmptyTip() : (t.submitView.renderBtnSubmitDisable(), void this.submitSetting({
            nickname: i,
            days: e.days,
            wishes: n
        }))
    }
    function d(e) {
        window.gShare.share({
            title: "【一元夺宝】",
            target: e.target,
            desc: "#一元夺宝心愿单# 各位亲朋好友，别再为送什么发愁了！礼物我挑好了，你们就慷慨解囊帮我凑个单，圆我一个心愿吧。千儿八百不嫌多，一元两元也是爱。爱心凑单请戳链接:" + e.shareUrl,
            link: e.shareUrl,
            site: "一元夺宝"
        })
    }
    function c(e) {
        e.hasIng ? this.view.renderHasOngoingBox() : (this.goodsModel = new v, this.goodsModel.set(e.goods), this.view.renderSettingBox())
    }
    function h(e) {
        var t = e && e.code;
        t == S.OUT_OF_STOCK ? this.view.renderOutOfStockBox() : this.view.renderFailToSetBox()
    }
    function u(e) {
        this.send(w.WISH_SETTING_SUBMIT_SUCCESS),
        this.trigger("onCreateSuccess"),
        this.view.renderStepShare({
            shareUrl: e.url,
            weixinCodeImg: e.weixinCode,
            yixinCodeImg: e.yixinCode,
            isFromUser: this.model.get("isFromUser") || !1,
            hasMobile: app.hasMobile()
        })
    }
    function g(e) {
        var t = e && e.code;
        t == f.NICKNAME_TOO_LONG ? this.view.submitView.renderNickNameTooLongTip() : t == f.HAS_ONGOING ? this.view.renderHasOngoingBox() : t == f.OUT_OF_STOCK ? this.view.renderOutOfStockBox() : this.view.renderFailToSetBox()
    }
    var p = (e("pro"), e("global/controller/BaseController")),
    w = e("global/Broadcast"),
    m = (e("global/model/BaseModel"), e("global/utils/Utils")),
    b = e("global/utils/Location"),
    v = e("global/model/GoodsModel"),
    f = e("global/model/WishSettingModel"),
    S = e("global/model/WishSettingCheckModel"),
    x = p.extend({
        constructor: t,
        statics: {},
        members: {
            init: i,
            initListener: n,
            showSettingBox: s,
            checkSetting: o,
            submitSetting: r,
            onBtnOpenClick: a,
            onBtnSubmitClick: l,
            onBtnThirdPartShareClick: d,
            onCheckSettingSuc: c,
            onCheckSettingErr: h,
            onSubmitSettingSuc: u,
            onSubmitSettingErr: g
        }
    });
    return x
}),
define("wishsetting/WishInput", ["require", "pro", "global/component/Base", "ui/Textarea"],
function(e) {
    var t = e("pro"),
    i = e("global/component/Base"),
    n = e("ui/Textarea"),
    s = i.extend({
        constructor: function() {
            this.applySuper(arguments);
            var e = this.__config;
            switch (e.type = e.type || "user", e.type) {
            case "user":
                e.placeholder = "请认真填写邀请朋友凑单的话语，成不成就看你写得好不好了。懒得写？请从下面4个模板里挑选~",
                e.items = [{
                    title: "江湖告急",
                    content: "男侠女侠，鄙人钱包告急啊，不用你两肋插刀，只求帮忙付点钱凑个单，我想要这个礼物！拜托拜托~~"
                },
                {
                    title: "甜蜜撒娇",
                    content: "你说，你的钱包就是我的钱包，我也是这么想的，嘻嘻~别让我失望哦"
                },
                {
                    title: "感情永恒",
                    content: "提钱伤感情，那咱就谈感情吧，现在我想要这个礼物，你不会拒绝我的吧！"
                },
                {
                    title: "威逼利诱",
                    content: "谁说感情不能用金钱来衡量呢？！真是不能忍！你看着给吧！"
                }];
                break;
            case "friend":
                e.placeholder = "这位大爷，您都准备赏钱了何不再给他/她捎句话？懒得写？请从下面4个模板里挑选",
                e.items = [{
                    title: "江湖告急",
                    content: "钱包告急还想要礼物啊，幸亏有我们这些好哥儿姐们！"
                },
                {
                    title: "甜蜜撒娇",
                    content: "买买买！亲，我怎么舍得让你失望呢"
                },
                {
                    title: "感情永恒",
                    content: "咱俩什么交情啊，一定会支持你的！"
                },
                {
                    title: "威逼利诱",
                    content: "爷赏你的，下次吃饭可不要忘了我！"
                }]
            }
            this.itemList = new t.List(e.items)
        },
        statics: {
            template: '<div class="w-wishes">					<div class="w-wishes-text" pro="textarea"></div>					<div class="w-wishes-tab" pro="tab"></div>				</div>',
            Tab: i.extend({
                statics: {
                    template: '<div class="w-wishes-tab-wrap"><a class="w-wishes-tab-item" href="javascript:void(0)">{{title}}</a></div>',
                    events: {
                        "@": "click"
                    },
                    data: function(e) {
                        return {
                            title: e.title,
                            content: e.content
                        }
                    }
                },
                members: {
                    select: function() {
                        this.addClass("selected"),
                        this.fire("select")
                    },
                    unselect: function() {
                        this.removeClass("selected"),
                        this.fire("unselect")
                    }
                }
            }),
            doms: {
                textarea: "@textarea",
                tab: "@tab"
            },
            listeners: {
                render: function(e) {
                    var t = this,
                    i = this.__config;
                    e.style.width = i.width ? i.width + "px": "auto";
                    var o = n.extend({
                        overrides: {
                            template: '<div class="w-input w-input-textarea"><textarea class="w-input-input" pro="input"{{#maxLength}} maxlength="{{maxLength}}"{{/maxLength}}{{#__rawPlaceholder}}{{#placeholder}} placeholder={{placeholder}}{{/placeholder}}{{/__rawPlaceholder}} >{{value}}</textarea></div>'
                        }
                    });
                    this.textarea = new o({
                        width: i.width - 18,
                        height: 70,
                        maxLength: 50,
                        text: "",
                        placeholder: i.placeholder
                    }).render(this.doms.textarea),
                    this.itemList.each(function(e, n) {
                        var o, r = e.get();
                        o = new s.Tab({
                            title: r.title,
                            content: r.content
                        }).join(t).render(t.doms.tab),
                        o.listen("click",
                        function() {
                            t.switchTo(this)
                        }),
                        o.listen("destroy",
                        function() {
                            t.textarea.destroy(),
                            t.textarea = null
                        }),
                        r.selected && (i.selected = n)
                    }),
                    this.switchTo(i.selected || null)
                }
            }
        },
        members: {
            getTextarea: function() {
                return this.textarea
            },
            getTab: function(e) {
                return this.getChild(e)
            },
            switchTo: function(e) {
                this.each(function(t, i) {
                    e === t || e === i ? (t.select(), this.fire("switch", i, t), this.textarea.setValue(e.model.get("content"))) : t.unselect()
                })
            },
            getValue: function() {
                return this.textarea.getValue()
            },
            setValue: function(e) {
                return this.textarea.setValue(e)
            },
            showTips: function(e, t) {
                this.textarea.showTips(e, t)
            },
            hideTips: function() {
                this.textarea.hideTips()
            }
        }
    });
    return s
}),
define("wishsetting/WishSettingSetupView", ["require", "pro", "global/view/BaseView"],
function(e) {
    var t = (e("pro"), e("global/view/BaseView")),
    i = t.extend({
        overrides: {
            template: ['<div pro="step1" class="w-wishSetting w-wishSetting-step1">', '   <div class="main">', '       <p class="txt-red w-wishSetting-title">心愿单，挑礼物圆心愿，让亲朋好友为您凑单！</p>', "   </div>", '   <div class="flow">', '       <div class="w-progressNode w-progressNode-3step">', '       <ol class="inner">', '           <li class="step step-1">', '               <p class="name">挑选礼物开启心愿单</p>', '               <div class="bg">1</div>', "           </li>", '           <li class="step step-2">', '               <div class="ln"></div>', '               <p class="name">发送付款链接给朋友<br/>求凑单</p>', '               <div class="bg">2</div>', "           </li>", '           <li class="step step-3">', '               <div class="ln"></div>', '               <p class="name">获得礼物/得到红包</p>', '               <div class="bg">3</div>', "           </li>", "       </ol>", "   </div>", "</div>", '<p class="alert">重要提醒：如果未能在设置时间内完成凑单，则心愿单失败。<br/>已筹的资金将转为红包充值到您的一元夺宝帐号。</p>', '<p class="btnBar"><button id="btnOpen" class="w-button w-button-main w-button-l">开启心愿单</button></p>', "</div>"].join(""),
            events: {
                "#btnOpen": "btnOpenClick"
            },
            entry: "@step1"
        }
    });
    return i
}),
define("wishsetting/WishSettingSubmitView", ["require", "pro", "global/view/BaseView", "ui/Button", "ui/Input", "ui/Select", "wishsetting/WishInput", "global/model/WishSettingDaysModel"],
function(e) {
    function t() {
        this.wishes.hideTips(),
        this.nickname.hideTips();
        var e = {
            nickname: this.nickname.getValue(),
            days: this.days.getValue(),
            wishes: this.wishes.getValue()
        };
        this.fire("btnSubmitClick", e)
    }
    function i() {
        this.getSettingDays(),
        this.nickname = new h({
            className: "nicknameInput",
            width: 260,
            value: app.getNickname()
        }).render(this.doms.nickname).join(this),
        this.wishes = new g({
            width: 410,
            type: "user"
        }).render(this.doms.wishes).join(this)
    }
    function n() {
        var e = this,
        t = new p;
        t.fetch({
            cid: app.getCID(),
            gid: this.parents[0].context.controller.goodsModel.get("gid")
        },
        function(t) {
            e.renderAvailableDays(t)
        },
        function() {
            e.renderAvailableDays({
                days: [1],
                defaultDay: 1
            })
        })
    }
    function s(e) {
        for (var t = [], i = 0, n = 0, s = e.days.length; s > n; n += 1) {
            var o = e.days[n];
            t.push({
                value: o,
                text: o + "天"
            }),
            o == e.defaultDay && (i = n)
        }
        this.days = new u({
            className: "nicknameInput",
            items: t,
            selected: i
        }).render(this.doms.days).join(this)
    }
    function o() {
        this.nickname.showTips("请填写昵称", "err"),
        this.wishes.hideTips()
    }
    function r() {
        this.wishes.showTips("请填写心愿寄语", "err"),
        this.nickname.hideTips()
    }
    function a() {
        this.doms.btnSubmit.disabled = !0,
        this.addClass("w-button-disabled", "#btnSubmit")
    }
    function l() {
        this.doms.btnSubmit.disabled = !0,
        this.removeClass("w-button-disabled", "#btnSubmit")
    }
    function d() {
        this.nickname.showTips("昵称输入过长~", "err"),
        this.wishes.hideTips(),
        this.renderBtnSubmitResume()
    }
    var c = (e("pro"), e("global/view/BaseView")),
    h = (e("ui/Button"), e("ui/Input")),
    u = e("ui/Select"),
    g = e("wishsetting/WishInput"),
    p = e("global/model/WishSettingDaysModel"),
    w = c.extend({
        overrides: {
            template: ['<div pro="step2" class="w-wishSetting w-wishSetting-step2">', '   <div class="main">', '       <p class="w-wishSetting-title">您的心愿单</p>', '       <p class="w-wishSetting-subtitle">您挑选的是：<b class="txt-red f-breakword">{{gname}}</b></p>', '       <div class="price">', '           <p>礼物价值：<span class="txt-red"><b>{{price}}</b>夺宝币</span></p>', "       </div>", "   </div>", '   <div class="w-form">', '       <div class="w-form-item">', '           <div class="w-form-name"><b>您的昵称：</b></div>', '           <div class="w-form-cont">', '               <span pro="nickname"></span><span class="txt-gray">让朋友知道是你哦~</span>', "           </div>", "       </div>", '       <div class="w-form-item">', '           <div class="w-form-name"><b>筹集期限：</b></div>', '           <div class="w-form-cont" pro="days"></div>', "       </div>", '       <div class="w-form-item">', '           <div class="w-form-name"><b>心愿寄语：</b></div>', '           <div class="w-form-cont" pro="wishes"></div>', "       </div>", "   </div>", '   <p class="btnBar"><button id="btnSubmit" class="w-button w-button-main w-button-l">提&nbsp;&nbsp;交</button></p>', "</div>"].join(""),
            doms: {
                nickname: "@nickname",
                days: "@days",
                wishes: "@wishes",
                btnSubmit: "#btnSubmit"
            },
            events: {
                "#btnSubmit": t
            },
            entry: "@step2",
            onCreate: i
        },
        members: {
            getSettingDays: n,
            renderAvailableDays: s,
            renderNickNameEmptyTip: o,
            renderWishesEmptyTip: r,
            renderBtnSubmitDisable: a,
            renderBtnSubmitResume: l,
            renderNickNameTooLongTip: d
        }
    });
    return w
}),
define("wishsetting/WishSettingShareView", ["require", "jquery", "pro", "global/view/BaseView"],
function(e) {
    function t() {
        var t = this;
        e(["jqureyzclip"],
        function() {
            t.renderBtnCopy()
        }),
        e(["http://mimg.127.net/hd/lib/share/share.min.js"],
        function() {})
    }
    function i() {
        var e = this,
        t = s(".w-wishlist-settingBox .w-msgbox-bd");
        t.find("[pro=btnCopy]").zclip({
            path: "http://mimg.127.net/p/yy/lib/swf/ZeroClipboard.swf",
            copy: function() {
                return t.find("[pro=urlInput]").val()
            },
            beforeCopy: function() {
                e.doms.urlInput.select()
            },
            afterCopy: function() {
                e.doms.copySuccessTip.style.display = "block"
            }
        })
    }
    function n(e) {
        var t = e.getAttribute("data-share");
        this.fire("btnThirdPartShareClick", t)
    }
    var s = e("jquery"),
    o = (e("pro"), e("global/view/BaseView")),
    r = o.extend({
        overrides: {
            template: ['<div pro="step3" class="w-wishSetting w-wishSetting-step3">', '   <div class="main">', '       <p class="w-wishSetting-title">恭喜您成功开启心愿单！</p>', '       <p class="w-wishSetting-subtitle">获得心愿礼物需要朋友们给力凑单，赶快召唤他们吧！</p>', "   </div>", '   <div class="w-wishSetting-share">', '       <p class="title">发送付款链接给您的朋友来凑单：</p>', '       <div class="url" pro="urlWrap">', '           <div class="w-input"><input pro="urlInput" class="w-input-input" type="text" value="{{shareUrl}}" readonly="readonly" style="width:406px;" /></div>', '           <a pro="btnCopy" class="w-button w-button-simple" href="javascript:void(0)">复制链接</a>', '           <p class="txt-suc copySuccess" style="display: none;" pro="copySuccessTip"><i class="ico ico-suc-s"></i>已复制，快粘贴给朋友吧~</p>', "       </div>", '       <p class="title">扫描二维码分享给朋友或朋友圈：</p>', '       <div class="code">', '           <div class="code-wrap">', '               <img pro="weixinCode" width="120" height="120" src="{{weixinCodeImg}}" />', '               <p class="code-title">微信扫一扫</p>', "           </div>", '           <div class="code-wrap">', '               <img pro="yixinCode" width="120" height="120" src="{{yixinCodeImg}}" />', '               <p class="code-title">易信扫一扫</p>', "           </div>", "       </div>", '       <div class="w-shareTo">', '           <span class="w-shareTo-txt">更多分享</span>', '           <ul class="w-shareTo-list" pro="shareTo">', '               <li><a data-share="weibo" class="w-shareTo-ico w-shareTo-weibo" href="javascript:void(0)" title="分享至新浪微博"></a></li>', '               <li><a data-share="qzone" class="w-shareTo-ico w-shareTo-qzone" href="javascript:void(0)" title="分享至QZone"></a></li>', '               <li><a data-share="tqq" class="w-shareTo-ico w-shareTo-tqq" href="javascript:void(0)" title="分享至腾讯微博"></a></li>', "           </ul>", "       </div>", "</div>", '<p class="btnBar"><a pro="viewWishList" class="w-button w-button-main w-button-l" href="/user/wishlist.do"{{#isFromUser}} target="_blank"{{/isFromUser}}>查看我的心愿单</a></p>', '{{#hasMobile}}<div style="height:20px"></div>{{/hasMobile}}', '{{^hasMobile}}<p class="txt-gray link"><a href="/user/profile.do#mobile" target="_blank">完善手机号信息</a>，轻松跟踪心愿单状态！</p>{{/hasMobile}}', '<p class="txt-gray tips">您还可以在“我的心愿单”里找到分享入口哦~</p>', "</div>"].join(""),
            doms: {
                btnCopy: "@btnCopy",
                shareTo: "@shareTo",
                urlInput: "@urlInput",
                copySuccessTip: "@copySuccessTip"
            },
            events: {
                "@shareTo a": n
            },
            entry: "@step3",
            onCreate: t
        },
        members: {
            renderBtnCopy: i
        }
    });
    return r
}),
define("wishsetting/WishSettingView", ["require", "pro", "global/view/BaseView", "ui/Button", "ui/Input", "ui/Select", "wishsetting/WishInput", "wishsetting/WishSettingSetupView", "wishsetting/WishSettingSubmitView", "wishsetting/WishSettingShareView"],
function(e) {
    function t() {
        var e = this;
        this.msgbox.entry.innerHTML = ['<p style="text-align:center; padding-bottom: 30px;"><b>该商品已被设置为您的心愿单礼物，无需重复设置</b></p>', '<div pro="operation" class="w-msgbox-ft"></div>'].join("");
        var t = this.msgbox.find("@operation");
        new c({
            text: "好&nbsp;&nbsp;的",
            onClick: function() {
                e.msgbox.destroy()
            }
        }).render(t).join(this).addClass("w-button-main"),
        new c({
            text: "查看我的心愿单",
            href: "/user/wishlist.do"
        }).render(t).join(this),
        this.msgbox.center()
    }
    function i() {
        var e = this;
        this.msgbox.entry.innerHTML = ['<p style="text-align:center; padding-bottom: 30px;"><b>您有一个心愿正在进行中！结束了这个才能开启新的心愿单哦~</b></p>', '<div pro="operation" class="w-msgbox-ft"></div>'].join("");
        var t = this.msgbox.find("@operation");
        new c({
            text: "好&nbsp;&nbsp;的",
            onClick: function() {
                e.msgbox.destroy()
            }
        }).render(t).join(this).addClass("w-button-main"),
        new c({
            text: "查看我的心愿单",
            href: "/user/wishlist.do"
        }).render(t).join(this),
        this.msgbox.center()
    }
    function n() {
        var e = this;
        this.msgbox.entry.innerHTML = ['<p style="text-align:center; padding-bottom: 30px;">抱歉，该商品暂不能设置为心愿单礼物，请重新挑选</p>', '<div pro="operation" class="w-msgbox-ft"></div>'].join("");
        var t = this.msgbox.find("@operation");
        new c({
            text: "好&nbsp;&nbsp;的",
            onClick: function() {
                e.msgbox.destroy()
            }
        }).render(t).join(this).addClass("w-button-main"),
        this.msgbox.center()
    }
    function s() {
        var e = this;
        this.msgbox.entry.innerHTML = ['<p style="text-align:center; padding-bottom: 30px;">设置失败，请稍后重试</p>', '<div pro="operation" class="w-msgbox-ft"></div>'].join("");
        var t = this.msgbox.find("@operation");
        new c({
            text: "好&nbsp;&nbsp;的",
            onClick: function() {
                e.msgbox.destroy()
            }
        }).render(t).join(this).addClass("w-button-main"),
        this.msgbox.center()
    }
    function o() {
        this.renderStepSetup()
    }
    function r() {
        var e = this,
        t = this.msgbox.entry;
        this.setupView = new h,
        this.setupView.render(t).join(this),
        this.setupView.listen("btnOpenClick",
        function() {
            e.fire("btnOpenClick")
        }),
        this.msgbox.center()
    }
    function a(e) {
        var t = this;
        this.submitView = new u({
            model: e
        }),
        this.submitView.join(this),
        this.submitView.renderBy(this.setupView.dom),
        this.submitView.listen("btnSubmitClick",
        function(e) {
            t.fire("btnSubmitClick", e)
        }),
        this.msgbox.center()
    }
    function l(e) {
        var t = this;
        this.shareView = new g({
            data: e
        }),
        this.shareView.join(this),
        this.shareView.renderBy(this.submitView.dom),
        this.shareView.listen("btnThirdPartShareClick",
        function(e) {
            t.fire("btnThirdPartShareClick", {
                target: e,
                shareUrl: t.model.get("url")
            })
        }),
        this.msgbox.center()
    }
    var d = (e("pro"), e("global/view/BaseView")),
    c = e("ui/Button"),
    h = (e("ui/Input"), e("ui/Select"), e("wishsetting/WishInput"), e("wishsetting/WishSettingSetupView")),
    u = e("wishsetting/WishSettingSubmitView"),
    g = e("wishsetting/WishSettingShareView"),
    p = d.extend({
        statics: {},
        members: {
            renderHasSetBox: t,
            renderHasOngoingBox: i,
            renderOutOfStockBox: n,
            renderFailToSetBox: s,
            renderSettingBox: o,
            renderStepSetup: r,
            renderStepSubmit: a,
            renderStepShare: l
        }
    });
    return p
}),
define("wishsetting/WishSetting", ["require", "pro", "global/module/BaseModule", "wishsetting/WishSettingController", "wishsetting/WishSettingView", "global/model/WishSettingModel"],
function(e) {
    function t() {
        var e = this;
        this.listen(this.controller, "onCreateSuccess",
        function() {
            e.callSpecial("onSuccess")
        })
    }
    var i = (e("pro"), e("global/module/BaseModule")),
    n = e("wishsetting/WishSettingController"),
    s = e("wishsetting/WishSettingView"),
    o = e("global/model/WishSettingModel"),
    r = i.extend({
        overrides: {
            name: "wishSetting",
            Controller: n,
            View: s,
            Model: o
        },
        statics: {
            msgboxClass: "w-wishlist-settingBox"
        },
        members: {
            listenSpecial: t
        }
    });
    return r
}),
define("common/Tongji", ["require", "global/utils/Utils"],
function(e) {
    var t = e("global/utils/Utils"),
    i = window.location.pathname,
    n = window.location.search,
    s = (window.location.href, "add_event"),
    o = "click";
    return {
        SEPARATOR: "_",
        PAGE_LOAD: "PageLoad",
        INDEX_MOD: "IndexMod",
        DETAIL_MOD: "DetailMod",
        ATTEND: "attend",
        ADD_CART: "addCart",
        GOTO_CART: "gotoCart",
        RULES: {
            Index: /^\/(?:index\.do)?$/,
            Reveal: "/results.html",
            ShareOrder: "/share.do",
            ShareOrderDetail: "/user/shareDetail.do",
            ShareEdit: "/user/shareAdd.do",
            GoodsDetail: {
                path: "/detail/",
                params: function() {
                    return {
                        gid: i.match(/\/(\d+)(?:\-\d+)\.html$/)[1]
                    }
                }
            },
            Discover: "",
            Shake: "",
            Personal: "/user/index.do",
            ParticipateRecord: "/user/duobao.do",
            WinRecord: "/user/win.do",
            MallOrderrecord: "/user/mallrecord.do",
            BonusList: "/user/bonus.do",
            Jewel: "/user/gems.do",
            WishRecord: "/user/wishlist.do",
            WishDetail: "/user/wishDetail.do",
            MyShareRecord: "/user/share.do",
            Setting: "/user/setting.do",
            AddressRecord: "/user/address.do",
            RechargeRecord: "/user/chargeRecord.do",
            ShoppingCart: "/cart/index.do",
            SubmitOrder: "/newpay/order/info.do",
            PayOrder: "/cashier/order/info.do",
            PayResult: "/newpay/order/result.do",
            Recharge: "/cashier/recharge/info.do",
            SearchResult: {
                path: "/search.do",
                params: function() {
                    return {
                        keyword: decodeURIComponent(n.match(/\?keyword=(.*)$/)[1] || "")
                    }
                }
            },
            Nav: "",
            Kind0: "/list.html",
            Kind1001: "/ten/index.html",
            Kind1: "/list/1-0-1-1.html",
            Kind2: "/list/2-0-1-1.html",
            Kind3: "/list/3-0-1-1.html",
            Kind4: "/list/4-0-1-1.html",
            Kind5: "/list/5-0-1-1.html",
            Kind6: "/list/6-0-1-1.html",
            Kind7: "/list/7-0-1-1.html",
            Kind8: "/list/8-0-1-1.html",
            Login: "",
            Register: ""
        },
        use: function() {
            this.sendPageLoad(),
            this.bindingClick()
        },
        matchPath: function() {
            function e(e) {
                var t = !1;
                return e && ("string" == typeof e && 0 == i.indexOf(e) ? t = !0 : "function" == typeof e && e(n) ? t = !0 : e.test && e.test(i) && (t = !0)),
                t
            }
            var t = this.RULES;
            for (var n in t) {
                var s = t[n];
                if (s.path && e(s.path) || e(s)) return {
                    key: n,
                    params: s.params && s.params()
                }
            }
        },
        getKey: function() {
            for (var e = [], t = 0, i = arguments.length; i > t; t++) e.push(arguments[t]);
            return e.join(this.SEPARATOR)
        },
        sendPageLoad: function() {
            var e = this.matchPath();
            e && this.send(this.getKey(this.PAGE_LOAD, e.key), e.params)
        },
        send: function(e, t) { (app.isRelease() || app.isTest()) && (Countly.inited ? Countly.add_event({
                key: e,
                segmentation: t
            }) : Countly.q.push([s, {
                key: e,
                segmentation: t
            }]))
        },
        bindingClick: function() {
            var e = this,
            i = document.documentElement;
            t.addEvent(i, o,
            function(t) {
                var n = t || window.event,
                s = n.srcElement || n.target;
                do {
                    var o = s.getAttribute("tj");
                    if (o) {
                        var r = e.parseAttr(o);
                        e.send(r.key, r.params)
                    }
                    s = s.parentNode
                } while ( s && s !== i )
            })
        },
        parseAttr: function(e) {
            for (var t = e.split("?"), i = t[0], n = (t[1] || "").split("&"), s = 0, o = n.length; o > s; s++) {
                var r = n[s].split("=");
                n[s][r[0]] = r[1]
            }
            return {
                key: i,
                params: n
            }
        }
    }
}),
define("index/IndexController", ["require", "pro", "global/Broadcast", "global/controller/BaseController", "global/model/PeriodModel", "global/utils/Location", "global/utils/Cookie", "wishsetting/WishSetting", "global/utils/Utils", "common/Tongji"],
function(e) {
    function t() {
        this.applySuper(arguments),
        this.init()
    }
    function i() {
        this.initListener(),
        this.initSlideShow(),
        this.initScroll(),
        this.initCountdown(),
        this.view.renderGoodsPictures(),
        this.view.renderOldIEAlert(),
        app.showScrollToTop(!0)
    }
    function n() {
        this.listen(this.view, "countdownFinish", this.onCountdownFinish),
        this.listen(this.view, "btnQuickBuyClick", this.onBtnQuickBuyClick),
        this.listen(this.view, "btnAddCartClick", this.onBtnAddCartClick),
        this.listen(this.view, "bannerSlideChange", this.onBannerSlideChange),
        this.listen(this.view, "bannerSlideClick", this.onBannerSlideClick)
    }
    function s() {
        this.initBanner(),
        this.view.renderNewestSlideShow(),
        this.view.renderNewestResultSlideShow()
    }
    function o() {
        var e = this.view;
        this.bannerShownFrames = [];
        var t = {
            listeners: {
                slideChange: function(t) {
                    e.trigger("bannerSlideChange", t, this)
                },
                slideCLick: function(t, i) {
                    e.trigger("bannerSlideClick", i)
                },
                afterrender: function() {
                    this.trigger("slideChange")
                }
            }
        };
        e.renderBannerSlideShow(t)
    }
    function r() {
        var e = this.view;
        e.renderRecordRankScroll(),
        e.renderShareScroll()
    }
    function a() {
        this.view.renderNewestWaitingCountdown()
    }
    function l() {
        this.view.renderBuy()
    }
    function d() {
        this.view.renderBtnWishSetting(),
        this.listen(this.view, "btnWishSettingClick", this.onBtnWishSettingClick),
        this.receive(v.WISH_SETTING_SUBMIT_SUCCESS, this.onWishSettingSubmitSuccess)
    }
    function c() {
        this.initBuy(),
        this.initWishSetting()
    }
    function h(e, t) {
        var i = this.view,
        n = new S;
        n.getWinner(t,
        function(t) {
            i.renderNewestWinner(e, t)
        },
        function() {
            i.renderNewestFailWinner(e)
        })
    }
    function u(e) {
        window.app.duo({
            gid: e.get("goods").gid,
            period: e.get("period"),
            num: e.get("defaultBuyTimes"),
            regularBuy: 1
        })
    }
    function g(e, t) {
        window.app.addToCart({
            gid: e.get("goods").gid,
            period: e.get("period"),
            num: e.get("defaultBuyTimes")
        },
        t)
    }
    function p(e, t) {
        this.currentWishSettingView = t,
        this.wishSetting = this.context.launchInMsgbox(x, e)
    }
    function w() {
        this.view.renderWishSetting(!0, this.currentWishSettingView),
        this.currentWishSettingView = null
    }
    function m(e, t) {
        var i = this.bannerShownFrames;
        if (i[t.frame]) i.length == t.model.get("listLength") && t.unlisten("slideChange");
        else {
            var n = "";
            n = e ? "click": "change";
            var s = t.itemsDom[t.frame],
            o = s.getAttribute("data-statistics").replace(/'/g, '"'),
            r = y.parseJSON(o);
            r.eventType = n,
            r.cid = app.getCID(),
            C.send("HomeBannerPromoteShow", r),
            i[t.frame] = !0
        }
    }
    function b(e) {
        var t = e.getAttribute("data-statistics").replace(/'/g, '"'),
        i = y.parseJSON(t);
        i.cid = app.getCID(),
        C.send("HomeBannerPromoteClick", i)
    }
    var v = (e("pro"), e("global/Broadcast")),
    f = e("global/controller/BaseController"),
    S = e("global/model/PeriodModel"),
    x = (e("global/utils/Location"), e("global/utils/Cookie"), e("wishsetting/WishSetting")),
    y = e("global/utils/Utils"),
    C = e("common/Tongji");
    return f.extend({
        constructor: t,
        statics: {},
        members: {
            init: i,
            initListener: n,
            initSlideShow: s,
            initBanner: o,
            initScroll: r,
            initCountdown: a,
            initBuy: l,
            initWishSetting: d,
            onGlobalDataReady: c,
            onCountdownFinish: h,
            onBtnQuickBuyClick: u,
            onBtnAddCartClick: g,
            onBtnWishSettingClick: p,
            onWishSettingSubmitSuccess: w,
            onBannerSlideChange: m,
            onBannerSlideClick: b
        }
    })
}),
define("index/IndexView", ["require", "jquery", "pro", "global/view/BaseView", "global/utils/Countdown", "ui/SlideShow", "ui/IntervalScroll", "global/utils/Lazyload", "global/model/PeriodModel"],
function(e) {
    function t(e) {
        var t = w(".m-index-promot");
        this.banner = f.from(t[0], e),
        this.banner.join(this),
        this.banner.start()
    }
    function i() {
        var e = w(".m-index-newGoods");
        e.length > 0 && f.from(e[0], {
            autoPlay: !1,
            hasNav: !1
        }).start()
    }
    function n() {
        var e = w(".m-index-newReveal"),
        t = !0,
        i = e.find("li div[data-status=2]").size();
        i > 0 && (t = !1),
        f.from(e[0], {
            autoPlay: t,
            perGroup: 2
        }).start()
    }
    function s() {
        var e = w(".m-index-recordRank .w-intervalScroll");
        e.length > 0 && S.from(e[0], {
            minLine: e.attr("data-minLine"),
            perLine: e.attr("data-perLine")
        }).scrollUp()
    }
    function o() {
        var e = w(".m-index-share .w-intervalScroll");
        e.length > 0 && S.from(e[0], {
            minLine: e.attr("data-minLine"),
            perLine: e.attr("data-perLine")
        }).scrollUp()
    }
    function r() {
        var e = this,
        t = "<b>{{m0}}</b><b>{{m1}}</b>:<b>{{s0}}</b><b>{{s1}}</b>:<b>{{ms0}}</b><b>{{ms1}}</b>";
        w("#newestResult").find(".w-goods-newReveal[data-status=2]").each(function() {
            var i = w(this),
            n = i.find(".w-countdown-nums"),
            s = i.attr("data-remaintime") - 0,
            o = i.attr("data-gid") - 0,
            r = i.attr("data-period") - 0;
            v.start({
                now: e.getApplication().getServerTime(),
                expires: s,
                interval: 40,
                onRun: function(e) {
                    n.html(m.template(t, {
                        m0: e.m.substring(0, 1),
                        m1: e.m.substring(1),
                        s0: e.s.substring(0, 1),
                        s1: e.s.substring(1),
                        ms0: e.ms.substring(0, 1),
                        ms1: e.ms.substring(1, 2)
                    }))
                },
                onFinish: function() {
                    i.find(".w-goods-counting").hide(),
                    e.fire("countdownFinish", i, {
                        gid: o,
                        period: r
                    })
                }
            })
        })
    }
    function a(e, t) {
        var i = ['<div class="w-goods-record">', '<p class="w-goods-owner f-txtabb">获得者：<a href="{{owner.url}}" title="{{owner.nickname}}(ID:{{owner.cid}})"><b>{{owner.nickname}}</b></a></p>', "<p>本期参与：{{cost}}人次</p>", "<p>幸运号码：{{luckyCode}}</p>", "</div>"].join("");
        e.find(".ico-label").removeClass("ico-label-revealing").addClass("ico-label-newReveal"),
        t.owner.url = "/user/index.do?cid=" + t.owner.cid,
        e.append(m.template(i, t))
    }
    function l(e) {
        e.find(".w-goods-error").show()
    }
    function d() {
        var e = this;
        w(".w-goodsList").on("click", ".w-goods-quickBuy, .w-button-addToCart",
        function() {
            var t = w(this),
            i = t.closest(".w-goods-ing").get(0),
            n = w(i),
            s = new y;
            return s.set({
                period: n.attr("data-period") - 0,
                existingTimes: n.attr("data-existingTimes") - 0,
                goods: {
                    gid: n.attr("data-gid"),
                    price: n.attr("data-price") - 0,
                    totalTimes: n.attr("data-totalTimes") - 0,
                    buyUnit: n.attr("data-buyUnit") - 0,
                    regularBuyMax: n.attr("data-regularBuyMax") - 0
                }
            }),
            t.hasClass("w-goods-quickBuy") ? e.fire("btnQuickBuyClick", s) : e.fire("btnAddCartClick", s, n.find(".w-goods-pic").find("img")[0]),
            !1
        })
    }
    function c() {
        var e = this;
        w(".w-wishSetEntry").parent(".w-goodsList-item").hover(function() {
            w(this).find(".w-wishSetEntry").show()
        },
        function() {
            w(this).find(".w-wishSetEntry").hide()
        }),
        w(".w-goodsList").on("click", ".w-wishSetEntry",
        function() {
            var t = {
                hasSet: w(this).attr("data-setted") - 0,
                gid: w(this).attr("data-gid") || 0,
                gname: w(this).parent(".w-goodsList-item").find(".w-goods-title a").attr("title") || "",
                price: w(this).attr("data-price") || 0
            };
            e.fire("btnWishSettingClick", t, w(this))
        })
    }
    function h(e, t) {
        e && (t.html('已设为心愿单<i class="ico ico-wishHeart ico-wishHeart-solid"></i>'), t.attr("data-setted", 1))
    }
    function u() {
        this.oLazyload = new x
    }
    function g() {
        var e = ['<div class="g-alert">', '<div class="g-wrap">', '<p>亲爱的小伙伴，为了更好地体验一元夺宝的乐趣，快跟你过时的浏览器说声后会无期。建议您 <a href="http://windows.microsoft.com/zh-cn/windows/upgrade-your-browser" target="_blank">升级IE浏览器</a></p>', "</div>", "</div>"].join("");
        try { (navigator.userAgent.indexOf("MSIE 6.0") > 0 || navigator.userAgent.indexOf("MSIE 7.0") > 0) && w(".g-header").prepend(e)
        } catch(t) {}
    }
    function p() {
        w(".w-msgbox").length > 0 || app.msgbox({
            className: "w-msgbox-intro",
            text: '<h3>什么是一元夺宝？</h3><a class="ruleLink" href="/helpcenter/1-1.html" target="_blank" title="了解规则">了解规则</a>',
            ok: !1
        })
    }
    var w = e("jquery"),
    m = e("pro"),
    b = e("global/view/BaseView"),
    v = e("global/utils/Countdown"),
    f = e("ui/SlideShow"),
    S = e("ui/IntervalScroll"),
    x = e("global/utils/Lazyload"),
    y = e("global/model/PeriodModel"),
    C = b.extend({
        statics: {},
        members: {
            renderBannerSlideShow: t,
            renderNewestSlideShow: i,
            renderNewestResultSlideShow: n,
            renderRecordRankScroll: s,
            renderShareScroll: o,
            renderNewestWaitingCountdown: r,
            renderNewestWinner: a,
            renderNewestFailWinner: l,
            renderBuy: d,
            renderBtnWishSetting: c,
            renderWishSetting: h,
            renderGoodsPictures: u,
            renderOldIEAlert: g,
            renderDuoBaoIntro: p
        }
    });
    return C
}),
define("index/Index", ["require", "pro", "global/module/BaseModule", "index/IndexController", "index/IndexView"],
function(e) {
    function t() {
        this.callSpecial("init", {})
    }
    var i = (e("pro"), e("global/module/BaseModule")),
    n = e("index/IndexController"),
    s = e("index/IndexView");
    return i.extend({
        overrides: {
            name: "index",
            Controller: n,
            View: s
        },
        members: {
            listenSpecial: t
        }
    })
}),
define("index-ftl",
function() {});