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
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{ $name }}</h4>
						<input type="hidden" id="clubcode" name="clubcode" value="{{$code}}">
						<button class="btn btn-primary btn-round ml-auto" id="addModalOpen">
							<i class="fa fa-plus"></i> &nbsp;
							Add Event
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Event Type</label>
								<select class="select-filter form-control" data-placeholder="Select a Event" id="event-type-filter">
	                              	<option value="">All</option>
	                              	@foreach($eventTypes as $eventType)
		                              	<option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
	                              	@endforeach

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
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Event</th>
									<th>Type</th>
									<th>Event On</th>
									<th>Status</th>
									<th>Event Status</th>
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
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h2 class="modal-title">
					New Event
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addForm" autocomplete="off">
				<div class="modal-body">
						<input name="lat" type="hidden"  class="form-control latitude">
						<input name="long" type="hidden"  class="form-control longitude">
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group form-group-default">
											<label>Event Name</label>
											<input id="addName" type="text" name="name" maxlength="{{ limit("event_name.max")}}" class="form-control" placeholder="Event Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Event Date</label>
											<input id="addDate" type="text" name="event_date" class="form-control date-time-filter" placeholder="Select a date">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Event Type</label>
											<select class="select-filter form-control" name="event_type" data-placeholder="Select a type" id="addType">
				                              	<option value="">Select a type</option>
				                              	@forelse($eventTypes as $eventType)
					                              	<option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
				                              	@empty
				                              	@endforelse

											</select>
										</div>
									</div>
									<div class="col-sm-12"> 
								        <div class="form-group form-group-default">
											<label>Event Details</label>
											<textarea id="addDetail" name="detail" rows="4" maxlength="{{ limit("event_detail.max") }}"  class="form-control " placeholder="Details"></textarea> 
										</div>
									</div>
									{{-- <div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Start Time</label>
											<input id="addStartTime" type="text"  name="start_time"  class="form-control" placeholder="Start Time">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>End Time</label>
											<input id="addEndTime" name="end_time"  class="form-control" placeholder="End Time">
										</div>
									</div> --}}
								</div>
							</div>
							<div class="col-sm-12">
						        <div class="div-center map-cont">
						         	<input id="searchInput" class="controls map-section" name="location" maxlength="{{ limit("event_location.max") }}" type="text" placeholder="Enter a location">
						         	<div id="map"></div>
						        </div>
							</div>
							
						</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" data-loading-text="Creating.." id="addRowButton" class="btn btn-primary">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h2 class="modal-title">
					Edit Event
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm">
				<div class="modal-body">
						<input name="lat" type="hidden"  class="form-control latitude" id="editLat">
						<input name="long" type="hidden"  class="form-control longitude" id="editLong">
						<input name="data" type="hidden"  class="form-control longitude" id="editData">
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group form-group-default">
											<label>Event Name</label>
											<input id="editName" type="text" name="name" maxlength="{{ limit("event_name.max")}}" class="form-control" placeholder="Event Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Event Date</label>
											<input id="editDate" type="text" name="event_date" class="form-control date-time-filter" placeholder="Select a date">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Event Type</label>
											<select class="select-filter form-control" name="event_type" data-placeholder="Select a type" id="editType">
				                              	<option value="">Select a type</option>
				                              	@forelse($eventTypes as $eventType)
					                              	<option value="{{ $eventType->id }}">{{ $eventType->name }}</option>
				                              	@empty
				                              	@endforelse

											</select>
										</div>
									</div>
									<div class="col-sm-12"> 
								        <div class="form-group form-group-default">
											<label>Event Details</label>
											<textarea id="editDetail" name="detail" rows="4" maxlength="{{ limit("event_detail.max") }}"  class="form-control " placeholder="Details"></textarea> 
										</div>
									</div>
									{{-- <div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Start Time</label>
											<input id="addStartTime" type="text"  name="start_time"  class="form-control" placeholder="Start Time">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>End Time</label>
											<input id="addEndTime" name="end_time"  class="form-control" placeholder="End Time">
										</div>
									</div> --}}
								</div>
							</div>
							<div class="col-sm-12">
						        <div class="div-center map-cont">
						         	<input id="editSearchInput" class="controls map-section eventLocation" name="location" maxlength="{{ limit("event_location.max") }}" type="text" placeholder="Enter a location">
						         	<div id="editMap"></div>
						        </div>
							</div>
							
						</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" data-loading-text="Updating.." id="editRowButton" class="btn btn-primary">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>


@endsection

