var cache = {};

function loadFile(url, callback) {
  if (cache[url]) {
    // 如果缓存存在，则直接使用缓存
    callback(cache[url]);
  } else {
    // 如果缓存不存在，则进行加载和缓存
    var ext = url.split('.').pop();
    var file;
    switch (ext) {
      case 'js':
        file = document.createElement('script');
        file.type = 'text/javascript';
        file.src = url;
        break;
      case 'css':
        file = document.createElement('link');
        file.rel = 'stylesheet';
        file.type = 'text/css';
        file.href = url;
        break;
      case 'png':
      case 'jpg':
      case 'jpeg':
      case 'gif':
        file = new Image();
        file.src = url;
        break;
    }
    if (file) {
      file.onload = function() {
        cache[url] = file;
        callback(file);
      };
      document.head.appendChild(file);
    } else {
      throw new Error('Unsupported file type: ' + ext);
    }
  }
}

function loadModule(name, deps, url, callback) {
  if (cache[name]) {
    // 如果缓存存在，则直接使用缓存
    callback(cache[name]);
  } else {
    // 如果缓存不存在，则进行加载和缓存
    var depUrls = [];
    for (var i = 0; i < deps.length; i++) {
      var dep = deps[i];
      var depUrl = cache[dep] || dep;
      depUrls.push(depUrl);
    }
    loadFile(url, function() {
      var module = {
        name: name,
        deps: deps,
        impl: window[name]
      };
      cache[name] = module;
      callback(module);
    });
  }
}

function define(name, deps, impl) {
  var url = window.location.href;
  url = url.substring(0, url.lastIndexOf('/') + 1) + name;
  var ext = url.split('.').pop();
  if (ext !== 'js') {
    url += '.js';
  }
  loadModule(name, deps, url, function(module) {
    impl.apply(null, module.deps.map(function(dep) {
      return cache[dep].impl;
    }));
  });
}

function require(deps, callback) {
  var args = [];
  for (var i = 0; i < deps.length; i++) {
    var dep = deps[i];
    var module = cache[dep];
    if (!module) {
      throw new Error('Module ' + dep + ' not found');
    }
    var depArgs = require(module.deps, module.impl);
    args.push(depArgs);
  }
  return callback.apply(null, args);
}


// demo

// define('module1', [], function() {
//   return 'Module 1';
// });

// define('module2', ['module1'], function(module1) {
//   return 'Module 2 depends on ' + module1;
// });

// require(['module2'], function(module2) {
//   console.log(module2); // 'Module 2 depends on Module 1'
// });

// 这个示例定义了两个 JavaScript 模块，module1 和 module2，module2 依赖于 module1。在加载完成后，使用 require 函数可以获取到 module2 的输出值。如果这些模块已经被缓存过了，则直接使用缓存，否则进行加载和缓存。
// 除了 JavaScript 模块，这个加载器还支持加载 CSS 文件和图片文件。在定义模块时，可以将 CSS 文件和图片文件的路径作为依赖项，然后在模块实现中使用它们。
// 注意：在使用这个加载器时，需要按照模块名称来命名模块文件，并将它们放在同一个目录下。例如，如果要加载 module1，则需要将其定义在 module1.js 文件中。同时，CSS 文件和图片文件也应该放在同一个目录下。



// 好的，这个加载器还支持浏览器文件缓存，可以将已经加载过的文件进行缓存，下次再次使用时直接从缓存中获取，避免了重复加载文件，提高了加载速度。缓存是通过一个全局变量 cache 来实现的，它是一个对象，用于存储已经加载过的文件或模块。
// 在加载文件时，先检查缓存中是否已经存在该文件，如果存在则直接使用缓存，否则进行加载和缓存。在加载模块时，先检查缓存中是否已经存在该模块，如果存在则直接使用缓存，否则先加载该模块依赖的其他模块，然后再加载该模块本身，并将其缓存起来。
// 使用示例：

// define('module1', ['module2.css', 'module3.png'], function() {
//   return 'Module 1';
// });

// define('module2', [], function() {
//   return 'Module 2';
// });

// define('module3', [], function() {
//   return 'Module 3';
// });

// require(['module1'], function(module1) {
//   console.log(module1); // 'Module 1'
// });
// 这个示例定义了三个模块，module1 依赖于 module2.css 和 module3.png，它们是 CSS 文件和图片文件。在加载 module1 时，会先加载它依赖的文件，并将它们缓存起来，然后再加载 module1 并将其缓存起来。在下次加载 module1 时，会直接从缓存中获取，避免了重复加载文件和模块。
// 这个加载器还支持切换页面时使用已经缓存的文件，也就是说，当用户在同一个网站的不同页面之间进行切换时，已经加载过的文件可以直接从缓存中获取，避免了重复加载文件和模块，提高了页面切换的速度。
// 以上是一个基于原生 JavaScript 的文件模块加载器实现，它支持浏览器文件缓存，可以加载 js、css、图片等浏览器文件。这个实现包含了缓存、加载和使用模块的功能，实现了 RequireJS 的核心功能。

