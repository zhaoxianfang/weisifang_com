/**
 * 浏览器缓存加载 js、css、img、mp3、ttf等文件
 * demo: 见readme.md
 */
"use strict";
var browserCacheFile = {
    cache: {},
    loadScript: function (url, callback) {
        // 方法一 可能存在跨域
        var script = document.createElement("script");
        script.type = "text/javascript";
        if (script.readyState) {  // IE
            script.onreadystatechange = function() {
                if (script.readyState === "loaded" || script.readyState === "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {  // Others
            script.onload = function() {
                callback();
            };
        }
        script.src = url;
        document.body.appendChild(script);
    },
    loadStyle: function (url, callback) {
        // 方法一 可能存在跨域
        var link = document.createElement("link");
        link.rel = "stylesheet";
        link.type = 'text/css';
        if (link.readyState) {  // IE
            link.onreadystatechange = function() {
                if (link.readyState === "loaded" || link.readyState === "complete") {
                    link.onreadystatechange = null;
                    callback();
                }
            };
        } else {  // Others
            link.onload = function() {
                callback();
            };
        }
        link.href = url;
        document.head.appendChild(link);
    },
    loadImage: function (url, callback) {
        var img = new Image();
        img.src = url;
        img.onload = callback;
    },
    loadFont: function (url, fontFamily , callback) {
        var style = document.createElement("style");
        style.type = "text/css";
        style.textContent = "@font-face { font-family: " + (fontFamily || 'custom-family') + "; src: url(" + url + "); }";
        style.onload = callback;
        document.head.appendChild(style);
    },
    loadAudio: function (url, callback) {
        var audio = new Audio();
        audio.controls = true;  //这样控件才能显示出来
        audio.src = url;  //音乐的路径
        audio.onload = callback;
    },
    loadResource: function (url, callback, sourceItme) {
        if (browserCacheFile.cache[url]) {
            // 如果缓存存在，则直接使用缓存
            callback();
        } else {
            var ext;
            // 如果缓存不存在，则进行加载和缓存
            if (url && url.length > 0) {
                // 截取 再拼接新的字符串
                url = url.split("?")[0];
                ext = url.split(".").pop();
            } else {
                return false;
            }

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
                    browserCacheFile.loadFont(url, (sourceItme.fontFamily || ""), function () {
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
    // 推荐使用 loadResource
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
    getTtfFiles: function () {
        // 首先获取页面中的所有样式表，
        // 然后遍历每个样式表中的所有规则，找到type为CSSRule.FONT_FACE_RULE的规则，即为@font-face规则。
        // 接着获取规则中的字体文件URL，即为字体文件的URL。
        // 最终将所有字体文件的URL存储在fontUrls数组中。
        var fontFaceRules = [];
        // 获取所有的样式表
        var styleSheets = document.styleSheets;
        for (var i = 0; i < styleSheets.length; i ++) {
            // 获取每个样式表中的所有规则
            try {
                var rules = styleSheets[i].cssRules || styleSheets[i].rules;
                for (var j = 0; j < rules.length; j ++) {
                    // 判断规则是否为@font-face规则
                    if (rules[j].type === CSSRule.FONT_FACE_RULE) {
                        fontFaceRules.push(rules[j]);
                    }
                }
            } catch (err) {
                //在此处理错误
            }
        }

        var fontUrls = [];
        // 遍历所有的@font-face规则
        for (var k = 0; k < fontFaceRules.length; k ++) {
            // 获取规则中的字体文件URL
            var fontSrc = fontFaceRules[k].style.getPropertyValue("src");
            var fontUrl = fontSrc.match(/url\((.*?)\)/)[1];
            // 除去首尾的引号
            fontUrl = fontUrl.replace(/^['|"](.*)['|"]$/, "$1");
            fontUrls.push(fontUrl);
        }
        return fontUrls;
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
    // 检查是否需要自动缓存网页资源文件
    checkAutoCacheFile: function () {
        var auto_cache_file = browserCacheFile.getArgs("auto_cache_file");
        if (auto_cache_file && auto_cache_file === "true") {
            var all_files = [];
            Array.from(window.performance.getEntriesByType("resource")).map(
                function (x) {//遍历
                    if (["script", "img", "link"].includes(x.initiatorType)) {
                        all_files.push(x.name);
                    }
                }
            );
            // 获取ttf
            all_files = all_files.concat(browserCacheFile.getTtfFiles());
            // 自动缓存文件
            browserCacheFile.load(all_files, [], function () {});
        }
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
// 检查是否自动缓存加载的文件
browserCacheFile.checkAutoCacheFile();
