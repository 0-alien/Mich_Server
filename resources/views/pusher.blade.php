<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('631cad75e06b7aa8904a', {
      cluster: 'eu',
      encrypted: true
    });

    var channel = pusher.subscribe('59');
    channel.bind('invitation', function(data) {
      console.log(data);
    });
  </script>
</head>