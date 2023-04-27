/**
 * 浏览器缓存加载 js、css、img、mp3、ttf等文件
 * demo: 见readme.md
 */
"use strict";
var browserCacheFile = {
    cache: {},
    loadScript: function (url, callback) {
        var script = document.createElement("script");
        script.src = url;
        script.onload = callback;
        document.body.appendChild(script);
    },
    loadStyle: function (url, callback) {
        var link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = url;
        link.onload = callback;
        document.head.appendChild(link);
    },
    loadImage: function (url, callback) {
        var img = new Image();
        img.src = url;
        img.onload = callback;
    },
    loadFont: function (url, fontFamily = "custom-family", callback) {
        var style = document.createElement("style");
        style.type = "text/css";
        style.textContent = "@font-face { font-family: " + fontFamily + "; src: url(" + url + "); }";
        style.onload = callback;
        document.head.appendChild(style);
    },
    loadAudio: function (url, callback) {
        var audio = new Audio();
        audio.controls = true;  //这样控件才能显示出来
        audio.src = url;  //音乐的路径
        audio.onload = callback;
    },
    loadResource: function (url, callback, sourceItme = "") {
        if (browserCacheFile.cache[url]) {
            // 如果缓存存在，则直接使用缓存
            callback();
        } else {
            // 如果缓存不存在，则进行加载和缓存
            var ext = url.split(".").pop();
            switch (ext) {
                case "js":
                    browserCacheFile.loadScript(url, function () {
                        browserCacheFile.cache[url] = true;
                        callback();
                    });
                    break;
                case "css":
                    browserCacheFile.loadStyle(url, function () {
                        browserCacheFile.cache[url] = true;
                        callback();
                    });
                    break;
                case "ttf":
                    browserCacheFile.loadFont(url, sourceItme.fontFamily, function () {
                        browserCacheFile.cache[url] = true;
                        callback();
                    });
                    break;
                case "mp3":
                    browserCacheFile.loadAudio.loadStyle(url, function () {
                        browserCacheFile.cache[url] = true;
                        callback();
                    });
                    break;
                case "png":
                case "jpg":
                case "jpeg":
                case "gif":
                    browserCacheFile.loadImage(url, function () {
                        browserCacheFile.cache[url] = true;
                        callback();
                    });
                    break;
                default:
                    console.error("不支持的文件格式(." + ext + "):[" + url + "]");
                    callback();
            }
        }
    },
    loadResources: function (resources, callback) {
        var count = resources.length;

        function done() {
            count --;
            if (count === 0) {
                callback();
            }
        }

        for (var i = 0; i < resources.length; i ++) {
            var resource = resources[i];
            browserCacheFile.loadResource(resource.url, done, resource);
        }
    },
    loadModule: function (url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var res = xhr.responseText;
                eval(res);
                callback();
            }
        };
        xhr.send();
    },
    loadModules: function (modules, callback) {
        var count = modules.length;

        function done() {
            count --;
            if (count === 0) {
                callback();
            }
        }

        for (var i = 0; i < modules.length; i ++) {
            var module = modules[i];
            // 不使用 loadModule 里面的 eval 函数
            // browserCacheFile.loadModule(module.url, done);
            browserCacheFile.loadResource(module.url, done, modules);
        }
    },
    // 获取额外参数
    getArgs: function (attr_name) {
        var script = document.getElementsByTagName("script");
        var attr_val;
        //利用for循环遍历上面获取的li
        for (var i = 0; i <= script.length; i ++) {
            try {
                attr_val = script[i].getAttribute(attr_name);
                if (attr_val) {
                    return attr_val;
                }
            } catch (err) {
                //在此处理错误
            }
        }
        return attr_val;
    },
    load: function (resources, modules, callback) {
        browserCacheFile.loadResources(resources, function () {
            browserCacheFile.loadModules(modules, callback);
        });
    }
};

function define(name, deps, impl) {
    for (var i = 0; i < deps.length; i ++) {
        deps[i] = browserCacheFile.cache[deps[i]];
    }
    browserCacheFile.cache[name] = impl.apply(null, deps);
}

function require(deps, callback) {
    var args = [];
    for (var i = 0; i < deps.length; i ++) {
        args.push(browserCacheFile.cache[deps[i]]);
    }
    callback.apply(null, args);
}

var auto_cache_file = browserCacheFile.getArgs("auto_cache_file");
if (auto_cache_file && typeof (auto_cache_file) !== "undefined") {
    if (auto_cache_file == "true") {
        var all_js = [];
        Array.from(window.performance.getEntriesByType("resource")).map(
            function (x) {//遍历
                if (x.initiatorType == "script") {
                    all_js.push(x.name);
                }
                //TODO css img
                // if(x.initiatorType=="link"){
                //     console.log(x)
                // }
            }
        );
    }
}
