"undefined"==typeof jwplayer&&(jwplayer=function(f){
    if(jwplayer.api)return jwplayer.api.selectPlayer(f)
        },jwplayer.version="6.5.3609",jwplayer.vid=document.createElement("video"),jwplayer.audio=document.createElement("audio"),jwplayer.source=document.createElement("source"),function(f){
    function a(h){
        return function(){
            return d(h)
            }
        }
    var k=document,e=window,c=navigator,b=f.utils=function(){};
    
    b.exists=function(h){
    switch(typeof h){
        case "string":
            return 0<h.length;
        case "object":
            return null!==h;
        case "undefined":
            return!1
            }
            return!0
    };
b.styleDimension=function(h){
    return h+(0<h.toString().indexOf("%")?"":"px")
    };
    
b.getAbsolutePath=function(h,a){
    b.exists(a)||(a=k.location.href);
    if(b.exists(h)){
        var d;
        if(b.exists(h)){
            d=h.indexOf("://");
            var c=h.indexOf("?");
            d=0<d&&(0>c||c>d)
            }else d=void 0;
        if(d)return h;
        d=a.substring(0,a.indexOf("://")+3);
        var c=a.substring(d.length,a.indexOf("/",d.length+1)),e;
        0===h.indexOf("/")?e=h.split("/"):(e=a.split("?")[0],e=e.substring(d.length+c.length+1,e.lastIndexOf("/")),e=e.split("/").concat(h.split("/")));
        for(var g=[],m=0;m<e.length;m++)e[m]&&(b.exists(e[m])&&"."!=e[m])&&(".."==e[m]?g.pop():g.push(e[m]));
        return d+c+"/"+g.join("/")
        }
    };

b.extend=function(){
    var a=b.extend.arguments;
    if(1<a.length){
        for(var d=1;d<a.length;d++)b.foreach(a[d],function(d,c){
            try{
                b.exists(c)&&(a[0][d]=c)
                }catch(e){}
        });
    return a[0]
    }
    return null
};

b.log=function(a,b){
    "undefined"!=typeof console&&"undefined"!=typeof console.log&&(b?console.log(a,b):console.log(a))
    };
    
var d=b.userAgentMatch=function(a){
    return null!==c.userAgent.toLowerCase().match(a)
    };
b.isIE=a(/msie/i);
b.isFF=a(/firefox/i);
b.isChrome=a(/chrome/i);
b.isIOS=a(/iP(hone|ad|od)/i);
b.isIPod=a(/iP(hone|od)/i);
b.isIPad=a(/iPad/i);
b.isSafari602=a(/Macintosh.*Mac OS X 10_8.*6\.0\.\d* Safari/i);
b.isAndroid=function(a){
    return a?d(RegExp("android.*"+a,"i")):d(/android/i)
    };
    
b.isMobile=function(){
    return b.isIOS()||b.isAndroid()
    };
    
b.saveCookie=function(a,b){
    k.cookie="jwplayer."+a+"\x3d"+b+"; path\x3d/"
    };
    
b.getCookies=function(){
    for(var a={},b=k.cookie.split("; "),d=0;d<b.length;d++){
        var c=b[d].split("\x3d");
        0==c[0].indexOf("jwplayer.")&&(a[c[0].substring(9,c[0].length)]=c[1])
        }
        return a
    };
    
b.typeOf=function(a){
    var b=typeof a;
    return"object"===b?!a?"null":a instanceof Array?"array":b:b
    };
    
b.translateEventResponse=function(a,d){
    var c=b.extend({},d);
    a==f.events.JWPLAYER_FULLSCREEN&&!c.fullscreen?(c.fullscreen="true"==c.message?!0:!1,delete c.message):"object"==typeof c.data?(c=b.extend(c,c.data),delete c.data):"object"==typeof c.metadata&&b.deepReplaceKeyName(c.metadata,["__dot__","__spc__","__dsh__","__default__"],
        ["."," ","-","default"]);
    b.foreach(["position","duration","offset"],function(a,b){
        c[b]&&(c[b]=Math.round(1E3*c[b])/1E3)
        });
    return c
    };
    
b.flashVersion=function(){
    if(b.isAndroid())return 0;
    var a=c.plugins,d;
    try{
        if("undefined"!==a&&(d=a["Shockwave Flash"]))return parseInt(d.description.replace(/\D+(\d+)\..*/,"$1"))
            }catch(n){}
    if("undefined"!=typeof e.ActiveXObject)try{
        if(d=new ActiveXObject("ShockwaveFlash.ShockwaveFlash"))return parseInt(d.GetVariable("$version").split(" ")[1].split(",")[0])
            }catch(f){}
        return 0
    };
b.getScriptPath=function(a){
    for(var b=k.getElementsByTagName("script"),d=0;d<b.length;d++){
        var c=b[d].src;
        if(c&&0<=c.indexOf(a))return c.substr(0,c.indexOf(a))
            }
            return""
    };
    
b.deepReplaceKeyName=function(a,d,c){
    switch(f.utils.typeOf(a)){
        case "array":
            for(var e=0;e<a.length;e++)a[e]=f.utils.deepReplaceKeyName(a[e],d,c);
            break;
        case "object":
            b.foreach(a,function(b,g){
            var e;
            if(d instanceof Array&&c instanceof Array){
                if(d.length!=c.length)return;
                e=d
                }else e=[d];
            for(var n=b,k=0;k<e.length;k++)n=n.replace(RegExp(d[k],
                "g"),c[k]);
            a[n]=f.utils.deepReplaceKeyName(g,d,c);
            b!=n&&delete a[b]
        })
        }
        return a
    };
    
var n=b.pluginPathType={
    ABSOLUTE:0,
    RELATIVE:1,
    CDN:2
};

b.getPluginPathType=function(a){
    if("string"==typeof a){
        a=a.split("?")[0];
        var d=a.indexOf("://");
        if(0<d)return n.ABSOLUTE;
        var c=a.indexOf("/");
        a=b.extension(a);
        return 0>d&&0>c&&(!a||!isNaN(a))?n.CDN:n.RELATIVE
        }
    };

b.getPluginName=function(a){
    return a.replace(/^(.*\/)?([^-]*)-?.*\.(swf|js)$/,"$2")
    };
    
b.getPluginVersion=function(a){
    return a.replace(/[^-]*-?([^\.]*).*$/,"$1")
    };
b.isYouTube=function(a){
    return-1<a.indexOf("youtube.com")||-1<a.indexOf("youtu.be")
    };
    
b.isRtmp=function(a,b){
    return 0==a.indexOf("rtmp")||"rtmp"==b
    };
    
b.foreach=function(a,b){
    var d,c;
    for(d in a)a.hasOwnProperty(d)&&(c=a[d],b(d,c))
        };
        
b.isHTTPS=function(){
    return 0==e.location.href.indexOf("https")
    };
    
b.repo=function(){
    var a="http://p.jwpcdn.com/"+f.version.split(/\W/).splice(0,2).join("/")+"/";
    try{
        b.isHTTPS()&&(a=a.replace("http://","https://ssl."))
        }catch(d){}
    return a
    }
}(jwplayer),function(f){
    var a="video/",
    k=f.foreach,e={
        mp4:a+"mp4",
        vorbis:"audio/ogg",
        ogg:a+"ogg",
        webm:a+"webm",
        aac:"audio/mp4",
        mp3:"audio/mpeg",
        hls:"application/vnd.apple.mpegurl"
    },c={
        mp4:e.mp4,
        f4v:e.mp4,
        m4v:e.mp4,
        mov:e.mp4,
        m4a:e.aac,
        f4a:e.aac,
        aac:e.aac,
        mp3:e.mp3,
        ogv:e.ogg,
        ogg:e.vorbis,
        oga:e.vorbis,
        webm:e.webm,
        m3u8:e.hls,
        hls:e.hls
        },a="video",a={
        flv:a,
        f4v:a,
        mov:a,
        m4a:a,
        m4v:a,
        mp4:a,
        aac:a,
        f4a:a,
        mp3:"sound",
        smil:"rtmp",
        m3u8:"hls",
        hls:"hls"
    },b=f.extensionmap={};
    
    k(c,function(a,c){
        b[a]={
            html5:c
        }
    });
k(a,function(a,c){
    b[a]||(b[a]={});
    b[a].flash=
    c
    });
b.types=e;
b.mimeType=function(a){
    var b;
    k(e,function(c,e){
        !b&&e==a&&(b=c)
        });
    return b
    };
    
b.extType=function(a){
    return b.mimeType(c[a])
    }
}(jwplayer.utils),function(f){
    var a=f.loaderstatus={
        NEW:0,
        LOADING:1,
        ERROR:2,
        COMPLETE:3
    },k=document;
    f.scriptloader=function(e){
        function c(){
            d=a.ERROR;
            h.sendEvent(n.ERROR)
            }
            function b(){
            d=a.COMPLETE;
            h.sendEvent(n.COMPLETE)
            }
            var d=a.NEW,n=jwplayer.events,h=new n.eventdispatcher;
        f.extend(this,h);
        this.load=function(){
            var h=f.scriptloader.loaders[e];
            if(h&&(h.getStatus()==
                a.NEW||h.getStatus()==a.LOADING))h.addEventListener(n.ERROR,c),h.addEventListener(n.COMPLETE,b);
            else if(f.scriptloader.loaders[e]=this,d==a.NEW){
                d=a.LOADING;
                var p=k.createElement("script");
                p.addEventListener?(p.onload=b,p.onerror=c):p.readyState&&(p.onreadystatechange=function(){
                    ("loaded"==p.readyState||"complete"==p.readyState)&&b()
                    });
                k.getElementsByTagName("head")[0].appendChild(p);
                p.src=e
                }
            };
        
    this.getStatus=function(){
        return d
        }
    };

f.scriptloader.loaders={}
}(jwplayer.utils),function(f){
    f.trim=function(a){
        return a.replace(/^\s*/,
            "").replace(/\s*$/,"")
        };
        
    f.pad=function(a,f,e){
        for(e||(e="0");a.length<f;)a=e+a;
        return a
        };
        
    f.xmlAttribute=function(a,f){
        for(var e=0;e<a.attributes.length;e++)if(a.attributes[e].name&&a.attributes[e].name.toLowerCase()==f.toLowerCase())return a.attributes[e].value.toString();return""
        };
        
    f.extension=function(a){
        if(!a||"rtmp"==a.substr(0,4))return"";
        a=a.substring(a.lastIndexOf("/")+1,a.length).split("?")[0].split("#")[0];
        if(-1<a.lastIndexOf("."))return a.substr(a.lastIndexOf(".")+1,a.length).toLowerCase()
            };
    f.stringToColor=function(a){
        a=a.replace(/(#|0x)?([0-9A-F]{3,6})$/gi,"$2");
        3==a.length&&(a=a.charAt(0)+a.charAt(0)+a.charAt(1)+a.charAt(1)+a.charAt(2)+a.charAt(2));
        return parseInt(a,16)
        }
    }(jwplayer.utils),function(f){
    f.key=function(a){
        var k,e,c;
        this.edition=function(){
            return c&&c.getTime()<(new Date).getTime()?"invalid":k
            };
            
        this.token=function(){
            return e
            };
            
        f.exists(a)||(a="");
        try{
            a=f.tea.decrypt(a,"36QXq4W@GSBV^teR");
            var b=a.split("/");
            (k=b[0])?/^(free|pro|premium|ads)$/i.test(k)?(e=b[1],b[2]&&0<parseInt(b[2])&&
                (c=new Date,c.setTime(String(b[2])))):k="invalid":k="free"
            }catch(d){
            k="invalid"
            }
        }
}(jwplayer.utils),function(f){
    var a=f.tea={};
    
    a.encrypt=function(c,b){
        if(0==c.length)return"";
        var d=a.strToLongs(e.encode(c));
        1>=d.length&&(d[1]=0);
        for(var n=a.strToLongs(e.encode(b).slice(0,16)),h=d.length,f=d[h-1],p=d[0],q,j=Math.floor(6+52/h),g=0;0<j--;){
            g+=2654435769;
            q=g>>>2&3;
            for(var m=0;m<h;m++)p=d[(m+1)%h],f=(f>>>5^p<<2)+(p>>>3^f<<4)^(g^p)+(n[m&3^q]^f),f=d[m]+=f
                }
                d=a.longsToStr(d);
        return k.encode(d)
        };
        
    a.decrypt=function(c,
        b){
        if(0==c.length)return"";
        for(var d=a.strToLongs(k.decode(c)),n=a.strToLongs(e.encode(b).slice(0,16)),h=d.length,f=d[h-1],p=d[0],q,j=2654435769*Math.floor(6+52/h);0!=j;){
            q=j>>>2&3;
            for(var g=h-1;0<=g;g--)f=d[0<g?g-1:h-1],f=(f>>>5^p<<2)+(p>>>3^f<<4)^(j^p)+(n[g&3^q]^f),p=d[g]-=f;
            j-=2654435769
            }
            d=a.longsToStr(d);
        d=d.replace(/\0+$/,"");
        return e.decode(d)
        };
        
    a.strToLongs=function(a){
        for(var b=Array(Math.ceil(a.length/4)),d=0;d<b.length;d++)b[d]=a.charCodeAt(4*d)+(a.charCodeAt(4*d+1)<<8)+(a.charCodeAt(4*d+
            2)<<16)+(a.charCodeAt(4*d+3)<<24);
        return b
        };
        
    a.longsToStr=function(a){
        for(var b=Array(a.length),d=0;d<a.length;d++)b[d]=String.fromCharCode(a[d]&255,a[d]>>>8&255,a[d]>>>16&255,a[d]>>>24&255);
        return b.join("")
        };
        
    var k={
        code:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/\x3d",
        encode:function(a,b){
            var d,n,h,f,p=[],q="",j,g,m=k.code;
            g=("undefined"==typeof b?0:b)?e.encode(a):a;
            j=g.length%3;
            if(0<j)for(;3>j++;)q+="\x3d",g+="\x00";
            for(j=0;j<g.length;j+=3)d=g.charCodeAt(j),n=g.charCodeAt(j+
                1),h=g.charCodeAt(j+2),f=d<<16|n<<8|h,d=f>>18&63,n=f>>12&63,h=f>>6&63,f&=63,p[j/3]=m.charAt(d)+m.charAt(n)+m.charAt(h)+m.charAt(f);
            p=p.join("");
            return p=p.slice(0,p.length-q.length)+q
            },
        decode:function(a,b){
            b="undefined"==typeof b?!1:b;
            var d,f,h,r,p,q=[],j,g=k.code;
            j=b?e.decode(a):a;
            for(var m=0;m<j.length;m+=4)d=g.indexOf(j.charAt(m)),f=g.indexOf(j.charAt(m+1)),r=g.indexOf(j.charAt(m+2)),p=g.indexOf(j.charAt(m+3)),h=d<<18|f<<12|r<<6|p,d=h>>>16&255,f=h>>>8&255,h&=255,q[m/4]=String.fromCharCode(d,f,
                h),64==p&&(q[m/4]=String.fromCharCode(d,f)),64==r&&(q[m/4]=String.fromCharCode(d));
            r=q.join("");
            return b?e.decode(r):r
            }
        },e={
    encode:function(a){
        a=a.replace(/[\u0080-\u07ff]/g,function(a){
            a=a.charCodeAt(0);
            return String.fromCharCode(192|a>>6,128|a&63)
            });
        return a=a.replace(/[\u0800-\uffff]/g,function(a){
            a=a.charCodeAt(0);
            return String.fromCharCode(224|a>>12,128|a>>6&63,128|a&63)
            })
        },
    decode:function(a){
        a=a.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g,function(a){
            a=(a.charCodeAt(0)&15)<<12|
            (a.charCodeAt(1)&63)<<6|a.charCodeAt(2)&63;
            return String.fromCharCode(a)
            });
        return a=a.replace(/[\u00c0-\u00df][\u0080-\u00bf]/g,function(a){
            a=(a.charCodeAt(0)&31)<<6|a.charCodeAt(1)&63;
            return String.fromCharCode(a)
            })
        }
    }
}(jwplayer.utils),function(f){
    f.events={
        COMPLETE:"COMPLETE",
        ERROR:"ERROR",
        API_READY:"jwplayerAPIReady",
        JWPLAYER_READY:"jwplayerReady",
        JWPLAYER_FULLSCREEN:"jwplayerFullscreen",
        JWPLAYER_RESIZE:"jwplayerResize",
        JWPLAYER_ERROR:"jwplayerError",
        JWPLAYER_SETUP_ERROR:"jwplayerSetupError",
        JWPLAYER_MEDIA_BEFOREPLAY:"jwplayerMediaBeforePlay",
        JWPLAYER_MEDIA_BEFORECOMPLETE:"jwplayerMediaBeforeComplete",
        JWPLAYER_COMPONENT_SHOW:"jwplayerComponentShow",
        JWPLAYER_COMPONENT_HIDE:"jwplayerComponentHide",
        JWPLAYER_MEDIA_BUFFER:"jwplayerMediaBuffer",
        JWPLAYER_MEDIA_BUFFER_FULL:"jwplayerMediaBufferFull",
        JWPLAYER_MEDIA_ERROR:"jwplayerMediaError",
        JWPLAYER_MEDIA_LOADED:"jwplayerMediaLoaded",
        JWPLAYER_MEDIA_COMPLETE:"jwplayerMediaComplete",
        JWPLAYER_MEDIA_SEEK:"jwplayerMediaSeek",
        JWPLAYER_MEDIA_TIME:"jwplayerMediaTime",
        JWPLAYER_MEDIA_VOLUME:"jwplayerMediaVolume",
        JWPLAYER_MEDIA_META:"jwplayerMediaMeta",
        JWPLAYER_MEDIA_MUTE:"jwplayerMediaMute",
        JWPLAYER_MEDIA_LEVELS:"jwplayerMediaLevels",
        JWPLAYER_MEDIA_LEVEL_CHANGED:"jwplayerMediaLevelChanged",
        JWPLAYER_CAPTIONS_CHANGED:"jwplayerCaptionsChanged",
        JWPLAYER_CAPTIONS_LIST:"jwplayerCaptionsList",
        JWPLAYER_PLAYER_STATE:"jwplayerPlayerState",
        state:{
            BUFFERING:"BUFFERING",
            IDLE:"IDLE",
            PAUSED:"PAUSED",
            PLAYING:"PLAYING"
        },
        JWPLAYER_PLAYLIST_LOADED:"jwplayerPlaylistLoaded",
        JWPLAYER_PLAYLIST_ITEM:"jwplayerPlaylistItem",
        JWPLAYER_PLAYLIST_COMPLETE:"jwplayerPlaylistComplete",
        JWPLAYER_DISPLAY_CLICK:"jwplayerViewClick",
        JWPLAYER_CONTROLS:"jwplayerViewControls",
        JWPLAYER_INSTREAM_CLICK:"jwplayerInstreamClicked",
        JWPLAYER_INSTREAM_DESTROYED:"jwplayerInstreamDestroyed",
        JWPLAYER_AD_TIME:"jwplayerAdTime",
        JWPLAYER_AD_ERROR:"jwplayerAdError",
        JWPLAYER_AD_CLICK:"jwplayerAdClicked",
        JWPLAYER_AD_COMPLETE:"jwplayerAdComplete",
        JWPLAYER_AD_IMPRESSION:"jwplayerAdImpression",
        JWPLAYER_AD_COMPANIONS:"jwplayerAdCompanions"
    }
}(jwplayer),function(f){
    var a=jwplayer.utils;
    f.eventdispatcher=function(f,
        e){
        var c,b;
        this.resetEventListeners=function(){
            c={};
            
            b=[]
            };
            
        this.resetEventListeners();
        this.addEventListener=function(b,e,h){
            try{
                a.exists(c[b])||(c[b]=[]),"string"==a.typeOf(e)&&(e=(new Function("return "+e))()),c[b].push({
                    listener:e,
                    count:h
                })
                }catch(f){
                a.log("error",f)
                }
                return!1
            };
            
        this.removeEventListener=function(b,e){
            if(c[b]){
                try{
                    for(var h=0;h<c[b].length;h++)if(c[b][h].listener.toString()==e.toString()){
                        c[b].splice(h,1);
                        break
                    }
                    }catch(f){
                a.log("error",f)
                }
                return!1
            }
        };
    
this.addGlobalListener=function(d,
    c){
    try{
        "string"==a.typeOf(d)&&(d=(new Function("return "+d))()),b.push({
            listener:d,
            count:c
        })
        }catch(e){
        a.log("error",e)
        }
        return!1
    };
    
this.removeGlobalListener=function(d){
    if(d){
        try{
            for(var c=0;c<b.length;c++)if(b[c].listener.toString()==d.toString()){
                b.splice(c,1);
                break
            }
            }catch(e){
        a.log("error",e)
        }
        return!1
    }
};

this.sendEvent=function(d,n){
    a.exists(n)||(n={});
    a.extend(n,{
        id:f,
        version:jwplayer.version,
        type:d
    });
    e&&a.log(d,n);
    if("undefined"!=a.typeOf(c[d]))for(var h=0;h<c[d].length;h++){
        try{
            c[d][h].listener(n)
            }catch(r){
            a.log("There was an error while handling a listener: "+
                r.toString(),c[d][h].listener)
            }
            c[d][h]&&(1===c[d][h].count?delete c[d][h]:0<c[d][h].count&&(c[d][h].count-=1))
        }
        for(h=0;h<b.length;h++){
        try{
            b[h].listener(n)
            }catch(p){
            a.log("There was an error while handling a listener: "+p.toString(),b[h].listener)
            }
            b[h]&&(1===b[h].count?delete b[h]:0<b[h].count&&(b[h].count-=1))
        }
    }
}
}(jwplayer.events),function(f){
    var a={},k={};
    
    f.plugins=function(){};
    
    f.plugins.loadPlugins=function(e,c){
        k[e]=new f.plugins.pluginloader(new f.plugins.model(a),c);
        return k[e]
        };
        
    f.plugins.registerPlugin=
    function(e,c,b,d){
        var n=f.utils.getPluginName(e);
        a[n]||(a[n]=new f.plugins.plugin(e));
        a[n].registerPlugin(e,c,b,d)
        }
    }(jwplayer),function(f){
    f.plugins.model=function(a){
        this.addPlugin=function(k){
            var e=f.utils.getPluginName(k);
            a[e]||(a[e]=new f.plugins.plugin(k));
            return a[e]
            };
            
        this.getPlugins=function(){
            return a
            }
        }
}(jwplayer),function(f){
    var a=jwplayer.utils,k=jwplayer.events;
    f.pluginmodes={
        FLASH:0,
        JAVASCRIPT:1,
        HYBRID:2
    };
    
    f.plugin=function(e){
        function c(){
            switch(a.getPluginPathType(e)){
                case a.pluginPathType.ABSOLUTE:
                    return e;
                case a.pluginPathType.RELATIVE:
                    return a.getAbsolutePath(e,window.location.href)
                    }
                }
        function b(){
        q=setTimeout(function(){
            n=a.loaderstatus.COMPLETE;
            j.sendEvent(k.COMPLETE)
            },1E3)
        }
        function d(){
        n=a.loaderstatus.ERROR;
        j.sendEvent(k.ERROR)
        }
        var n=a.loaderstatus.NEW,h,r,p,q,j=new k.eventdispatcher;
    a.extend(this,j);
    this.load=function(){
        if(n==a.loaderstatus.NEW)if(0<e.lastIndexOf(".swf"))h=e,n=a.loaderstatus.COMPLETE,j.sendEvent(k.COMPLETE);
            else if(a.getPluginPathType(e)==a.pluginPathType.CDN)n=a.loaderstatus.COMPLETE,
            j.sendEvent(k.COMPLETE);
        else{
            n=a.loaderstatus.LOADING;
            var g=new a.scriptloader(c());
            g.addEventListener(k.COMPLETE,b);
            g.addEventListener(k.ERROR,d);
            g.load()
            }
        };
        
this.registerPlugin=function(b,d,c,e){
    q&&(clearTimeout(q),q=void 0);
    p=d;
    c&&e?(h=e,r=c):"string"==typeof c?h=c:"function"==typeof c?r=c:!c&&!e&&(h=b);
    n=a.loaderstatus.COMPLETE;
    j.sendEvent(k.COMPLETE)
    };
    
this.getStatus=function(){
    return n
    };
    
this.getPluginName=function(){
    return a.getPluginName(e)
    };
    
this.getFlashPath=function(){
    if(h)switch(a.getPluginPathType(h)){
        case a.pluginPathType.ABSOLUTE:
            return h;
        case a.pluginPathType.RELATIVE:
            return 0<e.lastIndexOf(".swf")?a.getAbsolutePath(h,window.location.href):a.getAbsolutePath(h,c())
            }
            return null
    };
    
this.getJS=function(){
    return r
    };
    
this.getTarget=function(){
    return p
    };
    
this.getPluginmode=function(){
    if("undefined"!=typeof h&&"undefined"!=typeof r)return f.pluginmodes.HYBRID;
    if("undefined"!=typeof h)return f.pluginmodes.FLASH;
    if("undefined"!=typeof r)return f.pluginmodes.JAVASCRIPT
        };
        
this.getNewInstance=function(a,b,d){
    return new r(a,b,d)
    };
    
this.getURL=function(){
    return e
    }
}
}(jwplayer.plugins),
function(f){
    var a=f.utils,k=f.events,e=a.foreach;
    f.plugins.pluginloader=function(c,b){
        function d(){
            p?g.sendEvent(k.ERROR,{
                message:q
            }):r||(r=!0,h=a.loaderstatus.COMPLETE,g.sendEvent(k.COMPLETE))
            }
            function n(){
            j||d();
            if(!r&&!p){
                var b=0,e=c.getPlugins();
                a.foreach(j,function(c){
                    c=a.getPluginName(c);
                    var g=e[c];
                    c=g.getJS();
                    var h=g.getTarget(),g=g.getStatus();
                    if(g==a.loaderstatus.LOADING||g==a.loaderstatus.NEW)b++;
                    else if(c&&(!h||parseFloat(h)>parseFloat(f.version)))p=!0,q="Incompatible player version",d()
                        });
                0==b&&d()
                }
            }
        var h=a.loaderstatus.NEW,r=!1,p=!1,q,j=b,g=new k.eventdispatcher;
    a.extend(this,g);
    this.setupPlugins=function(b,d,g){
        var h={
            length:0,
            plugins:{}
    },m=0,f={},j=c.getPlugins();
    e(d.plugins,function(c,e){
        var A=a.getPluginName(c),n=j[A],k=n.getFlashPath(),p=n.getJS(),q=n.getURL();
        k&&(h.plugins[k]=a.extend({},e),h.plugins[k].pluginmode=n.getPluginmode(),h.length++);
        try{
            if(p&&d.plugins&&d.plugins[q]){
                var r=document.createElement("div");
                r.id=b.id+"_"+A;
                r.style.position="absolute";
                r.style.top=0;
                r.style.zIndex=
                m+10;
                f[A]=n.getNewInstance(b,a.extend({},d.plugins[q]),r);
                m++;
                b.onReady(g(f[A],r,!0));
                b.onResize(g(f[A],r))
                }
            }catch(E){
        a.log("ERROR: Failed to load "+A+".")
        }
    });
b.plugins=f;
return h
};

this.load=function(){
    if(!(a.exists(b)&&"object"!=a.typeOf(b))){
        h=a.loaderstatus.LOADING;
        e(b,function(b){
            a.exists(b)&&(b=c.addPlugin(b),b.addEventListener(k.COMPLETE,n),b.addEventListener(k.ERROR,m))
            });
        var d=c.getPlugins();
        e(d,function(a,b){
            b.load()
            })
        }
        n()
    };
    
var m=this.pluginFailed=function(){
    p||(p=!0,q="File not found",d())
    };
this.getStatus=function(){
    return h
    }
}
}(jwplayer),function(f){
    f.playlist=function(a){
        var k=[];
        if("array"==f.utils.typeOf(a))for(var e=0;e<a.length;e++)k.push(new f.playlist.item(a[e]));else k.push(new f.playlist.item(a));
        return k
        }
    }(jwplayer),function(f){
    var a=f.item=function(k){
        var e=jwplayer.utils,c=e.extend({},a.defaults,k);
        c.tracks=e.exists(k.tracks)?k.tracks:[];
        0==c.sources.length&&(c.sources=[new f.source(c)]);
        for(var b=0;b<c.sources.length;b++){
            var d=c.sources[b]["default"];
            c.sources[b]["default"]=
            d?"true"==d.toString():!1;
            c.sources[b]=new f.source(c.sources[b])
            }
            if(c.captions&&!e.exists(k.tracks)){
            for(k=0;k<c.captions.length;k++)c.tracks.push(c.captions[k]);
            delete c.captions
            }
            for(b=0;b<c.tracks.length;b++)c.tracks[b]=new f.track(c.tracks[b]);
        return c
        };
        
    a.defaults={
        description:"",
        image:"",
        mediaid:"",
        title:"",
        sources:[],
        tracks:[]
    }
}(jwplayer.playlist),function(f){
    var a=jwplayer.utils,k={
        file:void 0,
        label:void 0,
        type:void 0,
        "default":void 0
        };
        
    f.source=function(e){
        var c=a.extend({},k);
        a.foreach(k,
            function(b){
                a.exists(e[b])&&(c[b]=e[b],delete e[b])
                });
        c.type&&0<c.type.indexOf("/")&&(c.type=a.extensionmap.mimeType(c.type));
        "m3u8"==c.type&&(c.type="hls");
        "smil"==c.type&&(c.type="rtmp");
        return c
        }
    }(jwplayer.playlist),function(f){
    var a=jwplayer.utils,k={
        file:void 0,
        label:void 0,
        kind:"captions",
        "default":!1
        };
        
    f.track=function(e){
        var c=a.extend({},k);
        e||(e={});
        a.foreach(k,function(b){
            a.exists(e[b])&&(c[b]=e[b],delete e[b])
            });
        return c
        }
    }(jwplayer.playlist),function(f){
    var a=f.utils,k=f.events,e=!0,c=
    !1,b=document,d=f.embed=function(n){
        function h(b,d){
            a.foreach(d,function(a,d){
                "function"==typeof b[a]&&b[a].call(b,d)
                })
            }
            function r(a){
            j(l,B+a.message)
            }
            function p(){
            j(l,B+"No playable sources found")
            }
            function q(){
            j(l,"Adobe SiteCatalyst Error: Could not find Media Module")
            }
            function j(b,d){
            if(m.fallback){
                var h=b.style;
                h.backgroundColor="#000";
                h.color="#FFF";
                h.width=a.styleDimension(m.width);
                h.height=a.styleDimension(m.height);
                h.display="table";
                h.opacity=1;
                var h=document.createElement("p"),f=h.style;
                f.verticalAlign="middle";
                f.textAlign="center";
                f.display="table-cell";
                f.font="15px/20px Arial, Helvetica, sans-serif";
                h.innerHTML=d.replace(":",":\x3cbr\x3e");
                b.innerHTML="";
                b.appendChild(h);
                g(d,e)
                }else g(d,c)
                }
                function g(a,b){
            x&&(clearTimeout(x),x=null);
            n.dispatchEvent(k.JWPLAYER_SETUP_ERROR,{
                message:a,
                fallback:b
            })
            }
            var m=new d.config(n.config),l,t,u,w=m.width,z=m.height,B="Error loading player: ",y=f.plugins.loadPlugins(n.id,m.plugins),x=null;
        m.fallbackDiv&&(u=m.fallbackDiv,delete m.fallbackDiv);
        m.id=
        n.id;
        t=b.getElementById(n.id);
        m.aspectratio?n.config.aspectratio=m.aspectratio:delete n.config.aspectratio;
        l=b.createElement("div");
        l.id=t.id;
        l.style.width=0<w.toString().indexOf("%")?w:w+"px";
        l.style.height=0<z.toString().indexOf("%")?z:z+"px";
        t.parentNode.replaceChild(l,t);
        f.embed.errorScreen=j;
        y.addEventListener(k.COMPLETE,function(){
            if(m.sitecatalyst)try{
                null!=s&&s.hasOwnProperty("Media")||q()
                }catch(b){
                q();
                return
            }
            if("array"==a.typeOf(m.playlist)&&2>m.playlist.length&&(0==m.playlist.length||!m.playlist[0].sources||
                0==m.playlist[0].sources.length))p();
            else if(y.getStatus()==a.loaderstatus.COMPLETE){
                for(var f=0;f<m.modes.length;f++)if(m.modes[f].type&&d[m.modes[f].type]){
                    var j=a.extend({},m),D=new d[m.modes[f].type](l,m.modes[f],j,y,n);
                    if(D.supportsConfig())return D.addEventListener(k.ERROR,r),D.embed(),h(n,j.events),n
                        }
                        if(m.fallback){
                    var t="No suitable players found and fallback enabled";
                    x=setTimeout(function(){
                        g(t,e)
                        },10);
                    a.log(t);
                    new d.download(l,m,p)
                    }else t="No suitable players found and fallback disabled",
                    g(t,c),a.log(t),l.parentNode.replaceChild(u,l)
                    }
                });
    y.addEventListener(k.ERROR,function(a){
        j(l,"Could not load plugins: "+a.message)
        });
    y.load();
    return n
    }
}(jwplayer),function(f){
    function a(a){
        if(a.playlist)for(var d=0;d<a.playlist.length;d++)a.playlist[d]=new c(a.playlist[d]);
        else{
            var f={};
            
            e.foreach(c.defaults,function(d){
                k(a,f,d)
                });
            f.sources||(a.levels?(f.sources=a.levels,delete a.levels):(d={},k(a,d,"file"),k(a,d,"type"),f.sources=d.file?[d]:[]));
            a.playlist=[new c(f)]
            }
        }
    function k(a,d,c){
    e.exists(a[c])&&
    (d[c]=a[c],delete a[c])
    }
    var e=f.utils,c=f.playlist.item;
(f.embed.config=function(b){
    var d={
        fallback:!0,
        height:270,
        primary:"html5",
        width:480,
        base:b.base?b.base:e.getScriptPath("jwplayer.js"),
        aspectratio:""
    };
    
    b=e.extend(d,f.defaults,b);
    var d={
        type:"html5",
        src:b.base+"jwplayer.html5.js"
        },c={
        type:"flash",
        src:b.base+"jwplayer.flash.swf"
        };
        
    b.modes="flash"==b.primary?[c,d]:[d,c];
    b.listbar&&(b.playlistsize=b.listbar.size,b.playlistposition=b.listbar.position);
    b.flashplayer&&(c.src=b.flashplayer);
    b.html5player&&
    (d.src=b.html5player);
    a(b);
    c=b.aspectratio;
    if("string"!=typeof c||!e.exists(c))d=0;
    else{
        var h=c.indexOf(":");
        -1==h?d=0:(d=parseFloat(c.substr(0,h)),c=parseFloat(c.substr(h+1)),d=0>=d||0>=c?0:100*(c/d)+"%")
        }-1==b.width.toString().indexOf("%")?delete b.aspectratio:d?b.aspectratio=d:delete b.aspectratio;
    return b
    }).addConfig=function(b,c){
    a(c);
    return e.extend(b,c)
    }
}(jwplayer),function(f){
    var a=f.utils,k=document;
    f.embed.download=function(e,c,b){
        function d(b,c){
            for(var d=k.querySelectorAll(b),e=0;e<d.length;e++)a.foreach(c,
                function(a,b){
                    d[e].style[a]=b
                    })
            }
            function f(a,b,c){
            a=k.createElement(a);
            b&&(a.className="jwdownload"+b);
            c&&c.appendChild(a);
            return a
            }
            var h=a.extend({},c),r=h.width?h.width:480,p=h.height?h.height:320,q;
        c=c.logo?c.logo:{
            prefix:a.repo(),
            file:"logo.png",
            margin:10
        };
        
        var j,g,m,h=h.playlist,l,t=["mp4","aac","mp3"];
        if(h&&h.length){
            l=h[0];
            q=l.sources;
            for(h=0;h<q.length;h++){
                var u=q[h],w=u.type?u.type:a.extensionmap.extType(a.extension(u.file));
                u.file&&a.foreach(t,function(b){
                    w==t[b]?(j=u.file,g=l.image):a.isYouTube(u.file)&&
                    (m=u.file)
                    })
                }
                j?(q=j,b=g,e&&(h=f("a","display",e),f("div","icon",h),f("div","logo",h),q&&h.setAttribute("href",a.getAbsolutePath(q))),h="#"+e.id+" .jwdownload",e.style.width="",e.style.height="",d(h+"display",{
                width:a.styleDimension(Math.max(320,r)),
                height:a.styleDimension(Math.max(180,p)),
                background:"black center no-repeat "+(b?"url("+b+")":""),
                backgroundSize:"contain",
                position:"relative",
                border:"none",
                display:"block"
            }),d(h+"display div",{
                position:"absolute",
                width:"100%",
                height:"100%"
            }),d(h+"logo",

            {
                top:c.margin+"px",
                right:c.margin+"px",
                background:"top right no-repeat url("+c.prefix+c.file+")"
                }),d(h+"icon",{
                background:"center no-repeat url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAgNJREFUeNrs28lqwkAYB/CZqNVDDj2r6FN41QeIy8Fe+gj6BL275Q08u9FbT8ZdwVfotSBYEPUkxFOoks4EKiJdaDuTjMn3wWBO0V/+sySR8SNSqVRKIR8qaXHkzlqS9jCfzzWcTCYp9hF5o+59sVjsiRzcegSckFzcjT+ruN80TeSlAjCAAXzdJSGPFXRpAAMYwACGZQkSdhG4WCzehMNhqV6vG6vVSrirKVEw66YoSqDb7cqlUilE8JjHd/y1MQefVzqdDmiaJpfLZWHgXMHn8F6vJ1cqlVAkEsGuAn83J4gAd2RZymQygX6/L1erVQt+9ZPWb+CDwcCC2zXGJaewl/DhcHhK3DVj+KfKZrMWvFarcYNLomAv4aPRSFZVlTlcSPA5fDweW/BoNIqFnKV53JvncjkLns/n/cLdS+92O7RYLLgsKfv9/t8XlDn4eDyiw+HA9Jyz2eyt0+kY2+3WFC5hluej0Ha7zQQq9PPwdDq1Et1sNsx/nFBgCqWJ8oAK1aUptNVqcYWewE4nahfU0YQnk4ntUEfGMIU2m01HoLaCKbTRaDgKtaVLk9tBYaBcE/6Artdr4RZ5TB6/dC+9iIe/WgAMYADDpAUJAxjAAAYwgGFZgoS/AtNNTF7Z2bL0BYPBV3Jw5xFwwWcYxgtBP5OkE8i9G7aWGOOCruvauwADALMLMEbKf4SdAAAAAElFTkSuQmCC)"
            })):
            m?(c=m,e=f("embed","",e),e.src="http://www.youtube.com/v/"+/v=([^&]+)|\/([\w-]+)$|^([\w-]+)$/i.exec(c).slice(1).join(""),e.type="application/x-shockwave-flash",e.width=r,e.height=p):b()
            }
        }
}(jwplayer),function(f){
    var a=f.utils,k=f.events,e={};
    (f.embed.flash=function(c,b,d,n,h){
        function r(a,b,c){
            var d=document.createElement("param");
            d.setAttribute("name",b);
            d.setAttribute("value",c);
            a.appendChild(d)
            }
            function p(a,b,c){
            return function(){
                try{
                    c&&document.getElementById(h.id+"_wrapper").appendChild(b);
                    var d=
                    document.getElementById(h.id).getPluginConfig("display");
                    "function"==typeof a.resize&&a.resize(d.width,d.height);
                    b.style.left=d.x;
                    b.style.top=d.h
                    }catch(e){}
            }
        }
    function q(b){
        if(!b)return{};
            
        var c={},d=[];
        a.foreach(b,function(b,e){
            var g=a.getPluginName(b);
            d.push(b);
            a.foreach(e,function(a,b){
                c[g+"."+a]=b
                })
            });
        c.plugins=d.join(",");
        return c
        }
        var j=new f.events.eventdispatcher,g=a.flashVersion();
    a.extend(this,j);
    this.embed=function(){
        d.id=h.id;
        if(10>g)return j.sendEvent(k.ERROR,{
            message:"Flash version must be 10.0 or greater"
        }),
        !1;
        var f,l,t=h.config.listbar,u=a.extend({},d);
        if(c.id+"_wrapper"==c.parentNode.id)f=document.getElementById(c.id+"_wrapper");
        else{
            f=document.createElement("div");
            l=document.createElement("div");
            l.style.display="none";
            l.id=c.id+"_aspect";
            f.id=c.id+"_wrapper";
            f.style.position="relative";
            f.style.display="block";
            f.style.width=a.styleDimension(u.width);
            f.style.height=a.styleDimension(u.height);
            if(h.config.aspectratio){
                var w=parseFloat(h.config.aspectratio);
                l.style.display="block";
                l.style.marginTop=h.config.aspectratio;
                f.style.height="auto";
                f.style.display="inline-block";
                t&&("bottom"==t.position?l.style.paddingBottom=t.size+"px":"right"==t.position&&(l.style.marginBottom=-1*t.size*(w/100)+"px"))
                }
                c.parentNode.replaceChild(f,c);
            f.appendChild(c);
            f.appendChild(l)
            }
            f=n.setupPlugins(h,u,p);
        0<f.length?a.extend(u,q(f.plugins)):delete u.plugins;
        "undefined"!=typeof u["dock.position"]&&"false"==u["dock.position"].toString().toLowerCase()&&(u.dock=u["dock.position"],delete u["dock.position"]);
        f=u.wmode?u.wmode:u.height&&40>=
        u.height?"transparent":"opaque";
        l="height width modes events primary base fallback volume".split(" ");
        for(t=0;t<l.length;t++)delete u[l[t]];
        l=a.getCookies();
        a.foreach(l,function(a,b){
            "undefined"==typeof u[a]&&(u[a]=b)
            });
        l=window.location.href.split("/");
        l.splice(l.length-1,1);
        l=l.join("/");
        u.base=l+"/";
        e[c.id]=u;
        a.isIE()?(l='\x3cobject classid\x3d"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" " width\x3d"100%" height\x3d"100%"id\x3d"'+c.id+'" name\x3d"'+c.id+'" tabindex\x3d0""\x3e',l+='\x3cparam name\x3d"movie" value\x3d"'+
            b.src+'"\x3e',l+='\x3cparam name\x3d"allowfullscreen" value\x3d"true"\x3e\x3cparam name\x3d"allowscriptaccess" value\x3d"always"\x3e',l+='\x3cparam name\x3d"seamlesstabbing" value\x3d"true"\x3e',l+='\x3cparam name\x3d"wmode" value\x3d"'+f+'"\x3e',l+='\x3cparam name\x3d"bgcolor" value\x3d"#000000"\x3e',l+="\x3c/object\x3e",c.outerHTML=l,f=document.getElementById(c.id)):(l=document.createElement("object"),l.setAttribute("type","application/x-shockwave-flash"),l.setAttribute("data",b.src),l.setAttribute("width",
            "100%"),l.setAttribute("height","100%"),l.setAttribute("bgcolor","#000000"),l.setAttribute("id",c.id),l.setAttribute("name",c.id),l.setAttribute("tabindex",0),r(l,"allowfullscreen","true"),r(l,"allowscriptaccess","always"),r(l,"seamlesstabbing","true"),r(l,"wmode",f),c.parentNode.replaceChild(l,c),f=l);
        h.config.aspectratio&&(f.style.position="absolute");
        h.container=f;
        h.setPlayer(f,"flash")
        };
        
    this.supportsConfig=function(){
        if(g)if(d){
            if("string"==a.typeOf(d.playlist))return!0;
            try{
                var b=d.playlist[0].sources;
                if("undefined"==typeof b)return!0;
                for(var c=0;c<b.length;c++){
                    var e;
                    if(e=b[c].file){
                        var f=b[c].file,h=b[c].type;
                        if(a.isYouTube(f)||a.isRtmp(f,h)||"hls"==h)e=!0;
                        else{
                            var j=a.extensionmap[h?h:a.extension(f)];
                            e=!j?!1:!!j.flash
                            }
                        }
                    if(e)return!0
                    }
                }catch(k){}
    }else return!0;
return!1
}
}).getVars=function(a){
    return e[a]
    }
}(jwplayer),function(f){
    var a=f.utils,k=a.extensionmap,e=f.events;
    f.embed.html5=function(c,b,d,n,h){
        function r(a,b,d){
            return function(){
                try{
                    var e=document.querySelector("#"+c.id+" .jwmain");
                    d&&
                    e.appendChild(b);
                    "function"==typeof a.resize&&(a.resize(e.clientWidth,e.clientHeight),setTimeout(function(){
                        a.resize(e.clientWidth,e.clientHeight)
                        },400));
                    b.left=e.style.left;
                    b.top=e.style.top
                    }catch(f){}
            }
        }
    function p(a){
    q.sendEvent(a.type,{
        message:"HTML5 player not found"
    })
    }
    var q=this,j=new e.eventdispatcher;
a.extend(q,j);
q.embed=function(){
    if(f.html5){
        n.setupPlugins(h,d,r);
        c.innerHTML="";
        var g=f.utils.extend({},d);
        delete g.volume;
        g=new f.html5.player(g);
        h.container=document.getElementById(h.id);
        h.setPlayer(g,
            "html5")
        }else g=new a.scriptloader(b.src),g.addEventListener(e.ERROR,p),g.addEventListener(e.COMPLETE,q.embed),g.load()
        };
        
q.supportsConfig=function(){
    if(f.vid.canPlayType)try{
        if("string"==a.typeOf(d.playlist))return!0;
        for(var b=d.playlist[0].sources,c=0;c<b.length;c++){
            var e;
            var h=b[c].file,j=b[c].type;
            if(null!==navigator.userAgent.match(/BlackBerry/i)||a.isAndroid()&&("m3u"==a.extension(h)||"m3u8"==a.extension(h))||a.isRtmp(h,j))e=!1;
            else{
                var n=k[j?j:a.extension(h)],p;
                if(!n||n.flash&&!n.html5)p=!1;
                else{
                    var r=n.html5,q=f.vid;
                    if(r)try{
                        p=q.canPlayType(r)?!0:!1
                        }catch(x){
                        p=!1
                        }else p=!0
                        }
                        e=p
                }
                if(e)return!0
                }
            }catch(v){}
    return!1
}
}
}(jwplayer),function(f){
    var a=f.embed,k=f.utils,e=k.extend(function(c){
        function b(a){
            m.debug&&k.log(a)
            }
            function d(a){
            a=a.split("/");
            a=a[a.length-1];
            a=a.split("?");
            return a[0]
            }
            function e(){
            if(!C){
                var a=c.getPosition();
                b("stop: "+v+" : "+a);
                s.Media.stop(v,a)
                }
            }
        function h(a){
        C=!0;
        x=0;
        m.mediaName?a=m.mediaName:(a=c.getPlaylistItem(a.index),a=a.title?a.title:a.file?d(a.file):a.sources&&
            a.sources.length?d(a.sources[0].file):"");
        v=a;
        A=m.playerName?m.playerName:c.id
        }
        function r(a,b){
        var c=g.events[a];
        g.events[a]="function"!=typeof g.events[a]?b:function(a){
            c&&c(a);
            b(a)
            }
        }
    var p=k.repo(),q=k.extend({},f.defaults),j=k.extend({},q,c.config),g=c.config,m=g.sitecatalyst,l=j.plugins,t=j.analytics,u=p+"jwpsrv.js",w=p+"sharing.js",z=p+"related.js",B=p+"gapro.js",q=f.key?f.key:q.key,y=(new f.utils.key(q)).edition(),x=0,v="",A="",C=!0,l=l?l:{};
    
    "ads"==y&&j.advertising&&(j.advertising.client.match(".js$|.swf$")?
        l[j.advertising.client]=j.advertising:l[p+j.advertising.client+".js"]=j.advertising);
    delete g.advertising;
    g.key=q;
    j.analytics&&(j.analytics.client&&j.analytics.client.match(".js$|.swf$"))&&(u=j.analytics.client);
    delete g.analytics;
    if("free"==y||!t||!1!==t.enabled)l[u]=t?t:{};
        
    delete l.sharing;
    delete l.related;
    switch(y){
        case "premium":case "ads":
            j.sharing&&(j.sharing.client&&j.sharing.client.match(".js$|.swf$")&&(w=j.sharing.client),l[w]=j.sharing),j.related&&(j.related.client&&j.related.client.match(".js$|.swf$")&&
            (z=j.related.client),l[z]=j.related),j.ga&&(j.ga.client&&j.ga.client.match(".js$|.swf$")&&(B=j.ga.client),l[B]=j.ga),m&&(g.events=k.extend({},g.events),r("onPlay",function(){
            if(!C){
                var a=c.getPosition();
                b("play: "+v+" : "+a);
                s.Media.play(v,a)
                }
            }),r("onPause",function(){
            e()
            }),r("onBuffer",function(){
            e()
            }),r("onPlaylistItem",h),r("onTime",function(){
            a:{
                if(C){
                    var a=c.getDuration();
                    if(-1==a)break a;
                    C=!1;
                    b("open: "+v+" : "+a+" : "+A);
                    s.Media.open(v,a,A);
                    b("play: "+v+" : 0");
                    s.Media.play(v,0)
                    }
                    a=c.getPosition();
                if(3<=Math.abs(a-x)){
                    var d=x;
                    b("seek: "+d+" to "+a);
                    b("stop: "+v+" : "+d);
                    s.Media.stop(v,d);
                    b("play: "+v+" : "+a);
                    s.Media.play(v,a)
                    }
                    x=a
                }
            }),r("onComplete",function(){
            var a=c.getPosition();
            b("stop: "+v+" : "+a);
            s.Media.stop(v,a);
            b("close: "+v);
            s.Media.close(v);
            C=!0;
            x=0
            }));
case "pro":
    j.skin&&(g.skin=j.skin.replace(/^(beelden|bekle|five|glow|modieus|roundster|stormtrooper|vapor)$/i,k.repo()+"skins/$1.xml"))
    }
    g.plugins=l;
return new a(c)
},a);
f.embed=e
}(jwplayer),function(f){
    var a=[],k=f.utils,e=f.events,
    c=e.state,b=document,d=f.api=function(a){
        function h(a,b){
            return function(c){
                return b(a,c)
                }
            }
        function r(a,b){
        l[a]||(l[a]=[],q(e.JWPLAYER_PLAYER_STATE,function(b){
            var c=b.newstate;
            b=b.oldstate;
            if(c==a){
                var d=l[c];
                if(d)for(var e=0;e<d.length;e++)"function"==typeof d[e]&&d[e].call(this,{
                    oldstate:b,
                    newstate:c
                })
                }
                }));
    l[a].push(b);
    return g
    }
    function p(a,b){
    try{
        a.jwAddEventListener(b,'function(dat) { jwplayer("'+g.id+'").dispatchEvent("'+b+'", dat); }')
        }catch(c){
        k.log("Could not add internal listener")
        }
    }
function q(a,
    b){
    m[a]||(m[a]=[],t&&u&&p(t,a));
    m[a].push(b);
    return g
    }
    function j(){
    if(u){
        for(var a=arguments[0],b=[],c=1;c<arguments.length;c++)b.push(arguments[c]);
        if("undefined"!=typeof t&&"function"==typeof t[a])switch(b.length){
            case 4:
                return t[a](b[0],b[1],b[2],b[3]);
            case 3:
                return t[a](b[0],b[1],b[2]);
            case 2:
                return t[a](b[0],b[1]);
            case 1:
                return t[a](b[0]);
            default:
                return t[a]()
                }
                return null
        }
        w.push(arguments)
    }
    var g=this,m={},l={},t=void 0,u=!1,w=[],z=void 0,B={},y={};

g.container=a;
g.id=a.id;
g.getBuffer=function(){
    return j("jwGetBuffer")
    };
g.getContainer=function(){
    return g.container
    };
    
g.addButton=function(a,b,c,d){
    try{
        y[d]=c,j("jwDockAddButton",a,b,"jwplayer('"+g.id+"').callback('"+d+"')",d)
        }catch(e){
        k.log("Could not add dock button"+e.message)
        }
    };

g.removeButton=function(a){
    j("jwDockRemoveButton",a)
    };
    
g.callback=function(a){
    if(y[a])y[a]()
        };
        
g.forceState=function(a){
    j("jwForceState",a);
    return g
    };
    
g.releaseState=function(){
    return j("jwReleaseState")
    };
    
g.getDuration=function(){
    return j("jwGetDuration")
    };
    
g.getFullscreen=function(){
    return j("jwGetFullscreen")
    };
g.getStretching=function(){
    return j("jwGetStretching")
    };
    
g.getHeight=function(){
    return j("jwGetHeight")
    };
    
g.getLockState=function(){
    return j("jwGetLockState")
    };
    
g.getMeta=function(){
    return g.getItemMeta()
    };
    
g.getMute=function(){
    return j("jwGetMute")
    };
    
g.getPlaylist=function(){
    var a=j("jwGetPlaylist");
    "flash"==g.renderingMode&&k.deepReplaceKeyName(a,["__dot__","__spc__","__dsh__","__default__"],["."," ","-","default"]);
    return a
    };
    
g.getPlaylistItem=function(a){
    k.exists(a)||(a=g.getPlaylistIndex());
    return g.getPlaylist()[a]
    };
g.getPlaylistIndex=function(){
    return j("jwGetPlaylistIndex")
    };
    
g.getPosition=function(){
    return j("jwGetPosition")
    };
    
g.getRenderingMode=function(){
    return g.renderingMode
    };
    
g.getState=function(){
    return j("jwGetState")
    };
    
g.getVolume=function(){
    return j("jwGetVolume")
    };
    
g.getWidth=function(){
    return j("jwGetWidth")
    };
    
g.setFullscreen=function(a){
    k.exists(a)?j("jwSetFullscreen",a):j("jwSetFullscreen",!j("jwGetFullscreen"));
    return g
    };
    
g.setStretching=function(a){
    j("jwSetStretching",a);
    return g
    };
    
g.setMute=function(a){
    k.exists(a)?
    j("jwSetMute",a):j("jwSetMute",!j("jwGetMute"));
    return g
    };
    
g.lock=function(){
    return g
    };
    
g.unlock=function(){
    return g
    };
    
g.load=function(a){
    j("jwLoad",a);
    return g
    };
    
g.playlistItem=function(a){
    j("jwPlaylistItem",parseInt(a));
    return g
    };
    
g.playlistPrev=function(){
    j("jwPlaylistPrev");
    return g
    };
    
g.playlistNext=function(){
    j("jwPlaylistNext");
    return g
    };
    
g.resize=function(a,c){
    if("flash"!=g.renderingMode){
        var d=document.getElementById(g.id);
        d.className=d.className.replace(/\s+aspectMode/,"");
        d.style.display="block";
        j("jwResize",a,c)
        }else{
        var d=b.getElementById(g.id+"_wrapper"),e=b.getElementById(g.id+"_aspect");
        e&&(e.style.display="none");
        d&&(d.style.display="block",d.style.width=k.styleDimension(a),d.style.height=k.styleDimension(c))
        }
        return g
    };
    
g.play=function(a){
    "undefined"==typeof a?(a=g.getState(),a==c.PLAYING||a==c.BUFFERING?j("jwPause"):j("jwPlay")):j("jwPlay",a);
    return g
    };
    
g.pause=function(a){
    "undefined"==typeof a?(a=g.getState(),a==c.PLAYING||a==c.BUFFERING?j("jwPause"):j("jwPlay")):j("jwPause",a);
    return g
    };
g.stop=function(){
    j("jwStop");
    return g
    };
    
g.seek=function(a){
    j("jwSeek",a);
    return g
    };
    
g.setVolume=function(a){
    j("jwSetVolume",a);
    return g
    };
    
g.loadInstream=function(a,b){
    return z=new d.instream(this,t,a,b)
    };
    
g.getQualityLevels=function(){
    return j("jwGetQualityLevels")
    };
    
g.getCurrentQuality=function(){
    return j("jwGetCurrentQuality")
    };
    
g.setCurrentQuality=function(a){
    j("jwSetCurrentQuality",a)
    };
    
g.getCaptionsList=function(){
    return j("jwGetCaptionsList")
    };
    
g.getCurrentCaptions=function(){
    return j("jwGetCurrentCaptions")
    };
g.setCurrentCaptions=function(a){
    j("jwSetCurrentCaptions",a)
    };
    
g.getControls=function(){
    return j("jwGetControls")
    };
    
g.getSafeRegion=function(){
    return j("jwGetSafeRegion")
    };
    
g.setControls=function(a){
    j("jwSetControls",a)
    };
    
g.destroyPlayer=function(){
    j("jwPlayerDestroy")
    };
    
g.playAd=function(a){
    j("jwPlayAd",a)
    };
    
var x={
    onBufferChange:e.JWPLAYER_MEDIA_BUFFER,
    onBufferFull:e.JWPLAYER_MEDIA_BUFFER_FULL,
    onError:e.JWPLAYER_ERROR,
    onSetupError:e.JWPLAYER_SETUP_ERROR,
    onFullscreen:e.JWPLAYER_FULLSCREEN,
    onMeta:e.JWPLAYER_MEDIA_META,
    onMute:e.JWPLAYER_MEDIA_MUTE,
    onPlaylist:e.JWPLAYER_PLAYLIST_LOADED,
    onPlaylistItem:e.JWPLAYER_PLAYLIST_ITEM,
    onPlaylistComplete:e.JWPLAYER_PLAYLIST_COMPLETE,
    onReady:e.API_READY,
    onResize:e.JWPLAYER_RESIZE,
    onComplete:e.JWPLAYER_MEDIA_COMPLETE,
    onSeek:e.JWPLAYER_MEDIA_SEEK,
    onTime:e.JWPLAYER_MEDIA_TIME,
    onVolume:e.JWPLAYER_MEDIA_VOLUME,
    onBeforePlay:e.JWPLAYER_MEDIA_BEFOREPLAY,
    onBeforeComplete:e.JWPLAYER_MEDIA_BEFORECOMPLETE,
    onDisplayClick:e.JWPLAYER_DISPLAY_CLICK,
    onControls:e.JWPLAYER_CONTROLS,
    onQualityLevels:e.JWPLAYER_MEDIA_LEVELS,
    onQualityChange:e.JWPLAYER_MEDIA_LEVEL_CHANGED,
    onCaptionsList:e.JWPLAYER_CAPTIONS_LIST,
    onCaptionsChange:e.JWPLAYER_CAPTIONS_CHANGED,
    onAdError:e.JWPLAYER_AD_ERROR,
    onAdClick:e.JWPLAYER_AD_CLICK,
    onAdImpression:e.JWPLAYER_AD_IMPRESSION,
    onAdTime:e.JWPLAYER_AD_TIME,
    onAdComplete:e.JWPLAYER_AD_COMPLETE,
    onAdCompanions:e.JWPLAYER_AD_COMPANIONS
    };
    
k.foreach(x,function(a){
    g[a]=h(x[a],q)
    });
var v={
    onBuffer:c.BUFFERING,
    onPause:c.PAUSED,
    onPlay:c.PLAYING,
    onIdle:c.IDLE
    };
    
k.foreach(v,function(a){
    g[a]=h(v[a],r)
    });
g.remove=
function(){
    if(!u)throw"Cannot call remove() before player is ready";
    w=[];
    d.destroyPlayer(this.id)
    };
    
g.setup=function(a){
    $.each(a, function(key, element) {
});
    if(f.embed){
        var c=b.getElementById(g.id);
        c&&(a.fallbackDiv=c);
        c=g;
        w=[];
        d.destroyPlayer(c.id);
        c=f(g.id);
        c.config=a;
        
        return new f.embed(c)
        }
        return g
    };
    
g.registerPlugin=function(a,b,c,d){
    f.plugins.registerPlugin(a,b,c,d)
    };
    
g.setPlayer=function(a,b){
    t=a;
    g.renderingMode=b
    };
    
g.detachMedia=function(){
    if("html5"==g.renderingMode)return j("jwDetachMedia")
        };
        
g.attachMedia=function(a){
    if("html5"==
        g.renderingMode)return j("jwAttachMedia",a)
        };
        
g.dispatchEvent=function(a,b){
    if(m[a])for(var c=k.translateEventResponse(a,b),d=0;d<m[a].length;d++)if("function"==typeof m[a][d])try{
        a==e.JWPLAYER_PLAYLIST_LOADED&&k.deepReplaceKeyName(c.playlist,["__dot__","__spc__","__dsh__","__default__"],["."," ","-","default"]),m[a][d].call(this,c)
        }catch(f){
        k.log("There was an error calling back an event handler")
        }
    };
    
g.dispatchInstreamEvent=function(a){
    z&&z.dispatchEvent(a,arguments)
    };
    
g.callInternal=j;
g.playerReady=
function(a){
    u=!0;
    t||g.setPlayer(b.getElementById(a.id));
    g.container=b.getElementById(g.id);
    k.foreach(m,function(a){
        p(t,a)
        });
    q(e.JWPLAYER_PLAYLIST_ITEM,function(){
        B={}
    });
q(e.JWPLAYER_MEDIA_META,function(a){
    k.extend(B,a.metadata)
    });
for(g.dispatchEvent(e.API_READY);0<w.length;)j.apply(this,w.shift())
    };
    
g.getItemMeta=function(){
    return B
    };
    
g.isBeforePlay=function(){
    return t.jwIsBeforePlay()
    };
    
g.isBeforeComplete=function(){
    return t.jwIsBeforeComplete()
    };
    
return g
};

d.selectPlayer=function(c){
    var e;
    k.exists(c)||
    (c=0);
    c.nodeType?e=c:"string"==typeof c&&(e=b.getElementById(c));
    return e?(c=d.playerById(e.id))?c:d.addPlayer(new d(e)):"number"==typeof c?a[c]:null
    };
    
d.playerById=function(b){
    for(var c=0;c<a.length;c++)if(a[c].id==b)return a[c];return null
    };
    
d.addPlayer=function(b){
    for(var c=0;c<a.length;c++)if(a[c]==b)return b;a.push(b);
    return b
    };
    
d.destroyPlayer=function(c){
    for(var d=-1,e,f=0;f<a.length;f++)a[f].id==c&&(d=f,e=a[f]);
    0<=d&&(c=e.id,f=b.getElementById(c+("flash"==e.renderingMode?"_wrapper":"")),k.clearCss&&
        k.clearCss("#"+c),f&&("html5"==e.renderingMode&&e.destroyPlayer(),e=b.createElement("div"),e.id=c,f.parentNode.replaceChild(e,f)),a.splice(d,1));
    return null
    };
    
f.playerReady=function(a){
    var b=f.api.playerById(a.id);
    b?b.playerReady(a):f.api.selectPlayer(a.id).playerReady(a)
    }
}(jwplayer),function(f){
    var a=f.events,k=f.utils,e=a.state;
    f.api.instream=function(c,b,d,f){
        function h(a,b){
            j[a]||(j[a]=[],q.jwInstreamAddEventListener(a,'function(dat) { jwplayer("'+p.id+'").dispatchInstreamEvent("'+a+'", dat); }'));
            j[a].push(b);
            return this
            }
            function r(b,c){
            g[b]||(g[b]=[],h(a.JWPLAYER_PLAYER_STATE,function(a){
                var c=a.newstate,d=a.oldstate;
                if(c==b){
                    var e=g[c];
                    if(e)for(var f=0;f<e.length;f++)"function"==typeof e[f]&&e[f].call(this,{
                        oldstate:d,
                        newstate:c,
                        type:a.type
                        })
                    }
                    }));
        g[b].push(c);
        return this
        }
        var p=c,q=b,j={},g={};
    
    this.dispatchEvent=function(a,b){
        if(j[a])for(var c=k.translateEventResponse(a,b[1]),d=0;d<j[a].length;d++)"function"==typeof j[a][d]&&j[a][d].call(this,c)
            };
            
    this.onError=function(b){
        return h(a.JWPLAYER_ERROR,
            b)
        };
        
    this.onFullscreen=function(b){
        return h(a.JWPLAYER_FULLSCREEN,b)
        };
        
    this.onMeta=function(b){
        return h(a.JWPLAYER_MEDIA_META,b)
        };
        
    this.onMute=function(b){
        return h(a.JWPLAYER_MEDIA_MUTE,b)
        };
        
    this.onComplete=function(b){
        return h(a.JWPLAYER_MEDIA_COMPLETE,b)
        };
        
    this.onTime=function(b){
        return h(a.JWPLAYER_MEDIA_TIME,b)
        };
        
    this.onBuffer=function(a){
        return r(e.BUFFERING,a)
        };
        
    this.onPause=function(a){
        return r(e.PAUSED,a)
        };
        
    this.onPlay=function(a){
        return r(e.PLAYING,a)
        };
        
    this.onIdle=function(a){
        return r(e.IDLE,a)
        };
    this.onClick=function(b){
        return h(a.JWPLAYER_INSTREAM_CLICK,b)
        };
        
    this.onInstreamDestroyed=function(b){
        return h(a.JWPLAYER_INSTREAM_DESTROYED,b)
        };
        
    this.play=function(a){
        q.jwInstreamPlay(a)
        };
        
    this.pause=function(a){
        q.jwInstreamPause(a)
        };
        
    this.destroy=function(){
        q.jwInstreamDestroy()
        };
        
    p.callInternal("jwLoadInstream",d,f?f:{})
    }
}(jwplayer),function(f){
    var a=f.api,k=a.selectPlayer;
    a.selectPlayer=function(a){
        return(a=k(a))?a:{
            registerPlugin:function(a,b,d){
                f.plugins.registerPlugin(a,b,d)
                }
            }
    }
}(jwplayer));