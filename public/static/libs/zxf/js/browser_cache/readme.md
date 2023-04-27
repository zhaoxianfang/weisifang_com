# browser cache(浏览器文件加载缓存)

> 本文件主要是为了让浏览器一次加载资源后形成临时缓存，再次访问时候就直接加载缓存的方式降低浏览器加载资源文件的等待时间

## demo

加载本资源缓存库
```
默认使用 browserCacheFile.load 方法区定义加载
<script src="./browser_cache/browser_cache_file.js" type="text/javascript" charset="utf-8" ></script>

设置了 auto_cache_file 并且值为 true 时候会自动缓存已经加载的资源文件
<script src="./browser_cache/browser_cache_file.js" type="text/javascript" charset="utf-8" auto_cache_file="true"></script>
```

加载缓存模块文件
```
 <style type="text/css">
	#text{
		font-family: "自定义佛系字体"; /* 同ttf中定义的fontFamily名称一致 */
	}
  </style>
<script type="text/javascript">

  // 第一个数组参数里面用来加载不规范的js文件(没有使用define定义的文件)或css、ttf、图片、mp3等文件
  // 第二个数组参数里面用来加载规范的js文件(使用define定义的模块文件)
  browserCacheFile.load([
    { url: 'https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js'},
    { url: './m/test1.js' },
    { url: './m/foxi.ttf',fontFamily:"自定义佛系字体" }, // ttf 字体文件需要使用 fontFamily 指明字体名称
    { url: 'http://example.com/banner4.jpg'}
  ], [
    { url: './m/m1.js' },
    { url: './m/m2.js' }
  ], function() {
    require(['m1', 'm2'], function(module1, module2) {
      // 上面的文件已经加载完成
      console.log('加载完==')
      console.log(module1,module2)
  });
</script>
```


```
# /m/test1.js 文件内容
var test1={
	name:'test 1'
}
```

```
# /m/m1.js 文件内容
define('m1', [], function() {
  return {
    'name':'m1'
  };
});

```
