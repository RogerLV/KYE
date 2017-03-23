<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="{{ env('ASSET_DIR').'js/jquery-2.1.3.min.js' }}"></script>
    <link rel="stylesheet" href="{{ env('ASSET_DIR').'css/bootstrap.css' }}">
    <script type="text/javascript" src="{{ env('ASSET_DIR').'js/bootstrap.js' }}"></script>

    <title>Dummy Entry</title>
</head>

<body>
<form action="{{ route('welcome') }}" method="POST" target="_blank" id="myform">
    <div class="form-group">
        <label for="user">Login User:</label>
        <input type="text" class="form-control" id="user">
    </div>
    <input type="hidden" name="LanID" id="LanID">
</form>
<button id="login-button" class="btn btn-lg btn-primary">Login</button>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $('#login-button').click(function(){
            var lanID = $('#user').val();
            $('#LanID').val(btoa(lanID));
            $('#myform').submit();
        });
    });
</script>