@push("js")

	<script type="text/javascript">
		var delete_url = "{{ route('private.event.destroy') }}";
		var table;
		var startDate, endDate, startTime, endTime;
		var addValidator = "";
		var datePickerOption = {opens: 'left',
				timePicker: true,
				minDate: moment(),
				startDate: moment(),
				locale: {
					format: '{{ config("site.date_time_format.front") }}'
				}
			};
		var datePickerEvent = function(start, end, label) {
				startDate = start.format('YYYY-MM-DD HH:mm:ss');
				startTime = start.format('HH:mm:ss');
				endDate =  end.format('YYYY-MM-DD HH:mm:ss');
				endTime = start.format('HH:mm:ss');

			};
		
		$(document).ready(function() {
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "events"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.club.geteventlist") }}",
	                "type": "POST",
	                data: function (d) { 
	                	d.event_type = $("#event-type-filter").val()     
	                	d.code = $("#clubcode").val()     
	                	d.status = $("#status-filter").val()     
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    { "data": "event_type.name" },
	                    { "data": "start_date",
	                    	"render": function ( data, type, row ) {
	                    		return moment(row.start_date).format(dateTimeFormat);
	                    	},
	                    	"name": "start_date" 
	                    },
	                    //{ mRender:  function ( data, type, row ){ return row.tel_code+" "+row.phone} },
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
						{ "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge event_cancell"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> 
	                        					<a href="javascript:void(0);" data-toggle="tooltip" title="Edit Event" class="btn btn-link btn-primary btn-lg" data-id="${row.id}" data-original-title="Edit Event" onClick="Edit(this)"> 
	                        						<i class="fa fa-edit"></i> 
	                        					</a> 
	                        					<a href="{{route('private.event.bookinglist',"")}}/${row.code}" data-toggle="tooltip" title="Club User" class="btn btn-link btn-primary btn-lg" data-id="${row.code}" data-original-title="Club User" target="_blank"> 
	                        						<i class="fa fa-list"></i> 
	                        					</a>  	      
	                        					<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> 
	                        						<i class="fa fa-times"></i> 
	                        					</button>
	                        				</div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.event.status') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
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

	                               $(row).find( '.event_cancell' ).editable({
	                                    url: "{{ route('private.event.cancelled') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
	                                    },

	                                    inputclass: 'form-control',
	                                    source: [{
	                                        value: 1,
	                                        text: 'Active'
	                                    }, {
	                                        value: 0,
	                                        text: 'Cancelled'
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
					[2, "desc"]
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

			$("#addModalOpen").click(function(){
				$("#addModal").find("#addForm")[0].reset();
				$("#addModal").find("#addName").focus();
				if(addValidator){
					addValidator.resetForm();
				}
				$("#addModal").modal({keyboard: false, static: false});
			});


			/**Create Event Form Validation**/
			addValidator = $("#addForm").validate({
			    rules: {
			    	name:  {
			                    required: true,
			                    minlength	: {{ limit("event_name.min") }},
			                    maxlength	: {{ limit("event_name.max") }}
			                },
	                event_date:  {
			                    required: true,
			                },
			        event_type:  {
			                    required: true,
			                },
	                detail:  {
			                    required: true,
			                    minlength	: {{ limit("event_detail.min") }},
			                    maxlength	: {{ limit("event_detail.max") }}
			                },
			        location:{
			                    required: true,
			                    minlength	: {{ limit("event_location.min") }},
			                    maxlength	: {{ limit("event_location.max") }}
			                }
			    },
			    errorPlacement: function(error, element) {
			        if(element.hasClass("select2-hidden-accessible")){
			        	error.insertAfter(element.siblings('span.select2'));
			        }else if(element.hasClass("floating-input")){
			        	element.closest('.form-floating-label').addClass("error-cont").append(error);
			        	//error.insertAfter();
			        }else if(element.hasClass("inline-control")){
	                	element.closest('.form-group').addClass("error-cont").append(error);
	                	//error.insertAfter();
                	}else if(element.hasClass("map-section")){
	                	element.closest('.map-cont').append(error);
	                	//error.insertAfter();
                	}else{
			        	error.insertAfter(element);
			        }
			    },
			    submitHandler: function(form) {
			        loadButton('#addRowButton');
			        var data = $(form).serialize();
			        if(startDate && endDate && startTime && endTime){
			        	data += "&start_date="+startDate+"&end_date="+endDate+"&start_time="+startTime+"&end_time="+endTime;
			        	$.ajax({
    						type: "POST",
    						url: "{{ route("private.event.create") }}",
    						data: data,
    						dataType: "json",
    						success: function(data) {
    							loadButton('#addRowButton');
    							if(data.success == 1){
    								form.reset();
    								$("#addModal").modal("toggle");
    								notifySuccess(data.message);
    								table.fnDraw();
    							}
    							else
    							{
    								notifyWarning(data.message);
    								var errors = data.errors;
    	                            if(_.size(errors) > 0){
    	                                $.each(errors, function(index, error){
    	                                	if(index == "location"){
    	                                		$(form).find( ".map-section" ).append( "<label class='error'>"+error+"</label>" ); 
    	                                	}else{
		                                        $(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" ); 
    	                                	}
    	                                });
    	                            }
    							}
    						}
    					});

			        }else{
			        	notifyWarning("Kindly check all the fields");
			        }
			    }
			}); 

			/**Edit Event Form Validation**/
			editValidator = $("#editForm").validate({
			    rules: {
			    	name:  {
			                    required: true,
			                    minlength	: {{ limit("event_name.min") }},
			                    maxlength	: {{ limit("event_name.max") }}
			                },
	                event_date:  {
			                    required: true,
			                },
			        event_type:  {
			                    required: true,
			                },
	                detail:  {
			                    required: true,
			                    minlength	: {{ limit("event_detail.min") }},
			                    maxlength	: {{ limit("event_detail.max") }}
			                },
			        location:{
			                    required: true,
			                    minlength	: {{ limit("event_location.min") }},
			                    maxlength	: {{ limit("event_location.max") }}
			                }
			    },
			    errorPlacement: function(error, element) {
			        if(element.hasClass("select2-hidden-accessible")){
			        	error.insertAfter(element.siblings('span.select2'));
			        }else if(element.hasClass("floating-input")){
			        	element.closest('.form-floating-label').addClass("error-cont").append(error);
			        	//error.insertAfter();
			        }else if(element.hasClass("inline-control")){
	                	element.closest('.form-group').addClass("error-cont").append(error);
	                	//error.insertAfter();
                	}else if(element.hasClass("map-section")){
	                	element.closest('.map-cont').append(error);
	                	//error.insertAfter();
                	}else{
			        	error.insertAfter(element);
			        }
			    },
			    submitHandler: function(form) {
			        loadButton('#editRowButton');
			        var data = $(form).serialize();
			        if(startDate && endDate && startTime && endTime){
			        	data += "&start_date="+startDate+"&end_date="+endDate+"&start_time="+startTime+"&end_time="+endTime;
			        	$.ajax({
    						type: "POST",
    						url: "{{ route("private.event.update") }}",
    						data: data,
    						dataType: "json",
    						success: function(data) {
    							loadButton('#editRowButton');
    							if(data.success == 1){
    								form.reset();
    								$("#editModal").modal("toggle");
    								notifySuccess(data.message);
    								table.fnDraw();
    							}
    							else
    							{
    								notifyWarning(data.message);
    								var errors = data.errors;
    	                            if(_.size(errors) > 0){
    	                                $.each(errors, function(index, error){
    	                                	if(index == "location"){
    	                                		$(form).find( ".map-section" ).append( "<label class='error'>"+error+"</label>" ); 
    	                                	}else{
		                                        $(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" ); 
    	                                	}
    	                                });
    	                            }
    							}
    						}
    					});

			        }else{
			        	notifyWarning("Kindly check all the fields");
			        }
			    }
			}); 

			//DateRange Picker
			$(".date-time-filter").daterangepicker(datePickerOption , datePickerEvent);
	    });
	
		//EDIT USER GET DATA
	    function Edit(elem){
	        var dataId = $(elem).data("id");
	        $.ajax({
                type: "POST",
                url: "{{route('private.event.edit')}}",
                data: {eventId:dataId},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
                		var record = data.record;
                        $('#editData').val(record.id);
                        $('#editName').val(record.name);
                        $('#editDetail').val(record.detail);
                        //$('#editDate').val(record.phone);
                        $('#editType').val(record.event_type).trigger("change");
                        $('#editLat').val(record.lat);
                        $('#editLong').val(record.lng);
                        $('#editSearchInput').val(record.location);
                        $('#editModal').modal();
                        var latLng = new google.maps.LatLng(record.lat,record.lng);
                        editMap.map.setCenter(latLng);
                        editMap.marker.setPosition(latLng);
                        editMap.infowindow.setContent('<div>' + record.location+ '</div>');
                        editMap.infowindow.open(editMap.map, editMap.marker);
                        datePickerOption.startDate = moment(record.start_date);
                        datePickerOption.endDate = moment(record.end_date);
                        if(moment().diff(datePickerOption.startDate) > 0){
                        	lg("hi");
	                        datePickerOption.minDate = moment(record.start_date);
                        }
                        startDate = datePickerOption.startDate.format('YYYY-MM-DD HH:mm:ss');
						startTime = datePickerOption.startDate.format('HH:mm:ss');
						endDate =  datePickerOption.endDate.format('YYYY-MM-DD HH:mm:ss');
						endTime = datePickerOption.endDate.format('HH:mm:ss');
                        $("#editModal .date-time-filter").daterangepicker(datePickerOption, datePickerEvent);
                    }
                    else
                    {
                    	notifyWarning(data.message);
                	}
            	}
	        }); 
	    }
		/*
	    $(document).ready(function() {
	    	$('.cancel-data').on('click',function() {

	    		alert('hai');
	    	});

	    });*/

	    /*function Cancell(elem)
	    {
	    	var dataCode=$(elem).data("code");
	    	$.ajax({
                type: "POST",
                url: "{{route('private.event.cancelled')}}",
                data: {eventCode:dataCode},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
						notifySuccess(data.message);       
    				}else
                    {
                    	notifyWarning(data.message);
                	}
                }
            });
	    }*/


	</script>

	<script type="text/javascript">
		// This example requires the Places library. Include the libraries=places
		// parameter when you first load the API. For example:
		// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
		var addMap = {map: "", marker: "", infowindow: ""}, editMap= {map: "", marker: "", infowindow: ""};
		function initMap() {
		    
		    mapInitiator("map", "searchInput", addMap);
		    mapInitiator("editMap", "editSearchInput", editMap);
		}

		function mapInitiator(elementId, searchId, mapObj){
			mapObj.map = new google.maps.Map(document.getElementById(elementId), {
		        center: {lat: 18.641400, lng: 72.872200},
		        zoom: 10
		    });

		    var input = document.getElementById(searchId);

		    mapObj.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

		    var autocomplete = new google.maps.places.Autocomplete(input);

		    autocomplete.bindTo('bounds', mapObj.map);

		    mapObj.infowindow = new google.maps.InfoWindow();

		    var geocoder = new google.maps.Geocoder();

		    mapObj.marker = new google.maps.Marker({
		        map: mapObj.map,
		        anchorPoint: new google.maps.Point(0, -29),
		        draggable: true,
		    });
		   
		    (function (marker) {


		        google.maps.event.addListener(mapObj.marker, "dragend", function () {
		            var lat, lng, address;
		            geocoder.geocode({ 'latLng': mapObj.marker.getPosition() }, function (results, status) {
		                if (status == google.maps.GeocoderStatus.OK) {
		                    lat = mapObj.marker.getPosition().lat();
		                    lng = mapObj.marker.getPosition().lng();
		                    address = results[0].formatted_address;
		                    console.table(results[0].geometry);
		                    mapObj.infowindow.setContent('<div>' + address+ '</div>');
		                    $(".latitude").val(lat);
		                    $(".longitude").val(lng);
		                    input.value = address;
		                }
		            });
		        });

		    })(mapObj.marker);


		    autocomplete.addListener('place_changed', function() {
		        mapObj.infowindow.close();

		        mapObj.marker.setVisible(false);

		        var place = autocomplete.getPlace();
		        if (!place.geometry) {
		            window.alert("Autocomplete's returned place contains no geometry");
		            return;
		        }
		  
		        // If the place has a geometry, then present it on a map.
		        if (place.geometry.viewport) {
		            mapObj.map.fitBounds(place.geometry.viewport);
		        } else {
		            mapObj.map.setCenter(place.geometry.location);
		            mapObj.map.setZoom(17);
		        }

		        mapObj.marker.setIcon(({
		            //url: place.icon,
		            size: new google.maps.Size(71, 71),
		            origin: new google.maps.Point(0, 0),
		            anchor: new google.maps.Point(17, 34),
		            scaledSize: new google.maps.Size(35, 35)
		        }));

		        mapObj.marker.setPosition(place.geometry.location);
		        mapObj.marker.setVisible(true);
		    
		        var address = '';
		        if (place.address_components) {
		            address = [
		              (place.address_components[0] && place.address_components[0].short_name || ''),
		              (place.address_components[1] && place.address_components[1].short_name || ''),
		              (place.address_components[2] && place.address_components[2].short_name || '')
		            ].join(' ');
		        }
		    
		        mapObj.infowindow.setContent('<div>' + address+ '</div>');
		        mapObj.infowindow.open(mapObj.map, mapObj.marker);
		      
		        //Location details
		        for (var i = 0; i < place.address_components.length; i++) {
		            if(place.address_components[i].types[0] == 'postal_code'){
		                //document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
		            }
		            if(place.address_components[i].types[0] == 'country'){
		                //document.getElementById('country').innerHTML = place.address_components[i].long_name;
		            }
		        }
		        $(".latitude").val(place.geometry.location.lat());
                $(".longitude").val(place.geometry.location.lng());
		        
		    });
		}
	</script>

  	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCLykxO8HxQ2Z6ivzMpcYVLkOChPYdNuRc&callback=initMap" async defer></script>
@endpush

@push("css")
	<style type="text/css">
	    #map, #editMap {
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
	    #searchInput, #editSearchInput {
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