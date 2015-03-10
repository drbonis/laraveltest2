<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Autocomplete - Default functionality</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  
 
</head>
<body>

    <div>
        <div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title">[{{$c->cui}}] {{$c->str}}</h3></div>
  <div class="panel-body">

      @foreach($c->terms as $term)
        <span>{{$term->str}}</span>, 
      @endforeach
    
  </div>
</div>
        <p></p>
        <ul class="list-group" id='ascendants_container'>
        
        @foreach($c->ascendants as $ascendant_path)
            <li class='list-group-item'>
            @foreach($ascendant_path as $ascendant)
                @if($ascendant->cui != '')
                
                >>> <a href='/concept/show/{{$ascendant->cui}}'>{{$ascendant->str}}</a>
                
                @endif
            @endforeach
            </li>
        @endforeach
        </ul>
        <ul class="list-group" id='descendants_container'>
            @foreach($c->children as $child)
            <li class="list-group-item"><a href='/concept/show/{{$child->cui}}'>{{$child->str}}</a></li>
            @endforeach
        </ul>
    </div>
   
 
 
</body>
</html>

