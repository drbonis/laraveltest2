<!doctype html>
<html>
    <head>
            <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        
       
        
        <title></title>
        <script>
            $(function(){
                $questions_id_list = JSON.parse($('#questions_id_list').val());
                $('.next_question').click(function(e){
                    e.preventDefault();
                    
                        $this_question_id = $(e.target.parentElement.parentElement).attr("question_id");
                        
                        $this_question_answer = $('input[name=answer'+$this_question_id+']:checked').val();
                        $this_question_correct_answer = $('#code').val();
                        $user_id = $('#user_id').val();
                        
                        
                        $.ajax({
                            dataType: "json",
                            type: "POST",
                            url: "/api/question/answer",
                            data: {
                                'exam_id': $('#exam_id').val(),
                                'execution_id': $('#execution_id').val(),
                                'question_id': $this_question_id,
                                'answer': $this_question_answer,
                                'correct_answer': $this_question_correct_answer,
                                'user_id': $user_id
                            },
                                    
                            success:function(data){
                                console.log(data)
                            }
                                    
                                    
                        });
                        
                    if(e.target.value>-1) {    
                        
                        $('.question').hide();
                        $('#question_'+e.target.value).show();
                        
                    } else {
                        //is last question in the exam
                        $('.question').hide();
                        $('#exam_finished_msg').show();
                    }
                });
            });
        </script>
        
    </head>
    <body>


     



    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
 
        <div class="col-md-12">
            <div>

                <h1>Examen</h1> 
                
                {{Form::open(array('url'=>'/exam/show'))}}
                    {{Form::hidden('questions_id_list',json_encode($questions_id_list), array('id'=>'questions_id_list'))}}
                    {{Form::hidden('exam_id',$exam_id, array('id'=>'exam_id'))}}
                    {{Form::hidden('execution_id',$execution_id, array('id'=>'execution_id'))}}
                    {{Form::hidden('user_id', json_encode(Auth::id()), array('id'=>'user_id'))}}
                    @foreach($questions as $index => $question)
                    
                    <div id="question_{{$question->id}}" question_id="{{$question->id}}" class="question" @if($index>0) style="display: none" @endif>
                        <div>
                            @if($question->img<>null)
                                <img src="/img/questions/{{$question->img}}">
                            @endif
                        </div>
                        {{Form::hidden('code', json_encode($question->answer), array('id'=>'code'))}} 
                        <div>{{$question->question}}</div>
                        <div>
                            <div style="visibility: hidden">{{Form::radio('answer'.$question->id, 0, true, array('style'=>'visibility: hidden'))}}</div>
                                <div>{{Form::radio('answer'.$question->id,1)}}a) {{$question->option1}}</div>
                                <div>{{Form::radio('answer'.$question->id,2)}}b) {{$question->option2}}</div>
                                <div>{{Form::radio('answer'.$question->id,3)}}c) {{$question->option3}}</div>
                                <div>{{Form::radio('answer'.$question->id,4)}}d) {{$question->option4}}</div>
                                <div>{{Form::radio('answer'.$question->id,5)}}e) {{$question->option5}}</div>

                        </div>
                        <div>
                            <button class="btn btn-default next_question" type="submit" 
                                    
                                    @if($index<count($questions)-1)
                                        value="{{$questions_id_list[$index+1]}}"
                                    @else
                                        value="-1"
                                    @endif
                                    ">Enviar</button>
                        </div>
                    </div>
                    @endforeach
                
                {{Form::close()}}
                
            </div>
            <div id="exam_finished_msg" style="display:none">
                <p>Exámen finalizado, botón a lista de exámenes y mostrar aquí resumen de resultados</p>
                
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

