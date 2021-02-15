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
				<a href="{{ route('private.events') }}">Event List</a>
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
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{ $name }}</h4>
					</div>
				</div>
				<div class="card-body">
					<input type="hidden" class="form-control bg-white" id="event_code" value="{{$code}}">
					{{-- <div class="row p-2 filter-cont mb-4">
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Created Date</label>
								<input type="text" class="form-control bg-white" readonly id="date-filter">
								
							</div>
						</div>
					</div> --}}
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Name</th>
									<th>Booked On</th>
									{{-- <th>Action</th> --}}
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


@endsection

@push("js")

	<script type="text/javascript">
		var delete_url = "{{ route('private.employee.destroy') }}";
		var table;
		var startDate = moment().startOf('month').format('YYYY-MM-DD');
		var endDate = moment().format('YYYY-MM-DD');
		$(document).ready(function() {
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "users"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.event.getbookinglist") }}",
	                "type": "POST",
	                data: function (d) { 
	                	d.event_code = $("#event_code").val()
	     				//d.start_date = startDate
						// d.end_date = endDate       
	                },
	            },
	            
	            "columns": [
	                     { "data": "name" },
	                     { "data": "booked_at",
	                    	"render": function ( data, type, row ) {
	                    		return moment(row.pivot.created_at).format(dateTimeFormat);
	                    	},
	                    	"name": "booked_at" 
	                    },
	                    // {
	                    // 	"render": function ( data, type, row ) {
	                    // 		return "";
	                    // 	}
	                    // },
	                ],

	               
	            "columnDefs": [
		            {  // set default column settings
		                'orderable': false,
		                'targets': [ -1]
		            }, 
		            {
		                "searchable": false,
		                "targets": [ -1]
		            }
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
				startDate: moment().startOf('month'),
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

@push("css")
	<style type="text/css">
	    #map {
	        margin-top: 30px;
	      width:100%;
	      height: 250px;
	    }
	    .controls {
	      margin-top: 10px;
	      border: 1px solid transparent;
	      border-radius: 2px 0 0 2px;
	      box-sizing: border-box;
	      -moz-box-sizing: border-box;
	      height: 32px;
	      outline: none;
	      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	    }
	    #searchInput {
	      background-color: #fff;
	      font-family: Roboto;
	      font-size: 15px;
	      font-weight: 300;
	      margin-left: 12px;
	      padding: 0 11px 0 13px;
	      text-overflow: ellipsis;
	      width: 50%;
	    }
	    #searchInput:focus {
	      border-color: #4d90fe;
	    }
	    ul#geoData {
	        text-align: left;
	        font-weight: bold;
	        margin-top: 10px;
	    }
	    ul#geoData span {
	        font-weight: normal;
	    }
	    .pac-container{
	    	display: block;
	    	z-index: 1552;
	    }
	    .modal .form-group .select2-container--bootstrap.select2-container--focus .select2-selection, .modal .form-group .select2-container--bootstrap .select2-selection{
	    	border: none;
	    }
	    .modal .form-group .select2-container--bootstrap .select2-selection--single{
	    	height: 31px;
	    	padding: .4rem .5rem;
	    }
	</style>
@endpush