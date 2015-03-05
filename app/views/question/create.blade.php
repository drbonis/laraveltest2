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


<script>
    
    $(function(){
        sessionStorage.setItem("cui_list",JSON.stringify([]));
        $('#msgContainer').fadeOut(0);
        $('#imgSelector').change(function(){
            var fileToUpload = $('#imgSelector')[0].files[0];
            $('#msgContainer').fadeOut(10);
            if(fileToUpload.size>200000) {
                $('#msgContainer').html("La imagen tiene un tamaño demasiado grande");
                $('#msgContainer').fadeIn(10);
                document.getElementById("imgSelector").value = "";
            }
        });
        
        $('#imgSelector').change(function(e) {
            var file = e.target.files[0],
                imageType = /image.*/;

            if (!file.type.match(imageType))
                return;

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);        
        });

        function fileOnload(e) {
            var $img = $('<img>', { src: e.target.result });
            var canvas = $('#canvas')[0];
            var context = canvas.getContext('2d');

            $img.load(function() {
                context.drawImage(this, 0, 0);
            });
        }
        
        $('#btn_concepts').click(function(e){
            e.preventDefault();
            $.ajax({
                dataType: "json",
                type: "POST",
                url:"/api/concept/fromtext",
                data: {'text': $('#question_textarea').val()+" "
                            +$('#option1').val()+" "
                            +$('#option2').val()+" "
                            +$('#option3').val()+" "
                            +$('#option4').val()+" "
                            +$('#option5').val()
                            },
                success:function(data){
                    data_parsed = JSON.parse(data);

                    
                    data_parsed.forEach(function(item){
                        the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
                        if($.inArray(item.cui, the_cui_list)<0){
                            var new_html = $('#select_question').html()+"<button type='button' class='btn btn-info btn-sm' >\n"+"<span class=\"glyphicon glyphicon-remove concept_tag\" aria-hidden='true' id='"+item.cui+"'></span>"+item.concept_str+"</button>";
                            $('#select_question').html(new_html);
                            the_cui_list.push(item.cui);
                            sessionStorage.setItem("cui_list", JSON.stringify(the_cui_list));
                            console.log(sessionStorage.getItem("cui_list"));
                            $("#cui_list_input").val(sessionStorage.getItem("cui_list"));
                            $(".concept_tag").click(function(e){
                                the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
                                index = the_cui_list.indexOf(e.target.id);
                                if (index > -1) {
                                    the_cui_list.splice(index, 1);
                                }
                                sessionStorage.setItem("cui_list", JSON.stringify(the_cui_list));       
                                $("#"+e.target.id).parent().remove();
                                $("#cui_list_input").val(sessionStorage.getItem("cui_list"));
                            });
                        }
                    });
                }
            });
        });
        /*
        $('#save_btn').click(function(e){
            e.preventDefault();
            the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
            console.log(the_cui_list);
            
            $.post("/api/question/create", {id: 1, name: "asdf"}, function(data,status){
               console.log(data);
               console.log(status);
            });
           
            
        });
        */

        
   
    });
    
</script>


</head>
<body>
 
    <div class="container" id="container">

        <div class="header" id="header"></div>
    
        {{ Form::open(array('url' => 'question/create', 'files'=>true)) }}

        <div>
            <select id="exam_list" class="form-control">
                @foreach($exam_list as $exam)
                <option value="{{$exam->id}}">{{$exam->longname}}</option>
                @endforeach
            </select>
        </div>
        
        
        <div id="question_container" class="row" >
            
            <div id="question" class="jumbotron col-md-10 col-md-offset-1">{{Form::textarea('question','',array('id'=>'question_textarea','style'=>'resize: none', 'class'=>'form-control col-xs-12', 'rows'=>'12', 'required'))}}</div>
            
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
        
        
        {{Form::submit('Añadir',array('class'=>'btn btn-danger', 'id'=>'save_btn'))}}
        
        {{ Form::close() }}
        
         <div id="concepts_container" class="row" >
             
            <div id="select_question" class="col-md-10 col-md-offset-1">

            </div>
            
        </div>

        <div><canvas id="canvas" width="500px" height="300px"></canvas></div>
        
        



        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

