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
            <a class="navbar-brand" href="/logout">Logout</a>      
            <a class="navbar-brand" href="/user/profile">Bienvenido {{$user}}</a>  
            
        </div>
      </div>
    </nav>



    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
 
        <div class="col-md-12">
            <div>
                Usuario: {{$user}}
                <h1>Examen</h1> 
                {{Form::open(array('url'=>'/exam/show'))}}
                    
                    @foreach($questions as $index => $question)
                        <p>{{$index+1}}.- {{$question->question}}</p>
                        <span style="display:none">{{Form::radio($question->question_id,0, true)}}</span>
                        <ul>
                            <li>{{Form::radio($question->question_id,1)}} a) {{$question->option1}}</li>
                            <li>{{Form::radio($question->question_id,2)}} b) {{$question->option2}}</li>
                            <li>{{Form::radio($question->question_id,3)}} c) {{$question->option3}}</li>
                            <li>{{Form::radio($question->question_id,4)}} d) {{$question->option4}}</li>
                            <li>{{Form::radio($question->question_id,5)}} e) {{$question->option5}}</li>
                        </ul>
                    @endforeach
                    {{Form::hidden('exam_id',$exam_id)}}
                {{Form::submit('Enviar')}}
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

