<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

<head>
<body>
  <div class="container">
      <h1>Sandbox</h1>
    <pre>{{var_dump($results)}}</pre>
    <form id="form">
        <div><textarea name="texto" id="texto" rows="10" form="form" cols="100"></textarea></div>
        <input class="btn-success" type="submit">
    </form>

    <div id="taggedText"></div>
  </div>
    
    
    <script>
        $("#form").submit(function(e){
            e.preventDefault();
            $("#taggedText").html($("#texto").val());  
        });
    </script>  
    
</body>
</html>
