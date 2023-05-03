<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cherry Editor - Markdown Editor</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        video {
            max-width: 400px;
        }

        #demo-val {
            display: none;
        }

        img {
            max-width: 100%;
        }
        iframe.cherry-dialog-iframe {
            width: 100%;
            height: 100%;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('static/libs/cherry-markdown/dist/cherry-markdown.min.css') }}">
    <link rel="Shortcut Icon" href="{{ asset('static/libs/cherry-markdown/logo/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.12.0/dist/katex.min.css"
          integrity="sha384-AfEj0r4/OFrOo5t7NnNe46zW/tFgW6x/bCJG8FqQCEo3+Aro6EYUG4+cU+KJWu/X" crossorigin="anonymous">
{{--    <link href="{{ asset('static/libs/cherry-markdown/examples/markdown/basic.md') }}" rel="preload">--}}
</head>

<body>
<div id="dom_mask" style="position: absolute; top: 40px; height: 20px; width: 100%;"></div>
<div id="markdown"></div>

{{--<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts@4.6.0/dist/echarts.js"></script>--}}
{{--引用本地的--}}
{{--<script type="text/javascript" src="{{ asset('static/libs/echarts/4.6.0/echarts.min.js') }}"></script>--}}

<!--<script src="https://cdn.jsdelivr.net/npm/katex@0.12.0/dist/katex.min.js" integrity="sha384-g7c+Jr9ZivxKLnZTDUhnkOnsh30B4H0rpLUpJ4jAIKs4fnJI+sEnkvrMWph2EDg4" crossorigin="anonymous"></script>-->
<script src="{{ asset('static/libs/cherry-markdown/dist/cherry-markdown.min.js') }}"></script>
<script src="{{ asset('static/libs/cherry-markdown/examples/scripts/pinyin/pinyin_dist.js') }}"></script>
<script src="{{ asset('static/libs/cherry-markdown/examples/scripts/index-demo.js') }}"></script>
</body>

</html>
