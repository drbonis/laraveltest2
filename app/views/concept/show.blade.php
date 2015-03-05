<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  
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
                //$('#results').html(JSON.stringify(data));
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
 
<div class="ui-widget">
  <label for="tags">Términos: </label>
  <input id="tags">
</div>
    
<div id="results"></div>
 
 
</body>
</html>

