<!doctype html>
<html>
    <head>
        <title>Look at me Login</title>
    </head>
    <body>

        <h1>Este es el profile</h1>
        <div>
            Usuario: {{$user}}
        </div>
        <a href="/exam/list">Hacer un examen</a>
        <a href="/logout">Logout</a>
        <div>
            {{var_dump($results)}}
        </div>
        <div>
            {{var_dump($results_concept)}}
        </div>
    </body>
</html>

