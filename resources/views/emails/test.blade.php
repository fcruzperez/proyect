<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
<h1>Welcome back {{ $details['firstname'] }}!!!</h1>
<h1>Welcome back {{ $details['title'] }}!!!</h1>
<p>{{ $details['content'] }}</p>

<p>Thank you</p>
</body>
</html>
