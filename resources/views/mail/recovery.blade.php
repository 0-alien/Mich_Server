<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>

  <body style="background-color: #EEE; font-family: Tahoma;">
    <div style="padding: 15px; border: 1px solid #000; border-bottom: none; background-color: #cc3a1a;
                   font-size: 16px; font-weight: 600; letter-spacing: 1px;">MICH</div>

    <div style="border: 1px solid #000; background-color: #FFF; padding: 15px; font-size: 12px;">
      <span>Hello {{ $name }},</span>

      <br><br>

      <span>Here is your recovery code: <strong>{{ $code }}</strong></span>
    </div>
  </body>
</html>