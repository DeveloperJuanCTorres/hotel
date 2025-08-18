@extends('layouts.app')
<link rel="stylesheet" href="assets/css/login.css" />
@section('content')

<div class="wrapper">
  <form method="POST" action="{{ route('login') }}" class="login">
    @csrf
    <p class="title">Iniciar Sesión</p>
    <input type="email" name="email" placeholder="Usuario" autofocus required/>
    <i class="fa fa-user"></i>
    <input type="password" name="password" placeholder="Password" />
    <i class="fa fa-key"></i>
    <a href="#">Forgot your password?</a>
    <button type="submit">
      <i class="spinner"></i>
      <span class="state">Login</span>
    </button>
  </form>
  <footer><a target="blank" href="http://vesergenperu.com/">Grupo VesergenPerú</a></footer>
  </p>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    var working = false;
    $('.login').on('submit', function(e) {
        e.preventDefault();
        if (working) return;
        working = true;
        
        var $this = $(this),
            $state = $this.find('button > .state');
        
        $this.addClass('loading');
        $state.html('Autenticando...');

        $.ajax({
            url: "{{ route('login') }}", // ruta de login de Laravel
            method: "POST",
            data: $this.serialize(), // manda email + password + csrf
            success: function(response) {
                $this.addClass('ok');
                $state.html('Bienvenido!');
                
                setTimeout(function() {
                    window.location.href = "{{ url('/') }}"; // redirige al dashboard
                }, 1500);
            },
            error: function(xhr) {
                $this.removeClass('loading');
                working = false;
                if (xhr.status === 422) {
                    $state.html('Credenciales inválidas');
                } else {
                    $state.html('Error al iniciar sesión');
                }
            }
        });
    });
</script>
@endsection
