@extends("private.layouts.app")

@section('content')
<div class="page-inner">
	{{-- <div class="page-header">
		<h4 class="page-title">{{""}}</h4>
		<ul class="breadcrumbs">
			<li class="nav-home">
				<a href="#">
					<i class="flaticon-home"></i>
				</a>
			</li>
			<li class="separator">
				<i class="flaticon-right-arrow"></i>
			</li>
			<li class="nav-item">
				<a href="{{ route('private.dashboard') }}">Dashboard</a>
			</li>
			<li class="separator">
				<i class="flaticon-right-arrow"></i>
			</li>
			<li class="nav-item">
				<a href="#">{{ "" }}</a>
			</li>
		</ul>
	</div> --}}
	<div class="row">
		<div class="col-md-12">
			<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
				<div>
					<h2 class=" pb-2 fw-bold">Dashboard</h2>
					{{-- <h5 class=" op-7 mb-2">Premium Bootstrap 4 Admin Dashboard</h5> --}}
				</div>
			</div>
			@if($club == 1)
			<div class="row row-card-no-pd mt--2">
				
				
				<div class="col-sm-6 col-md-6">
					<div class="card card-stats card-round">
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-calendar-alt text-primary"></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<p class="card-category">Events</p>
										<h4 class="card-title">{{ $count["event"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="card card-stats card-round">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-users text-warning"></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<p class="card-category">Participants</p>
										<h4 class="card-title">{{ $count["participant"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@else
			<div class="row row-card-no-pd mt--2">
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-round">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-users text-warning"></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<a href="{{ route('private.users') }}" class=""><p class="card-category">Users</p></a>
										<h4 class="card-title">{{ $count["user"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-round">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-user-tie text-success"></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<a href="{{ route('private.club') }}"><p class="card-category">Club Managers</p></a>
										<h4 class="card-title">{{ $count["club"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-round">
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-user-shield text-danger"></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<a href="{{ route('private.employees') }}"><p class="card-category">Admin Users</p></a>
										<h4 class="card-title">{{ $count["manager"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-round">
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="fas fa-calendar-alt text-primary "></i>
									</div>
								</div>
								<div class="col-7 col-stats">
									<div class="numbers">
										<a href="{{ route('private.events') }}"><p class="card-category">Events</p></a>
										<h4 class="card-title">{{ $count["event"] }}</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
			
		</div>


	</div>
</div>

	
@endsection
@push("css")
<style>
.card-stats a{
text-decoration: none;
}
</style>
@endpush
@push("js")
<script type="text/javascript">
	// /notifySuccess("hi");
</script>
@endpush