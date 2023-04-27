/**
 * 浏览器缓存加载 js、css、img等文件
 * demo1:
 *  browserCache.load([ { url: 没有使用 define 定义的文件，例如 js,css,img等 }], [ { url: 没有使用 define 定义的module js文件 } ], function() {
 *   require(['module1', 'module2'], function(module1, module2) {
 *     // 使用加载完成的模块
 *   });
 *  });
 */
"use strict";
var browserCache = {
    cache: {},
    loadScript: function (url, callback) {
        var script = document.createElement("script");
        script.src = url;
        script.onload = callback;
        document.head.appendChild(script);
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
    loadResource: function (url, callback) {
        if (browserCache.cache[url]) {
            // 如果缓存存在，则直接使用缓存
            callback();
        } else {
            // 如果缓存不存在，则进行加载和缓存
            var ext = url.split(".").pop();
            switch (ext) {
                case "js":
                    browserCache.loadScript(url, function () {
                        browserCache.cache[url] = true;
                        callback();
                    });
                    break;
                case "css":
                    browserCache.loadStyle(url, function () {
                        browserCache.cache[url] = true;
                        callback();
                    });
                    break;
                case "png":
                case "jpg":
                case "jpeg":
                case "gif":
                    browserCache.loadImage(url, function () {
                        browserCache.cache[url] = true;
                        callback();
                    });
                    break;
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
            browserCache.loadResource(resource.url, done);
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
            browserCache.loadModule(module.url, done);
        }
    },
    load: function (resources, modules, callback) {
        browserCache.loadResources(resources, function () {
            browserCache.loadModules(modules, callback);
        });
    }
};

function define(name, deps, impl) {
    for (var i = 0; i < deps.length; i ++) {
        deps[i] = browserCache.cache[deps[i]];
    }
    browserCache.cache[name] = impl.apply(null, deps);
}

function require(deps, callback) {
    var args = [];
    for (var i = 0; i < deps.length; i ++) {
        args.push(browserCache.cache[deps[i]]);
    }
    callback.apply(null, args);
}


// 使用示例：

// browserCache.load([
//   { url: 没有使用 define 定义的文件，例如 js,css,img等 },
// ], [
//   { url: 没有使用 define 定义的module js文件 },
// ], function() {
//   require(['module1', 'module2'], function(module1, module2) {
//     // 使用加载完成的模块
//   });
// });

// 完整demo


// test1.js
// define('a', [], function() {
//     var a= {
//         test:'test a'
//     };
//     return a;
// });
// test2.js
// define('b', [], function() {
//     var b= {
//         test:'test b'
//     };
//     return b;
// });
// <script src="./load_plus.js" type="text/javascript" charset="utf-8" ></script>
// <script type="text/javascript" charset="utf-8" >
// var img = 'http://www.chaxuanxuan.com/images/new/banner4.jpg';
//   browserCache.load([
//     { url: 'https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js'},
//     { url: './test3.js' },
//     // { url: 'http://example.com/test.css'},
//     { url: img}
//   ], [
//     { url: './test1.js' },
//     { url: './test2.js' }
//   ], function() {
//     require(['a', 'b'], function(module1, module2) {
//       // 使用加载完成的模块
//       $('#img_test').attr("src", img);
//       console.log(module1,module2,$('#test').html())
//       console.log(c,'==')
//       $('#test').click(function(){
//         // alert("段落被点击了。");
//         window.location.href = "./index.test.html";
//     });
//     });
//   });
// </script>
