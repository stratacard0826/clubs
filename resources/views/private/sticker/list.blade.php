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
						<h4 class="card-title">{{ $title }}</h4>
						<button class="btn btn-primary btn-round ml-auto" id="addModalOpen">
							<i class="fa fa-plus"></i> &nbsp;
							Add Sticker
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Sticker Type</label>
								<select class="select-filter form-control" data-placeholder="Select a Sticker" id="sticker-type-filter">
	                              	<option value="">All</option>
	                              	@foreach($stickerTypes as $key=>$stickerType)
			                            <option value="{{ $stickerType->id }}">{{ $stickerType->name }}</option>
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
						<div class="col-md-3 pl-0" style="display: none;">
							<div class="form-group pt-0">
								<label>Created Date</label>
								<input type="text" class="form-control bg-white" readonly id="date-filter">
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Sticker Name</th>
									<th>Sticker Type</th>
									<th>Status</th>
									<th>Image</th>
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
					New Sticker
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addForm" autocomplete="off" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Sticker Name</label>
											<input id="addName" type="text" name="name" maxlength="{{ limit("event_name.max")}}" class="form-control" placeholder="Event Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Sticker Type</label>
											<select class="select-filter form-control" name="sticker_type" data-placeholder="Select a type" id="addType">
				                              	<option value="">Select a type</option>
				                              	@foreach($stickerTypes as $key=>$stickerType)
							                        <option value="{{ $stickerType->id }}">{{ $stickerType->name }}</option>
		                              			@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="exampleFormControlFile1">Sticker Image Upload</label>
											<input type="file" class="form-control-file" id="uploadImg2" name="uploadImg2[]" multiple required="">
										</div>
									</div>
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
					Edit Sticker
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="POST" enctype="multipart/form-data">
				@csrf
				<input id="editData" type="hidden"  name="data">
				<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Sticker Name</label>
											<input id="editName" type="text" name="name" maxlength="{{ limit("event_name.max")}}" class="form-control" placeholder="Event Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group form-group-default">
											<label>Event Type</label>
											<select class="select-filter form-control" name="sticker_type" data-placeholder="Select a type" id="editType">
				                              	<option value="">Select a type</option>
				                              	@foreach($stickerTypes as $key=>$stickerType)
							                        <option value="{{ $stickerType->id }}">{{ $stickerType->name }}</option>
		                              			@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="input-file input-file-image">
											<img class="img-upload-preview editImage" width="150" src="{{ asset('common/img/150x150.png') }}" alt="preview">
											<input type="file" class="form-control form-control-file" id="uploadImg1" name="uploadImg1" accept="image/*" id="editImage">
											<label for="uploadImg1" class="  label-input-file btn btn-default btn-round">
												<span class="btn-label">
													<i class="fa fa-file-image"></i>
												</span>
												Upload a Image
											</label>
										</div>
									</div>
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
		var delete_url = "{{ route('private.sticker.destroy') }}";
		var table;
		var startDate, endDate, startTime, endTime;
		var startDate1 = moment().startOf('year').format('YYYY-MM-DD HH:mm:ss');
		var endDate1 = moment().format('YYYY-MM-DD HH:mm:ss');
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
	                "url": "{{ route("private.sticker.list") }}",
	                "type": "POST",
	                data: function (d) { 
	                	d.sticker_type = $("#sticker-type-filter").val()     
	                	d.status = $("#status-filter").val() 
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    { "data": "sticker_type.name" },
	                    //{ mRender:  function ( data, type, row ){ return row.tel_code+" "+row.phone} },
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
						 { "data": "image",
						 		"render": function ( data, type, row ) {
	                                        return `<figure class="imagecheck-figure" style="width:50px">
																<img src="${row.path}" alt="title" class="imagecheck-image">
															</figure>`;
	                                    } 
						  },
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> 
	                        					<a href="javascript:void(0);" data-toggle="tooltip" title="Edit Sticker" class="btn btn-link btn-primary btn-lg" data-id="${row.id}" data-original-title="Edit Sticker" onClick="Edit(this)"> 
	                        						<i class="fa fa-edit"></i> 
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
	                                    url: "{{ route('private.sticker.status') }}",
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
			$(".filter-cont .select-filter").change(function(){
				table.fnDraw();
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap"
			});

			$("#addModalOpen").click(function(){
				$("#addModal").find("#addForm")[0].reset();
				$(".select-filter").select2({
					theme: "bootstrap"
				});
				$("#addModal").find("#addName").focus();
				if(addValidator){
					addValidator.resetForm();
				}
				$("#addModal").modal({keyboard: false, static: false});
			});

			//DateRange Picker
			$("#date-filter").daterangepicker({
				opens: 'left',
				startDate1: moment().startOf('year'),
				endDate1: moment(),
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
				startDate1 = start.format('YYYY-MM-DD HH:mm:ss');
				endDate1 =  end.format('YYYY-MM-DD HH:mm:ss');
				table.fnDraw();

			}); 
			/**Create Event Form Validation**/
			addValidator = $("#addForm").validate({
			    rules: {
			    	name:  {
			                    required: true,
			                    minlength	: {{ limit("event_name.min") }},
			                    maxlength	: {{ limit("event_name.max") }}
			                },
			        sticker_type:  {
			                    required: true,
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
			        var data = new FormData(form);
			        
			        	$.ajax({
    						type: "POST",
    						url: "{{ route("private.sticker.create") }}",
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
    						},
    						cache: false,
                        	contentType: false,
                        	processData: false

    					});

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
	               
			        sticker_type:  {required: true}
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
			       	var data1 = new FormData(form);
			        	$.ajax({
    						type: "POST",
    						url: "{{ route("private.sticker.update") }}",
    						data: data1,
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
    						},
    						cache: false,
                        	contentType: false,
                        	processData: false
    					});
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
                url: "{{route('private.sticker.edit')}}",
                data: {data:dataId},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
                		var record = data.data;
                        $('#editData').val(record.id);
                        $('#editName').val(record.name);
                        $('#editImage').val(record.path);
                        $('.editImage').attr('src',record.path);
                        $('#editType').val(record.type).trigger("change");
                         $('#editModal').modal();
                    }
                    else
                    {
                    	notifyWarning(data.message);
                	}
            	}
	        }); 
	    }
		
	</script>

	
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
	    .select2-container--bootstrap .select2-results__group {
		    color: #555;
		    font-weight: 600;
		    display: block;
		    padding: 7px 12px;
		    line-height: 1.42857143;
		    white-space: nowrap;
		    margin-top: 6px;
		}
	</style>
@endpush