@extends("private.layouts.app")

@section("content")
<div class="page-inner">
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
				<a href="#">{{ $title }}</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
					<div class="card-header">
						<div class="row">
						<div class="col-md-6 col-sm-6">
						<h4 class="card-title">{{ $title }}</h4>
						</div>
						<div class="col-md-6 col-sm-6">
						 <h6 class="float-right">Total User&nbsp;(<span class="user-count"></span>)</h6>
						</div>
					</div>
				
					</div>
					
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Gender</label>
								<select class="select-filter form-control" data-placeholder="Select a Gender" id="gender-filter">
	                              	<option value="">All</option>
	                              	@forelse($genders as $gender)
		                              	<option value="{{ $gender->id }}">{{ $gender->name }}</option>
	                              	@empty
	                              	@endforelse

								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Status</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="status-filter">
	                              	<option value="">All</option>
	                              	@foreach(config("site.status") as $key => $status)
		                              	<option value="{{ $key }}">{{ $status }}</option>
	                              	@endforeach

								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Created Date</label>
								<input type="text" class="form-control bg-white" readonly id="date-filter">
							</div>
						</div>
						<div class="col-md-3 pl-0">
							<div class="form-group pt-10 float-right">
								<button class="btn btn-primary btn-border btn-round export">Exports</button>
							</div>
						</div> 
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile</th>
									<th>Gender</th>
									<th>Created On</th>
									<th>Status</th>
									<th>Block</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
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
@push("css")
<style>
.export:hover 
{
    background-color: #1572E8 !important;
    color: #FFF!important;
}
</style>
@endpush

@push("js")

	

	<script type="text/javascript">


		var delete_url = "{{ route('private.user.destroy') }}";
		var table;
		var startDate = moment().startOf('year').format('YYYY-MM-DD');
		var endDate = moment().format('YYYY-MM-DD');
		
		$(document).ready(function() {
			$.ajax({
  					url: "{{ route('private.user.count') }}",
  					type: "POST",
  					dataType: "json",
  					success: function(response){
            		 $('.user-count').html(response);
           				},
				});

			$('.export').click(function(){
			    var gender_type = $("#gender-filter").val();    
				var status = $("#status-filter").val();	    
				if(gender_type=="")
				{
					gender_type=11;
				}
				if(status=="")
				{
					status=11;
				}
					window.location.href = "{{URL('private/user/export')}}/"+gender_type+"/"+status+"/"+startDate+"/"+endDate;
			});
			
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "User"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.users.list") }}",
	                "type": "POST",
	                data: function (d) { 
	                	d.gender_type = $("#gender-filter").val()     
						d.status = $("#status-filter").val()     
						d.start_date = startDate
						d.end_date = endDate
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    { "data": "email" },
	                    { "render":  function ( data, type, row ){ return row.tel_code+" "+row.phone}, "name": "phone"  },
						{ "data": "gender_type.name" },
						{ "data": "created_at",
							"render": function ( data, type, row ) {
								return moment(row.created_at).format(dateFormat);
							},
							"name": "created_at" 
						},
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
						{ "data": "block",
	                       "render": function ( data, type, row ) {
		                       	if(!row.blocked_at){
		                       		return `<a href="javascript:;" class="badge block_edit"  data-type="select" data-pk="${row.id}" data-value="0" data-original-title="Select Status"> </a>`;
		                       	}else
		                       	{
		                       		return `<a href="javascript:;" class="badge block_edit"  data-type="select" data-pk="${row.id}" data-value="1" data-original-title="Select Status"> </a>`;
		                       	}

	                        },
	                        "name": "blocked_at"  
						},
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> <a href="{{route('private.user.profile', ['key' => ''])}}/${row.key}" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-id="${row.id}" data-original-title="View Profile" target="_blank"> <i class="fa fa-eye"></i> </a> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.user.status') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
											//table.fnStandingRedraw();
	                                    },

	                                    inputclass: 'form-control',
	                                    source: [{
	                                        value: 1,
	                                        text: 'Active'
	                                    }, {
	                                        value: 0,
	                                        text: 'Inactive'
	                                    }],
	                                    display: function(value, sourceData) {
	                                        var cls = {
	                                                1: "badge-success",
	                                                0: "badge-danger"
	                                            },
	                                            rmcls = {
	                                                1: "badge-danger",
	                                                0: "badge-success"
	                                            },
	                                            elem = $.grep(sourceData, function(o) {
	                                                return o.value == value;
	                                            });

	                                        if (elem.length) {
	                                            $(this).text(elem[0].text).attr("data-value", value).removeClass( rmcls[value]);
	                                            $(this).addClass( cls[value]);
	                                        } else {
	                                            $(this).empty();
	                                        }
	                                    }
	                                });
	                            $(row).find( '.block_edit' ).editable({
                                url: "{{ route('private.user.block') }}",
                                success: function(response, newValue) {
                                    if(response.success == 0) return response.message; //msg will be shown in editable form
									//table.fnStandingRedraw();
                                },

                                inputclass: 'form-control',
                                source: [{
                                    value: 1,
                                    text: 'Blocked'
                                }, {
                                    value: 0,
                                    text: 'Unblocked'
                                }],
                                display: function(value, sourceData) {
                                    var cls = {
                                            1: "badge-danger",
                                            0: "badge-success"
                                        },
                                        rmcls = {
                                            0: "badge-danger",
                                            1: "badge-success"
                                        },
                                        elem = $.grep(sourceData, function(o) {
                                            return o.value == value;
                                        });

                                    if (elem.length) {
                                        $(this).text(elem[0].text).attr("data-value", value).removeClass( rmcls[value]);
                                        $(this).addClass( cls[value]);
                                    } else {
                                        $(this).empty();
                                    }
                                }
                            });
	                            
	                        },
	            "columnDefs": [
		            {  // set default column settings
		                'orderable': false,
		                'targets': [ -1]
		            }, 
		            {
		                "searchable": false,
		                "targets": [ -1]
		            }
				],
				"order": [
					[4, "desc"]
				] 
	        });

			//Table Filter
			$(".select-filter").change(function(){
				table.fnDraw();
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap"
			});

			//DateRange Picker
			$("#date-filter").daterangepicker({
				opens: 'left',
				startDate: moment().startOf('year'),
				endDate: moment(),
				locale: {
					format: '{{ config("site.date_format.front") }}'
				},
				maxDate:moment(),
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'This Year': [moment().startOf('year'), moment().endOf('year')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				//autoUpdateInput: false,
			}, function(start, end, label) {
				startDate = start.format('YYYY-MM-DD');
				endDate =  end.format('YYYY-MM-DD');
				table.fnDraw();

			});
	    });
	</script>
@endpush