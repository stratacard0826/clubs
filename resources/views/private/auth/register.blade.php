@extends('private.layouts.guest')

@section('content')

<div class="wrapper wrapper-login">
    <div class="container container-login animated fadeIn">
        <h3 class="text-center">{{ __('Club Registration') }}</h3>
        <form class="login-form form" method="POST"  id="registerForm">
            @csrf
            
            <div class="form-group form-floating-label">
                <input id="name" name="name" type="text" class="form-control floating-input input-border-bottom pl-1 pr-1" required value="" autofocus maxlength="{{ limit("name.max")}}">
                <label for="email" class="placeholder">Name</label>
            </div>
            <div class="form-group form-floating-label">
                <input id="email" name="email" type="text" class="form-control floating-input input-border-bottom pl-1 pr-1" required value="{{ old('email') }}" autofocus maxlength="{{ limit("email.max")}}">
                <label for="email" class="placeholder">Email</label>
            </div>
            <div class="form-group form-floating-label">
                <input id="phone" name="phone" type="text" class="form-control floating-input input-border-bottom pl-1 pr-1 digit-only" required value="" autofocus maxlength="{{ limit("phone.max")}}">
                <label for="email" class="placeholder">Phone</label>
            </div>
            <div class="form-group form-floating-label">
                <input id="password" name="password" type="password" class="form-control floating-input input-border-bottom pl-1 pr-5" required maxlength="{{ limit("password.max")}}">
                <label for="password" class="placeholder">Password</label>
                <div class="show-password">
                    <i class="far fa-eye-slash"></i>
                </div>
            </div>
            
            <div class="form-action mb-3">
                <button type="submit" class="btn btn-primary btn-rounded btn-login" data-loading-text="Register..." data-loading="" data-text="" id="register">Register</button>
            </div>
            <div class="login-account">
                <span class="msg">&copy; {{ date("Y") }} Funclub</span>
                
            </div>
        </form>
    </div>
    
</div>

@endsection


@push('js')

<script>
    $(document).ready(function(){


        /**Register Form Validation**/
        $("#registerForm").validate({
            rules: {

                name:  {
                            required: true,
                            minlength: {{ limit("name.min") }},
                            maxlength: {{ limit("name.max") }},
                            validName: true
                        },
                email:{
                            required: true,
                            minlength: {{ limit("email.min") }},
                            maxlength: {{ limit("email.max") }}
                        },
                phone:{
                            required: true,
                            digits: true,
                            minlength: {{ limit("phone.min") }},
                            maxlength: {{ limit("phone.max") }}
                        },
                password:{
                            required: true,
                            validPassword: true,
                            minlength: {{ limit("password.min") }},
                            maxlength: {{ limit("password.max") }}
                        },
            },
            errorPlacement: function(error, element) {
                if(element.hasClass("select2-hidden-accessible")){
                    error.insertAfter(element.siblings('span.select2'));
                }if(element.hasClass("floating-input")){
                    element.closest('.form-floating-label').addClass("error-cont").append(error);
                    //error.insertAfter();
                }else{
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                loadButton('#register');
                $(form).find(".alert").addClass("d-none");
                var data = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('register') }}",
                    data: data,
                    dataType: "json",
                    success: function(data) {
                        loadButton("#register");
                        if(data.success == 1){
                            notifySuccess(data.message);
                            form.reset();
                        }else{
                            notifyWarning(data.message);
                            var errors = data.errors;
                            console.log(errors);
                            if(_.size(errors) > 0){
                                $.each(errors, function(index, error){
                                    $(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" );
                                });
                            }

                        }
                    }
                }); 
            }
            
           
        }); 


    });
</script>
@endpush{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
