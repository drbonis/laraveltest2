<!doctype html>
<html>
    <head>
            <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        
       
        
        <title></title>
    </head>
    <body>


     
        
            <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="#">Project name</a>
            <a class="navbar-brand" href="../logout">Logout</a>      
            <a class="navbar-brand" href="#">Bienvenido {{$user}}</a>  
            
        </div>
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
            <h2>Menú lateral</h2>
            <p>Menú lateral que contiene algunos
            contenidos, como por ejemplo secciones.</p>
        </div>
        <div class="col-md-8">
            <div>
                Usuario: {{$user}}
                <h1>Examen</h1> 

                @foreach($questions as $index => $question)
                    <p>{{$index+1}}.- {{$question->question}}</p>
                    <ol>
                        <li>{{$question->option1}}</li>
                        <li>{{$question->option2}}</li>
                        <li>{{$question->option3}}</li>
                        <li>{{$question->option4}}</li>
                        <li>{{$question->option5}}</li>
                    </ol>
                @endforeach
            </div>
        </div>


      </div>

      <hr>

      <footer>
        <p>&copy; drbonis 2014</p>
      </footer>
    </div> <!-- /container -->
        
        
         <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    </body>
</html>

