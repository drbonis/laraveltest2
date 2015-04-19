<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pregunta {{$question_id}}</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  

</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header">Header</div>
    
        <div id="question_img">
            @if($img <> null)
                <img id="question_img_prev" src="/img/questions/{{$img}}">
            @endif
        </div>
        
        <div id="question_container" class="row" >
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{$question}}</div>
        </div>
        
        <div id="options_container" class="row" >
            <div id="opt1" class="col-md-10 col-md-offset-1">a) {{$opt1}}</div>
            <div id="opt2" class="col-md-10 col-md-offset-1">b) {{$opt2}}</div>
            <div id="opt3" class="col-md-10 col-md-offset-1">c) {{$opt3}}</div>
            <div id="opt4" class="col-md-10 col-md-offset-1">d) {{$opt4}}</div>
            <div id="opt5" class="col-md-10 col-md-offset-1">e) {{$opt5}}</div>
        </div>
        
        <div id="concepts_container" class="row" >
            
            <div id="select_question" class="col-md-10 col-md-offset-1">
                @foreach($concepts as $concept)
                        <span class="label label-primary">
                            {{json_decode($concept->str)}}
                        </span>
                @endforeach
            </div>
        
        </div>
        
        
        <div class="row">
            <div id="select_question" class="col-md-10 col-md-offset-1">
                <input class="btn btn-info" type="button" value="Siguiente">
            </div>
        </div>
 
        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

