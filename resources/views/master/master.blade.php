<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Artist</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link rel="stylesheet" href="<?php echo url('/'); ?>/css/smoothness-ui.css" >
    <link rel="stylesheet" href="<?php echo url('/'); ?>/css/bootstrap.css" >
    <link rel="stylesheet" href="<?php echo url('/'); ?>/css/jquery-ui.css" >
    <?php /*
        <link rel="stylesheet" href="<?php echo url('/'); ?>/css/smoothness-ui.css" >*/
     ?>
    <link rel="stylesheet" href="<?php echo url('/'); ?>/css/style.css" >

    <!-- Styles -->
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
    @stack('css')    
</head>

<body id="container-fluid">
    <nav class="navbar navbar-default navbar-static-top">
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

                    <img src="<?php echo url('/').'/images/logo.png'; ?>" width="50"> 
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Artist </a></li>
                    <li><a href="#">Albums </a></li>
                    <li><a href="#">Discography</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <?php  /*
                        <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>*/
                        ?>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <?php 
        /*<div id='sidebar-wrapper'>
            <ul style='position: fixed;top: 11px;background-color: #1d1d1d;' class='nav sidebar-nav'>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                            <i class='fa fa-fw fa-plus'></i>menu
                            <span class='caret'></span>
                        </a>
                        <ul class='dropdown-menu' role='menu'>
                                <li><a href='#'>submenu</a></li>
                        </ul>
                    </li>
            </ul>
        </div>*/
     ?>
    @yield('content')
    <!-- JavaScripts -->
    <script src="<?php echo url('/'); ?>/js/jquery.js" ></script>
    <script src="<?php echo url('/'); ?>/js/jquery-ui.js"></script>
    <script src="<?php echo url('/'); ?>/js/handlebars-latest.js"></script>
    @stack('scripts')    
</body>
</html>


