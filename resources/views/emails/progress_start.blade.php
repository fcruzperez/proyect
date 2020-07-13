<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME')}}</title>
</head>
<body>
<h3>You have a new order!</h3>
<p>Your offer for the parent {{$details['task_name']}} was accepted.</p>
<p>You must attach the embroidery matrix {{$details['format']}} within {{$details['left_time']}}.</p>
<p>See details.</p>
</body>
</html>
