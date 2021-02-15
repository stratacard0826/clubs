@extends('private.layouts.guest')

@section('content')

<div class="wrapper wrapper-login">
	<div class="container container-login animated fadeIn">
		<h3 class="text-center">Sign In</h3>
		<form class="login-form form" method="POST" action="{{ route('login') }}" id="loginForm">
			@csrf
			

			<div class="alert alert-warning alert-dismissible fade show text-center  @if(!$errors->any()) d-none @endif" role="alert">
			  <strong> {{__("site.invalid_login") }} </strong>
			</div>

			<div class="form-group form-floating-label">
				<input id="loginEmail" name="email" type="text" class="form-control floating-input input-border-bottom pl-1 pr-1" required value="{{ old('email') }}" autofocus maxlength="{{ limit("email.max")}}">
				<label for="email" class="placeholder">Email</label>
			</div>
			<div class="form-group form-floating-label">
				<input id="password" name="password" type="password" class="form-control floating-input input-border-bottom pl-1 pr-5" required maxlength="{{ limit("password.max")}}">
				<label for="password" class="placeholder">Password</label>
				<div class="show-password">
					<i class="far fa-eye-slash"></i>
				</div>
			</div>
			<div class="row form-sub m-0">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" name="remember" class="custom-control-input" id="rememberme" {{ old('remember') ? 'checked' : '' }}>
					<label class="custom-control-label" for="rememberme">Remember Me</label>
				</div>
				
				<a href="#" id="show-forgot"  class="link float-right">Forget Password ?</a>
			</div>
			<div class="form-action mb-3">
				<button type="submit" class="btn btn-primary btn-rounded btn-login" data-loading-text="Signing In.." data-loading="" data-text="" id="signin">Sign In</button>
			</div>
			<div class="login-account">
				<span class="msg">&copy; {{ date("Y") }} Funclub</span>
				
			</div>
		</form>
	</div>

	<div class="container container-forgot animated fadeIn d-none">
		<h3 class="text-center">Forgot Password</h3>
		<form class="login-form form" id="forgotPasswordForm" >
			<div class="alert alert-warning alert-dismissible fade show text-center d-none" role="alert">
			  <strong></strong>
			</div>
			<div class="form-group form-floating-label">
				<input  id="forgotEmail" name="email" type="email" class="form-control input-border-bottom floating-input pl-1 pr-1" required maxlength="{{ limit("email.max")}}">
				<label for="email" class="placeholder">Email</label>
			</div>
			<div class="form-action">
				<a href="#" id="show-signin" class="btn btn-danger btn-link btn-login mr-3">Sign In</a>
				<button type="submit" class="btn btn-primary btn-rounded btn-login" data-loading-text="Checking.." data-loading="" data-text="" id="forgotButton">Send Reset Link</button>
			</div>
			<div class="login-account mt-5">
				<span class="msg">&copy; {{ date("Y") }} Funclub</span>
				
			</div>
		</form>
	</div>
</div>

@endsection


@push('js')

<script>
    $(document).ready(function(){


        /**Login Form Validation**/
        $("#loginForm").validate({
            rules: {
                email:  {
                            required: true,
                            email	: true
                        },
                password:{
                            required: true,
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


        /**forgot password Form Validation**/
        $("#forgotPasswordForm").validate({
            rules: {
                email:  {
                            required: true,
                            email	: true
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
                loadButton('#forgotButton');
                $(form).find(".alert").addClass("d-none");
                var data = $(form).serialize();
	            $.ajax({
                   	type: "POST",
                   	url: "{{ route('password.email') }}",
                   	data: data,
                   	dataType: "json",
                   	success: function(data) {
                   		loadButton("#forgotButton");
                        $(form).find(".alert").removeClass("d-none").find("strong").html(data.message);
                        if(data.success == 1){
                            notifySuccess(data.message);
                            $(form).find(".alert").removeClass("alert-warning").addClass("alert-success");
                            form.reset();
                        }else{
                            notifyWarning(data.message);
                            $(form).find(".alert").removeClass("alert-success").addClass("alert-warning");
                        }
                    }
	            }); 
            }
            
           
        }); 


    });
</script>
@endpush