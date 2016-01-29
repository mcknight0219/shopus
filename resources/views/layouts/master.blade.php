<!DOCTYPE html>
<html>
<head>
    <title>Shopus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/grids-responsive-min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <div class="nav">
       <a class="left navlink" href="/cms">Shopus</a>
       <a class="left navlink" href="/cms/login">Login</a>
       <div class="right pure-form search_container">
           <input id="productsearch" class="pure-input-rounded" type="text" placeholder="Search Products" name="q"></input>
       </div>    
       <div class="clear"></div>  
    </div>

    <div class="container pure-g">
        <div class="content pure-u-1">
            @yield('content')
        </div>
    </div>
    <div class="footer is-center l-box">
        © 2016 Shopus, Inc 
        •
        <a href="/about">About</a> 
        •
        <a href="/contact">Contact</a>
    </div>
    <script src="/js/main.js"/>
</body>
</html>