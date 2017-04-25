<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript" src="{{ ASSET_DIR.'js/jquery-2.1.3.min.js' }}"></script>

    <link rel="stylesheet" href="{{ ASSET_DIR.'css/bootstrap.min.css' }}">
    <script type="text/javascript" src="{{ ASSET_DIR.'js/bootstrap.min.js' }}"></script>

    {{--File Upload Dependencies--}}
    <link href="{{ ASSET_DIR.'file-input/css/fileinput.min.css' }}" media="all" rel="stylesheet" type="text/css" />
    <!-- the main fileinput plugin file -->
    <script src="{{ ASSET_DIR.'file-input/js/fileinput.min.js' }}" type="text/javascript"></script>
    <!-- optionally if you need a theme like font awesome theme you can include
        it as mentioned below -->
    <script src="{{ ASSET_DIR.'file-input/themes/fa/theme.js' }}" type="text/javascript"></script>
    <!-- optionally if you need translation for your language then include
        locale file as mentioned below -->
    <script src="{{ ASSET_DIR.'file-input/js/locales/zh.js' }}" type="text/javascript"></script>

    <title>{{ $title or 'Know Your Employee' }}</title>
</head>

<body>

<style type="text/css">
    .modal {
        text-align: center;
    }

    @media screen and (min-width: 768px) {
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: "";
            height: 100%;
        }
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }

    .outer {
        display: block;
        margin: auto;
        position: relative;
        width: 22%;
    }

    div.required > label:after {
        content: '*';
        /*font-size: 150%;*/
        color: red;
    }
</style>

@yield('CSSContent')

<div class="container text-center">
    <button type="button" class="btn btn-default" style="display: block; width: 100%"
            onclick="location.href='{{ route('welcome') }}'">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
    </button>
</div>

<div id="alert-modal" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-text-area">

            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="col-md-1"></div>
    <div class="col-md-10" id="main-container">
        <div class="page-header">
            <h2>{{ $title or 'Know Your Employee' }}</h2>
        </div>
        <br>

        @yield('HTMLContent')

    </div>
    <div class="col-md-1"></div>
</div>
</body>
</html>

<script type="text/javascript">
    var setAlertText = function(content) {
        $('#modal-text-area').text('').append(content);
    };

    var handleReturn = function (data, successHandler) {
        if ('good' == data.status) {
            successHandler = successHandler || function () {
                window.location.reload();
            };
            successHandler();
        } else if ('ERR001' == data.status) {
            location.href = "http://"+"{{ env('PLATFORM_HOST') }}"+"/platform/index.php";
        } else if ('close' == data.status) {
            setAlertText("操作成功, 页面即将关闭。");
            $('#alert-modal').modal('show');

            setTimeout('window.close()', 3000);
        }
        else {
            setAlertText(data.status+": "+data.errorInfo);
            $("#alert-modal").modal('show');
        }
    }

    var headers = {
        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
    };
</script>

@yield('javascriptContent')
