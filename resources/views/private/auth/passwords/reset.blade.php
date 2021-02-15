@extends('private.layouts.guest')

@section('content')

<div class="wrapper wrapper-login">
	<div class="container container-login animated fadeIn">
		<h3 class="text-center">{{ __('Reset Password') }}</h3>
		<form class="login-form form" method="POST" action="{{ route('password.update') }}" id="resetForm">
			@csrf
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="alert alert-warning alert-dismissible fade show text-center  @if(!$errors->any()) d-none @endif" role="alert">
			  	@if($errors->has('email')) <strong> {{ $errors->first('email') }} </strong> <br> @endif
			  	@if($errors->has('password')) <strong> {{ $errors->first('password') }} </strong> @endif
			</div>

			<div class="form-group form-floating-label">
			<input id="resetEmail" name="email" maxlength="{{ limit('email.max') }}" type="text" class="form-control floating-input input-border-bottom pl-1 pr-1" required value="{{ old('email') }}" autofocus maxlength="{{ limit("email.max")}}">
				<label for="email" class="placeholder">{{ __('Email Address') }}</label>
			</div>
			<div class="form-group form-floating-label">
				<input id="password" name="password" maxlength="{{ limit('password.max') }}" type="password" class="form-control floating-input input-border-bottom pl-1 pr-5" required maxlength="{{ limit("password.max")}}">
				<label for="password" class="placeholder">{{ __('Password') }}</label>
				<div class="show-password">
					<i class="far fa-eye-slash"></i>
				</div>
			</div>
			<div class="form-group form-floating-label">
				<input id="password-confirm" maxlength="{{ limit('password.max') }}" name="password_confirmation" type="password" class="form-control floating-input input-border-bottom pl-1 pr-5" required maxlength="{{ limit("password.max")}}">
				<label for="password-confirm" class="placeholder">{{ __('Confirm Password') }}</label>
				<div class="show-password">
					<i class="far fa-eye-slash"></i>
				</div>
			</div>
			<div class="form-action mb-3">
				<button type="submit" class="btn btn-primary btn-rounded btn-login" data-loading-text="Checking.." data-loading="" data-text="" id="signin">{{ __('Reset Password') }}</button>
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


        /**Reset Password Form Validation**/
        $("#resetForm").validate({
            rules: {
                email:  {
                            required: true,
                            email	: true,
							minlength: {{ limit("email.min") }},
							maxlength: {{ limit("email.max") }}
                        },
                password:{
                            required: true,
                            validPassword: true,
							minlength: {{ limit("password.min") }},
							maxlength: {{ limit("password.max") }}
                        },
                password_confirmation:{
                            required: true,
                            equalTo: "#password",
							minlength: {{ limit("password.min") }},
							maxlength: {{ limit("password.max") }}
                        }
            },
            messages: {
            	password_confirmation: {
            		equalTo: "{{ __("validation.confirmed", ["attribute" => "password"]) }}"
            	}
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
                form.submit();
                loadButton('#signin');
            }
            
           
        }); 


    });
</script>
@endpush