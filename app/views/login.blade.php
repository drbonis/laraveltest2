<!doctype html>
<html>
    <head>
        <title>Look at me Login</title>
    </head>
    <body>
        <div id="var_dump">
            {{var_dump($vardump)}}
        </div>
        {{Form::open(array('url'=>'login'))}}
        <h1>Login</h1>
        
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
            {{ Form::submit('Submit!') }}</p>
        </p>
        {{ Form::close() }}
    </body>
</html>

