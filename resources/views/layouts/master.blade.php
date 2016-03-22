<!DOCTYPE html>
<html>
<head>
    <title>Shopus</title>
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.css">
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="nav">
       <a class="left navlink" href="/">Shopus</a>
       @if( Auth::user() )
        <a class="left navlink" href="/logout">Log Out</a>
       @else
        <a class="left navlink" href="/login">Log In</a>
       @endif
       <div class="right pure-form search_container">
           <input id="productsearch" class="pure-input-rounded" type="text" placeholder="Search Products" name="q"></input>
       </div>    
       <div class="clear"></div>  
    </div>

    <div class="container pure-g">
        <div class="content pure-u-1">
            <div id="app">
                @yield('content')
            </div>
        </div>
    </div>
    
    <div class="footer is-center l-box">
        © 2016 Shopus, Inc 
        •
        <a href="/about">About</a> 
        •
        <a href="/contact">Contact</a>
    </div>

    <script src="https://cdn.jsdelivr.net/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.js"></script>
    
    @yield ('script')
</body>
</html>