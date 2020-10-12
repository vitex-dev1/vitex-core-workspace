<!DOCTYPE html>
<html>
<head>
    <title>Error!</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }

        .error {
            font-size: 100px;
        }

        .red {
            color: #ff6464;
        }

        p {
            color: #FF0000;
            font-size: 24px;
            font-weight: 600;
        }

        .btn {
            font-weight: 600;
            color: #fff;
            font-size: 18px;
            text-decoration: none;
            padding: 8px 15px;
            background: #828080;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title red">
            <strong class="error">403</strong> Forbidden access!
        </div>

        @if(!empty($exception->getMessage()))
            @php
                $data = json_decode($exception->getMessage(), true);
            @endphp

            @if(empty($data['user']['active']))
                <p>Your account has been deactivated by the administrators. Please contact them for more details.</p>
            @else
                <a class="btn" href="{!! !empty($data['link']) ? $data['link'] : '#' !!}">Back to profile</a>
            @endif
        @else
            <a class="btn" href="javascript:history.back();">Back to home</a>
        @endif
    </div>
</div>
</body>
</html>
