@extends("private.layouts.app")

@section("content")
<div class="page-inner">
	{{-- <h4 class="page-title">User Profile</h4> --}}
	<div class="page-header">
		<h4 class="page-title">{{$title}}</h4>
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
				<a href="{{ route('private.club') }}">Club User List</a>
			</li>
			<li class="separator">
				<i class="flaticon-right-arrow"></i>
			</li>
			<li class="nav-item">
				<a href="#">{{ $title }}</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="card card-with-nav">
				<div class="card-header">
					<div class="row row-nav-line">
						<ul class="nav nav-tabs nav-line nav-color-secondary" role="tablist">
							{{-- <li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#home" role="tab" aria-selected="true">Timeline</a> </li> --}}
							<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-selected="false">Profile</a> </li>
							{{-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab" aria-selected="false">Settings</a> </li> --}}
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="row mt-3">
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Name</label>
								<p>{{ $profile->name }}</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Email</label>
								<p>{{ $profile->email }}</p>
							</div>
						</div>
					</div>
					<div class="row mt-1">
						
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Created On</label>
								<p>@date($profile->created_at)</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Phone</label>
								<p>+{{ $profile->tel_code }} {{ $profile->phone }}</p>
							</div>
						</div>
					</div>
					{{-- <div class="row mt-3">
						<div class="col-md-12">
							<div class="form-group form-group-default">
								<label>Address</label>
								<input type="text" class="form-control" value="st Merdeka Putih, Jakarta Indonesia" name="address" placeholder="Address">
							</div>
						</div>
					</div>
					<div class="row mt-3 mb-1">
						<div class="col-md-12">
							<div class="form-group form-group-default">
								<label>About Me</label>
								<textarea class="form-control" name="about" placeholder="About Me" rows="3">A man who hates loneliness</textarea>
							</div>
						</div>
					</div> --}}
					{{-- <div class="text-right mt-3 mb-3">
						<button class="btn btn-success">Save</button>
						<button class="btn btn-danger">Reset</button>
					</div>  --}}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-profile">
				<div class="card-header" style="background-image: url('{{ asset('private/assets/img/blogpost.jpg') }}')">
					<div class="profile-picture">
						<div class="avatar avatar-xl">
							<img src="{{ $profile->profile_pic }}" alt="..." class="avatar-img rounded-circle">
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="user-profile text-center">
						<div class="name">{{ $profile->name }}</div>
						<div class="job">{{ $profile->email }}</div>
						<div class="desc">+{{ $profile->tel_code }} {{ $profile->phone }}</div>
						{{-- <div class="social-media">
							<a class="btn btn-info btn-twitter btn-sm btn-link" href="#"> 
								<span class="btn-label just-icon"><i class="flaticon-twitter"></i> </span>
							</a>
							<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
								<span class="btn-label just-icon"><i class="flaticon-google-plus"></i> </span> 
							</a>
							<a class="btn btn-primary btn-sm btn-link" rel="publisher" href="#"> 
								<span class="btn-label just-icon"><i class="flaticon-facebook"></i> </span> 
							</a>
							<a class="btn btn-danger btn-sm btn-link" rel="publisher" href="#"> 
								<span class="btn-label just-icon"><i class="flaticon-dribbble"></i> </span> 
							</a>
						</div>
						<div class="view-profile">
							<a href="#" class="btn btn-secondary btn-block">View Full Profile</a>
						</div> --}}
					</div>
				</div>
				<div class="card-footer">
					<div class="row user-stats text-center">
						<div class="col">
							<div class="number">{{$countEvent}}</div>
							<div class="title">Event</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h5 class="modal-title">
					<span class="fw-mediumbold">
					New</span> 
					<span class="fw-light">
						Row
					</span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="small">Create a new row using this form, make sure you fill them all</p>
				<form>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Name</label>
								<input id="addName" type="text" class="form-control" placeholder="fill name">
							</div>
						</div>
						<div class="col-md-6 pr-0">
							<div class="form-group form-group-default">
								<label>Position</label>
								<input id="addPosition" type="text" class="form-control" placeholder="fill position">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Office</label>
								<input id="addOffice" type="text" class="form-control" placeholder="fill office">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer no-bd">
				<button type="button" id="addRowButton" class="btn btn-primary">Add</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


@endsection

@push("js")

	

	<script type="text/javascript">
		var delete_url = "{{ route('private.club.destroy') }}";
		var table;
		
		$(document).ready(function() {
			$(".select-filter").select2();
	    });
	</script>
@endpush