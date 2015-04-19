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


     
    <!--    
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
            <a class="navbar-brand" href="/logout">Logout</a>      
            <a class="navbar-brand" href="/user/profile">Bienvenido</a>  
            
        </div>
      </div>
    </nav>
    -->


    <div class="container">
      <!-- Example row of columns -->
      <div class="row">

        <div class="col-md-12">
            <div>
                
                <h1>Lista de exámenes</h1> 
                <p>{{Auth::user()->email}} ({{Auth::id()}})</p>
                {{Form::open(array('url'=>'exam/show'))}}
                    Examen ejemplo 
                    {{Form::hidden('questions_list', json_encode(array(1,2,3,4,624)))}}
                    {{Form::hidden('exam_id', json_encode(4))}}
                    {{Form::submit('Hacer test')}}
                {{Form::close()}}
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

