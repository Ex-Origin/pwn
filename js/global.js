!function(){const t=window,e=document.documentElement;if(e.classList.remove("no-js"),e.classList.add("js"),document.body.classList.contains("has-animations")){(window.sr=ScrollReveal()).reveal(".hero-title, .hero-paragraph, .hero-form",{duration:1e3,distance:"40px",easing:"cubic-bezier(0.5, -0.01, 0, 1.005)",origin:"bottom",interval:150})}const s=document.querySelectorAll(".is-moving-object");let n=0,i=0,a=0,o=0,r=0,l=e.clientWidth,c=e.clientHeight;s&&t.addEventListener("mousemove",function(t,e){let s=null,n=e;return(...e)=>{let i=Date.now();(!s||i-s>=n)&&(s=i,t.apply(this,e))}}(function(e){!function(e,s){n=e.pageX,i=e.pageY,a=t.scrollY,o=l/2-n,r=c/2-(i-a);for(let t=0;t<s.length;t++){const e=s[t].getAttribute("data-translating-factor")||20,n=s[t].getAttribute("data-rotating-factor")||20,i=s[t].getAttribute("data-perspective")||500;let a=[];s[t].classList.contains("is-translating")&&a.push("translate("+o/e+"px, "+r/e+"px)"),s[t].classList.contains("is-rotating")&&a.push("perspective("+i+"px) rotateY("+-o/n+"deg) rotateX("+r/n+"deg)"),(s[t].classList.contains("is-translating")||s[t].classList.contains("is-rotating"))&&(a=a.join(" "),s[t].style.transform=a,s[t].style.transition="transform 1s ease-out",s[t].style.transformStyle="preserve-3d",s[t].style.backfaceVisibility="hidden")}}(e,s)},150))}();

$(document).ready(function(){
    var converter = new showdown.Converter();

    $("article.markdown").each(function(){
        var raw = $(this).html();
        var html = converter.makeHtml(raw);
        $(this).html(html);
    });

    $("article.markdown pre code").each(function(){
        var content = $(this).html();

        content = content.replace(/&amp;lt;/g,'&lt;');
        content = content.replace(/&amp;gt;/g,'&gt;');
        
        $(this).html(content);
    });

    $("pre code").each(function(i, block) {
        hljs.highlightBlock(block);
    });

    $("article.markdown p").each(function(){
        var content = $(this).text();
        
        if(content.slice(0,2) == '> '){
            content = content.slice(2);
            var txt=document.createElement("blockquote");
            txt.innerHTML = content;
            $(this).after(txt);
            $(this).remove();
        }
    });

    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1050 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        // setTimeout(function() {
        //     $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        // }, 0);
    });

    /* Scroll To Top */
    var scrolltotop = {
        setting: {
            startline: 100,
            scrollto: 0,
            scrollduration: 1e3,
            fadeduration: [500, 100]
        },
        controlHTML: '<img style="width:50px" src="/images/scroll_to_top.png" />',
        // The offset from the bottom right corner
        controlattrs: {
            offsetx: 10,
            offsety: 20
        },
        anchorkeyword: "#top",
        state: {
            isvisible: !1,
            shouldvisible: !1
        },
        scrollup: function() {
            this.cssfixedsupport || this.$control.css({
                opacity: 0
            });
            var t = isNaN(this.setting.scrollto) ? this.setting.scrollto: parseInt(this.setting.scrollto);
            t = "string" == typeof t && 1 == jQuery("#" + t).length ? jQuery("#" + t).offset().top: 0,
            this.$body.animate({
                scrollTop: t
            },
            this.setting.scrollduration)
        },
        keepfixed: function() {
            var t = jQuery(window),
            o = t.scrollLeft() + t.width() - this.$control.width() - this.controlattrs.offsetx,
            s = t.scrollTop() + t.height() - this.$control.height() - this.controlattrs.offsety;
            this.$control.css({
                left: o + "px",
                top: s + "px"
            })
        },
        togglecontrol: function() {
            var t = jQuery(window).scrollTop();
            this.cssfixedsupport || this.keepfixed(),
            this.state.shouldvisible = t >= this.setting.startline ? !0 : !1,
            this.state.shouldvisible && !this.state.isvisible ? (this.$control.stop().animate({
                opacity: 0.7
            },
            this.setting.fadeduration[0]), this.state.isvisible = !0) : 0 == this.state.shouldvisible && this.state.isvisible && (this.$control.stop().animate({
                opacity: 0
            },
            this.setting.fadeduration[1]), this.state.isvisible = !1)
        },
        init: function() {
            jQuery(document).ready(function(t) {
                var o = scrolltotop,
                s = document.all;
                o.cssfixedsupport = !s || s && "CSS1Compat" == document.compatMode && window.XMLHttpRequest,
                o.$body = t(window.opera ? "CSS1Compat" == document.compatMode ? "html": "body": "html,body"),
                o.$control = t('<div id="topcontrol">' + o.controlHTML + "</div>").css({
                    position: o.cssfixedsupport ? "fixed": "absolute",
                    bottom: o.controlattrs.offsety,
                    right: o.controlattrs.offsetx,
                    opacity: 0,
                    cursor: "pointer"
                }).attr({
                    title: "Scroll to Top"
                }).click(function() {
                    return o.scrollup(),
                    !1
                }).appendTo("body"),
                document.all && !window.XMLHttpRequest && "" != o.$control.text() && o.$control.css({
                    width: o.$control.width()
                }),
                o.togglecontrol(),
                t('a[href="' + o.anchorkeyword + '"]').click(function() {
                    return o.scrollup(),
                    !1
                }),
                t(window).bind("scroll resize",
                function(t) {
                    o.togglecontrol()
                })
            })
        }
    };
    scrolltotop.init();
});
