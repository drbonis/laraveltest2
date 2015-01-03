<!doctype html>
<html>
    <head>
        <title>Look at me Login</title>
    </head>
    <body>

        <h1>Este es el profile</h1>
        <div>
            Usuario: {{$user}}
            Examen: 

            @foreach($questions as $question)
                <p>{{$question->question}}</p>
                <ol>
                    <li>{{$question->option1}}</li>
                    <li>{{$question->option2}}</li>
                    <li>{{$question->option3}}</li>
                    <li>{{$question->option4}}</li>
                    <li>{{$question->option5}}</li>
                </ol>
            @endforeach
        </div>
        
        <a href="../logout">Logout</a>
    </body>
</html>

