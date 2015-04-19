  
$(function(){
        sessionStorage.setItem("cui_list",JSON.stringify([]));
        sessionStorage.setItem("cui_list_full",JSON.stringify([]));
        //$('#question_textarea').val("españa italia francia china japón argentina chile perú");
        
        
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
            $('#question_img_prev').hide();
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
            console.log("conceptos");
            console.log($('#question_textarea').val());
            //inicializo todo
            sessionStorage.setItem("cui_list","[]");
            sessionStorage.setItem("cui_list_full", "[]");
            the_cui_list = [];
            the_cui_list_full = [];
            $(".concept_tag").parent().remove(); 
            $(".ancestor_tag").parent().remove(); 

            $.ajax({
                dataType: "json",
                type: "POST",
                url:"/api/concept/fromtext",
                data: {'text': $('#question_textarea').val()+" "
                            +$('#option1').val()+" "
                            +$('#option2').val()+" "
                            +$('#option3').val()+" "
                            +$('#option4').val()+" "
                            +$('#option5').val()+" "
                            +$('#tags').val()
                            },
                success:function(data){
                    console.log(data);
                    data_parsed = JSON.parse(data);
                    data_parsed.forEach(function(item){
                        the_cui_list = JSON.parse(sessionStorage.getItem("cui_list"));
                        the_cui_list_full = JSON.parse(sessionStorage.getItem("cui_list_full"));
                        if($.inArray(item.cui, the_cui_list)<0){ //si no esta en la lista    
                            if(item.direct == 1) { // si es un concepto encontrado en texto
                                var new_html = $('#select_question').html()+"<button type='button' class='btn btn-info btn-sm' >\n"+"<span class=\"glyphicon glyphicon-remove concept_tag\" aria-hidden='true' id='"+item.cui+"'></span>"+item.concept_str+"</button>";
                                $('#select_question').html(new_html);
                            } else if (item.direct == 0) { // si es un concepto ancestro
                                var new_html = $('#ancestor_question').html()+"<button type='button' class='btn btn-danger btn-sm' >\n<span class=\"glyphicon glyphicon-upload ancestor_tag\" aria-hidden='true' id='"+item.cui+"'></span>"+item.concept_str+"</button>";
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
                       
                        list_of_other_ascendants = [];
                        the_cui_list_full.forEach(function(n){   
                            if(n.direct==1) {
                                    console.log(n);
                                     n.ascendants.forEach(function(a){
                                         if(a in list_of_other_ascendants) {
                                             list_of_other_ascendants[a] = list_of_other_ascendants[a]+1;
                                         } else {
                                             list_of_other_ascendants[a] = 1;
                                         }
                                     }); 
                                if(n.cui==e.target.id) {
                                    candidates_to_remove = n.ascendants;
                                }
                            }
                        });
 
                        if (the_cui_list.indexOf(e.target.id) > -1) {
                            
                                this_index = the_cui_list.indexOf(e.target.id);
                                the_cui_list.splice(this_index, 1);
                                the_cui_list_full.splice(this_index, 1);
                        } 
                        $("#"+e.target.id).parent().remove(); 
                        
                        candidates_to_remove.forEach(function(candidate){
                            
                            if((candidate in list_of_other_ascendants) && (list_of_other_ascendants[candidate]>1)) {
                                console.log("Candidato en lista: "+candidate);
                                list_of_other_ascendants[candidate] = list_of_other_ascendants[candidate]-1;
                                console.log(list_of_other_ascendants[candidate]);
                            } else {
                                //if candidate not in the list of others ascendants
                                //or is the last one (count to one)
                                //remove that ascendant from cui_list and cui_list_full
                                this_index = the_cui_list.indexOf(candidate);
                                the_cui_list.splice(this_index, 1);
                                the_cui_list_full.splice(this_index, 1);
                                $("#"+candidate).parent().remove();
                            }
                        });
                        
                        sessionStorage.setItem("cui_list", JSON.stringify(the_cui_list));    
                        sessionStorage.setItem("cui_list_full", JSON.stringify(the_cui_list_full));  
                        $("#cui_list_input").val(sessionStorage.getItem("cui_list_full"));

                    });
                }
            });
        });
    });


