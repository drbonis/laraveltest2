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

<script src="/js/question.js"></script>


</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header"></div>
        

    
        {{ Form::open(array('url' => 'question/edit', 'files'=> 'true')) }}
        
        <div id="question_img">
            
            @if($img <> null)
                <img id="question_img_prev" src="/img/questions/{{$img}}">
                {{Form::hidden('prev_img',$img)}}
            @else
                {{Form::hidden('prev_img',null)}}
            @endif
            <canvas id="canvas" width="500px" height="300px"></canvas>
        </div>
        
        <div>
            <span class="btn btn-default btn-file">Imagen
                {{Form::file('img',array('id'=>'imgSelector'))}}
            </span>
            
        </div>

        <div id="question_container" class="row" >
            
            {{Form::hidden('question_id',$question_id)}}
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{Form::textarea('question',$question,array('id'=>'question_textarea', 'style'=>'resize: none', 'class'=>'form-control col-xs-12', 'rows'=>'12'))}}</div>
            
        </div>
        @if ($msg<>'') <div id="msg" class="alert alert-success" role="alert" >{{$msg}}</div>@endif
        <div id="options_container" class="row" >
            <div id="opt1">a) @if ($answer==1) {{Form::radio('answer', '1', true)}} @else {{Form::radio('answer', '1')}} @endif {{Form::text('option1',$opt1,array('id'=>'option1', 'style'=>'width: 90%'))}}</div>
            <div id="opt2">b) @if ($answer==2) {{Form::radio('answer', '2', true)}} @else {{Form::radio('answer', '2')}} @endif  {{Form::text('option2',$opt2,array('id'=>'option2', 'style'=>'width: 90%'))}}</div>
            <div id="opt3">c) @if ($answer==3) {{Form::radio('answer', '3', true)}} @else {{Form::radio('answer', '3')}} @endif  {{Form::text('option3',$opt3,array('id'=>'option3', 'style'=>'width: 90%'))}}</div>
            <div id="opt4">d) @if ($answer==4) {{Form::radio('answer', '4', true)}} @else {{Form::radio('answer', '4')}} @endif  {{Form::text('option4',$opt4,array('id'=>'option4', 'style'=>'width: 90%'))}}</div>
            <div id="opt5">e) @if ($answer==5) {{Form::radio('answer', '5', true)}} @else {{Form::radio('answer', '5')}} @endif  {{Form::text('option5',$opt5,array('id'=>'option5', 'style'=>'width: 90%'))}}</div>
        </div>
        
        <div>
            <button id="btn_concepts" class="btn btn-info">Conceptos</button>
            <input id="cui_list_input" type="hidden" name="cui_list_input" value='{{json_encode($concepts)}}'>
        </div>
        
        <div>
            {{Form::text('tags','',array('style'=>'width: 90%', 'id'=>'tags'))}}
        </div>
        
        
        {{Form::submit('Actualizar',array('class'=>'btn btn-danger'))}}
        {{ Form::close() }}
        
        <div id="concepts_container" class="row" >
            
            <div id="select_question" class="col-md-10 col-md-offset-1">
                @foreach($concepts as $concept)
                    @if($concept->direct == 1)                    
                        <!--<button type='button' class='btn btn-info btn-sm' >-->
                        <span class="label label-primary">
                            <span class="concept_tag" aria-hidden='true' id='{{$concept->id}}'></span>{{json_decode($concept->str)}}
                        </span>
                        <!--</button>-->
                    @endif
                @endforeach
            </div>
            
            <div id="ancestor_question" class="col-md-10 col-md-offset-1">
                @foreach($concepts as $concept)
                    @if($concept->direct == 0)                    
                        <span class="label label-danger">
                            <span class='ancestor_tag' aria-hidden='true' id='{{$concept->cui}}'></span>{{json_decode($concept->str)}}
                        </span>
                    @endif
                @endforeach
            </div>
            
        </div>

        
        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

