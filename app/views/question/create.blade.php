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
        sessionStorage.setItem("cui_list_full",JSON.stringify([]));
        
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
            sessionStorage.setItem("cui_list","[]");
            sessionStorage.setItem("cui_list_full", "[]");
            the_cui_list = [];
            the_cui_list_full = [];
            $(".concept_tag").parent().remove();
            
            /*console.log($('#question_textarea').val()+" "
                            +$('#option1').val()+" "
                            +$('#option2').val()+" "
                            +$('#option3').val()+" "
                            +$('#option4').val()+" "
                            +$('#option5').val());*/
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
                    //console.log(data_parsed);
                    data_parsed.forEach(function(item){
                        the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
                        the_cui_list_full = JSON.parse(sessionStorage.getItem("cui_list_full"));
                        if($.inArray(item.cui, the_cui_list)<0){     
                            if(item.direct == 1) {
                                var new_html = $('#select_question').html()+"<button type='button' class='btn btn-info btn-sm' >\n"+"<span class=\"glyphicon glyphicon-remove concept_tag\" aria-hidden='true' id='"+item.cui+"'></span>"+item.concept_str+"</button>";
                                $('#select_question').html(new_html);
                            } else if (item.direct == 0) {
                                var new_html = $('#ancestor_question').html()+"<button type='button' class='btn btn-danger btn-sm' >\n<span class=\"glyphicon glyphicon-upload concept_tag\" aria-hidden='true' id='"+item.cui+"'></span>"+item.concept_str+"</button>";
                                $('#ancestor_question').html(new_html);
                                
                            }
                        
                            the_cui_list_full.push({cui: item.cui, term_id: item.term_id, concept_id: item.concept_id, direct:item.direct, ascendants:item.ascendants});
                            the_cui_list.push(item.cui);
                            
                            sessionStorage.setItem("cui_list", JSON.stringify(the_cui_list));
                            sessionStorage.setItem("cui_list_full", JSON.stringify(the_cui_list_full));
                            

                            $("#cui_list_input").val(sessionStorage.getItem("cui_list_full"));
                            



                            
                            

                        } 
                    });
                    $(".concept_tag").click(function(e){
                        e.preventDefault();
                        the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
                        the_cui_list_full = JSON.parse(sessionStorage.getItem("cui_list_full"));
                        console.log(the_cui_list_full);
                        list_of_other_ascendants = [];
                        the_cui_list_full.forEach(function(n){
                            if(n.direct==1) {
                                if(n.cui==e.target.id) {
                                    candidates_to_remove = n.ascendants;
                                } else {
                                     n.ascendants.forEach(function(a){
                                         /*
                                         if(list_of_other_ascendants.indexOf(a)==-1){
                                             list_of_other_ascendants.push(a);
                                         }           */
                                         if(a in list_of_other_ascendants) {
                                             list_of_other_ascendants[a] = list_of_other_ascendants[a]+1;
                                         } else {
                                             list_of_other_ascendants[a] = 1;
                                         }
                                     });          
                                }
                            }
                        });
                        

                        
                        
                        console.log("other_ascendants", list_of_other_ascendants);
                        console.log("candidates",candidates_to_remove);
                        
                        
                      
                        if (the_cui_list.indexOf(e.target.id) > -1) {
                            the_cui_list.splice(the_cui_list.indexOf(e.target.id), 1);
                            the_cui_list_full.splice(the_cui_list.indexOf(e.target.id), 1);
                        }
                        $("#"+e.target.id).parent().remove();
                        
                        candidates_to_remove.forEach(function(candidate){
                            //if(list_of_other_ascendants.indexOf(candidate)==-1) {
                            if(candidate in list_of_other_ascendants && list_of_other_ascendants[candidate]>1) {
                                console.log("Candidato en lista: "+candidate);
                                list_of_other_ascendants[candidate] = list_of_other_ascendants[candidate]-1;
                                console.log(list_of_other_ascendants[candidate]);
                            } else {
                                //if candidate not in the list of others ascendants
                                //or is the last one (count to one)
                                //remove that ascendant from cui_list and cui_list_full
                                the_cui_list.splice(the_cui_list.indexOf(candidate));
                                the_cui_list_full.splice(the_cui_list_full.indexOf(candidate));
                                $("#"+candidate).parent().remove();
                                console.log("Candidato eliminado: "+candidate);
                            }
                        });
                        
                        console.log("other_ascendants end:", list_of_other_ascendants);
                        console.log("candidates end:",candidates_to_remove);
                        
                        
                        
                        sessionStorage.setItem("cui_list", JSON.stringify(the_cui_list));    
                        sessionStorage.setItem("cui_list_full", JSON.stringify(the_cui_list_full));
                        
                        $("#cui_list_input").val(sessionStorage.getItem("cui_list_full"));
                        
                        console.log(the_cui_list_full);
                        
                        
                        /*
                        $.ajax({
                            dataType: "json",
                            type: "GET",
                            url: "/api/concept/ascendants/"+e.target.id,

                            success: function(data) {
                                console.log(data.ascendants);
                                console.log(JSON.parse(sessionStorage.getItem("cui_list_full")));
                            }
                        });
                        */

                    });
                }
            });
        });
    });
    
</script>


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
            
            <div id="ancestor_question" class="col-md-10 col-md-offset-1">

            </div> 
             
        </div>

        <div><canvas id="canvas" width="500px" height="300px"></canvas></div>
        
        



        <footer class="footer">Pie de página</footer>
    </div>
 
</body>
</html>

