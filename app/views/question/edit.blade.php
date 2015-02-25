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
  
  <script>
  $(function() {
    
    $( "#tags" ).autocomplete({
      // source: availableTags
      source: function(request,response){
        $.ajax({
            dataType: "json",
            type : 'Get',
            url: "/concept/sandbox/"+request['term'],
            success: function(data) {
                console.log(data);
                console.log(request['term']);
                response(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
          
          
          
      },
      minLength: 4,
      select: function(event, ui) {
         console.log(event);
         console.log(ui['item']['data']);
         $.ajax({
            dataType: "json",
            type : 'Get',
            url: "/concept/questions/"+ui['item']['data']+"/direct/",
            success: function(data) {
                console.log(data);
                $.each(data['direct'],function(index,value){
                    console.log(value);
                             $.ajax({
                                dataType: "json",
                                type : 'Get',
                                url: "/question/show/"+value,
                                success: function(data) {
                                    $('#results').html($('#results').html()+"<div>"+
                                            "<div>"+JSON.stringify(data['id'])+" - "+JSON.stringify(data['question'])+"</div>"+
                                            "<div>a) "+JSON.stringify(data['option1'])+"</div>"+
                                            "<div>b) "+JSON.stringify(data['option2'])+"</div>"+
                                            "<div>c) "+JSON.stringify(data['option3'])+"</div>"+
                                            "<div>d) "+JSON.stringify(data['option4'])+"</div>"+
                                            "<div>e) "+JSON.stringify(data['option5'])+"</div>"+
                                            "</div>");
                                    console.log(data);
                                },
                                error: function(data) {
                                    console.log(data);
                                }
                            });
                });
            },
            error: function(data) {
                console.log(data);
            }
        });
      }
    });
  });
  </script>
</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header">Header</div>
    
        {{ Form::open(array('url' => 'question/edit')) }}

        <div id="question_container" class="row" >
            
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{Form::textarea('question',$question,array('style'=>'resize: none', 'class'=>'form-control col-xs-12', 'rows'=>'12'))}}</div>
        </div>
        
        <div id="options_container" class="row" >
            <div id="opt1">a) @if ($answer==1) {{Form::radio('answer', '1', true)}} @else {{Form::radio('answer', '1')}} @endif {{Form::text('option1',$opt1,array('style'=>'width: 90%'))}}</div>
            <div id="opt2">b) @if ($answer==2) {{Form::radio('answer', '2', true)}} @else {{Form::radio('answer', '2')}} @endif  {{Form::text('option2',$opt2,array('style'=>'width: 90%'))}}</div>
            <div id="opt3">c) @if ($answer==3) {{Form::radio('answer', '3', true)}} @else {{Form::radio('answer', '3')}} @endif  {{Form::text('option3',$opt3,array('style'=>'width: 90%'))}}</div>
            <div id="opt4">d) @if ($answer==4) {{Form::radio('answer', '4', true)}} @else {{Form::radio('answer', '4')}} @endif  {{Form::text('option4',$opt4,array('style'=>'width: 90%'))}}</div>
            <div id="opt5">e) @if ($answer==5) {{Form::radio('answer', '5', true)}} @else {{Form::radio('answer', '5')}} @endif  {{Form::text('option5',$opt5,array('style'=>'width: 90%'))}}</div>
        </div>
        {{Form::submit('Actualizar',array('class'=>'btn btn-danger'))}}
        {{ Form::close() }}
        
        <div id="concepts_container" class="row" >
            
            <div id="select_question" class="col-md-10 col-md-offset-1">
                @foreach($concepts as $concept)
                <button type="button" class="btn btn-info btn-sm">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    {{json_decode($concept->str)}}
                </button>

                @endforeach
            </div>
            
        </div>
        
        
        <div class="row">
            <div id="select_question" class="col-md-10 col-md-offset-1">
                
            </div>
        </div>
 
        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

