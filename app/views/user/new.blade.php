<!doctype html>
<html>
    <head>
        <title>Nuevo usuario</title>
    </head>
    <body>

        {{Form::open(array('url'=>'new'))}}
        <h1>Alta de usuario</h1>
        
        <p>
            {{$errors->first('email') }}
            {{$errors->first('password')}}
        </p>
        
        <p>
            {{ Form::label('email', 'Email Address') }}
            {{ Form::text('email', Input::old('email'), array('placeholder'=>'my@email.com'))}}
        </p>
        
        <p>
            {{ Form::label('password','Password')}}
            {{ Form::password('password')}}
        </p>
        
        <p>
            {{ Form::submit('Crear cuenta!') }}</p>
        </p>
        {{ Form::close() }}
        
        <a href="/login">Ya tengo cuenta</a>
    </body>
</html>

