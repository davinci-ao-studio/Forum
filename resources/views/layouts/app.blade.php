<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Localghost</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

         <!-- JS voor Calendar -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
     <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
     <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
     <script src="http://fullcalendar.io/js/fullcalendar-2.7.1/lang/nl.js"></script>
     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
        .quick-btn .label {
          position: absolute;
          top: -5px;
          right: -5px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Localghost
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/topic') }}">Home</a></li>
                    <li><a href="{{url('/profile')}}">Leerlingen</a></li>
                    <li><a href="{{url('event')}}">Agenda</a></li>                 
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                        <li style="margin-top: 10px;">
                        {!! Form::open(array('url' => 'search', 'required')) !!}
                            <div class="form-group" style="left: 100px;width:400px;">
                                {!! Form::text('Search', null, ['class' => 'form-control', 'placeholder' => 'Zoeken', 'required'] ) !!}
                            </div>
                        {!! Form::close() !!}
                    </li> 
                    <!-- Authentication Links -->
                    @if (Auth::guest())

                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>

                    @else
                    <?php         
                         $user = \Auth::user();
                        $info =  DB::table('users')
                        ->where('id', '=', $user->id)
                        ->get(); 
                        foreach ($info as $result){
                            $role = $result->role;
                        } 
                    ?>
                @if($role != 0)
                        <li><a href="{{ url('/beheer') }}">Beheer</a></li>
                        @endif
                        

                        <li><a href="{{ url('/topic/create')}}">Maak leervraag</a></li>

                    <?php         
                        $user = \Auth::user();
                            $count = DB::table('notifications')
                            ->where('receiver_id','=', $user->id)
                            ->where('read', '=', '0')
                            ->count();
                    ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} 
                                @if($count == '0')
                                    <span class="label label-info" style="display:none;">{{$count}}</span> 
                                @else
                                    <span class="label label-info">{{$count}}</span>
                                @endif
                                    <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/profile/<?=$result->id?>"><i class="fa fa-btn fa-user"></i>Profiel</a></li>
                                <li><a href="{{ url('/notificaties') }}"><i class="fa fa-btn fa-bell"></i>Notificaties</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
        @if (Session::has('flash_message_succes'))
            <div class="container alert alert-success"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('flash_message')}}</div>
        @endif

        @if (Session::has('flash_message_alert'))
            <div class="container alert alert-danger"> 
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('flash_message_alert')}}</div>
        @endif

    
    @yield('content')

    <!-- JavaScripts -->
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
