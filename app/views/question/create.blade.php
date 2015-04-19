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

<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>


<script src="/js/question.js"></script>


</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header"></div>
    
        {{ Form::open(array('url' => 'question/create', 'files'=>true)) }}

        <div>
            <select id="exam_list" name="exam_list" class="form-control">
                @foreach($exam_list as $exam)
                <option value="{{$exam->id}}">{{$exam->longname}}</option>
                @endforeach
            </select>
        </div>
        
        
        <div id="question_container" class="row" >
            
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{Form::textarea('question','',array('id'=>'question_textarea','style'=>'resize: none', 'class'=>'form-control col-xs-12', 'rows'=>'12', 'value'=>'España', 'required'))}}</div>
            
        </div>
        
        <div id="options_container" class="row" >
            <div id="opt1">a) {{Form::radio('answer', '1', false, array('required'))}} {{Form::text('option1','',array('style'=>'width: 90%', 'id'=>'option1', 'required'))}}</div>
            <div id="opt2">b) {{Form::radio('answer', '2')}} {{Form::text('option2','',array('style'=>'width: 90%', 'id'=>'option2', 'required'))}}</div>
            <div id="opt3">c) {{Form::radio('answer', '3')}} {{Form::text('option3','',array('style'=>'width: 90%', 'id'=>'option3'))}}</div>
            <div id="opt4">d) {{Form::radio('answer', '4')}} {{Form::text('option4','',array('style'=>'width: 90%', 'id'=>'option4'))}}</div>
            <div id="opt5">e) {{Form::radio('answer', '5')}} {{Form::text('option5','',array('style'=>'width: 90%', 'id'=>'option5'))}}</div>
        </div>
        
        <div>
            <span class="btn btn-default btn-file">Imagen
                {{Form::file('img',array('id'=>'imgSelector'))}}
            </span>
            
        </div>

        
        <div id="msgContainer" class="alert alert-danger"></div>
        
        <div>
            <button id="btn_concepts" class="btn btn-info">Conceptos</button>
            
            <input id="cui_list_input" type="hidden" name="cui_list_input" value="">
        </div>
        
        <div>
            {{Form::text('tags','',array('style'=>'width: 90%', 'id'=>'tags'))}}
        </div>
        
        
        {{Form::submit('Añadir',array('class'=>'btn btn-danger', 'id'=>'save_btn'))}}
        
        {{ Form::close() }}
        
         <div id="concepts_container" class="row" >
             
            <div id="select_question" class="col-md-10 col-md-offset-1">

            </div>
            
            <div id="ancestor_question" class="col-md-10 col-md-offset-1">

            </div> 
             
        </div>

        <div><canvas id="canvas" width="500px" height="300px"></canvas></div>
        
        



        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

