<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('public/css/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 18px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid;
            border-collapse: collapse;
            padding: 5px;
        }
    </style>
</head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            <h3 class="font-w300 push-15">{{ $error }}</h3>
                        </div>
                    @endforeach
                @endif
                <div class="title m-b-md">
                    <form action="{{ route('api') }}" method="GET">
                        @csrf
                        <label>
                            Дата:
                            <input type="text" class="datepicker-here" name="date">
                        </label>
                        <input type="submit" value="Отправить">
                    </form>
                </div>

                <div class="links">
                    @if (!empty(intval($average)))
                        <p>Среднее значение длительности ответа Администратора: {{ $average }}</p>
                    @endif
                    @if (!empty(intval($max)))
                        <p>Максимальное значение длительности ответа Администратора: {{ $max }}</p>
                    @endif
                    @if (!empty(intval($min)))
                        <p>Минимальное значение длительности ответа Администратора: {{ $min }}</p>
                    @endif
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Дата сообщения</th>
                                <th>Текст сообщения</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($arMessages))
                                @foreach ($arMessages as $message)
                                    <tr>
                                        <td>{{ $message->id }}</td>
                                        <td>{{ date('d.m.Y', $message->date) }}</td>
                                        <td>{{ $message->text }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    <script src="{{ asset('public/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/datepicker.min.js') }}"></script>
</html>
