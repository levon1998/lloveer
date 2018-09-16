<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hi,
    <br>
    Your Link for reset password
    <br>

    <a href="{{ route('password.request', ['token' => $resetCode])}}">Reset My Password </a>

    <br/>
</div>

</body>
</html>