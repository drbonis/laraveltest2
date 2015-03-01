<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pregunta </title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script>
    
    $(function(){
        $('#msgContainer').fadeOut(0);
        $('#imgSelector').change(function(){
            var fileToUpload = $('#imgSelector')[0].files[0];
            if(fileToUpload.size>200000) {
                $('#msgContainer').html("La imagen tiene un tamaño demasiado grande");
                $('#msgContainer').fadeIn(10);
                document.getElementById("imgSelector").value = "";
            }
            console.log(fileToUpload);

        });
   
    });
    
</script>


</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header"></div>
    
        {{ Form::open(array('url' => 'question/create', 'files'=>true)) }}

        <div id="question_container" class="row" >
            
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{Form::textarea('question','',array('style'=>'resize: none', 'class'=>'form-control col-xs-12', 'rows'=>'12'))}}</div>
            
        </div>
        
        <div id="options_container" class="row" >
            <div id="opt1">a) {{Form::radio('answer', '1')}} {{Form::text('option1','',array('style'=>'width: 90%'))}}</div>
            <div id="opt2">b) {{Form::radio('answer', '2')}} {{Form::text('option2','',array('style'=>'width: 90%'))}}</div>
            <div id="opt3">c) {{Form::radio('answer', '3')}} {{Form::text('option3','',array('style'=>'width: 90%'))}}</div>
            <div id="opt4">d) {{Form::radio('answer', '4')}} {{Form::text('option4','',array('style'=>'width: 90%'))}}</div>
            <div id="opt5">e) {{Form::radio('answer', '5')}} {{Form::text('option5','',array('style'=>'width: 90%'))}}</div>
        </div>
        {{Form::file('img',array('id'=>'imgSelector'))}}
        <div id="msgContainer" class="alert alert-danger"></div>
        {{Form::submit('Añadir',array('class'=>'btn btn-danger'))}}
        {{ Form::close() }}
        <canvas id="imgPreview" style="border:1px solid #d3d3d3;"></canvas>
        <div id="concepts_container" class="row" >
            
            <div id="select_question" class="col-md-10 col-md-offset-1">

            </div>
            
        </div>
 
        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

