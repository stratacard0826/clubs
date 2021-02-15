@extends("private.layouts.app")
@push('css')
<link rel="stylesheet" href="{{ asset('private/assets/js/plugin/Lightcase/css/lightcase.css') }}">
<style type="text/css">
.drop-over a:after{
	display:none;
}
.drop-over a{
	color:#575962;
}
.text-secondary {
    color: #6c757d !important;
}
.text-secondary:hover {
    color: #6c757d !important;
}
.font-12{
	font-size: 12px !important;
}
.post-hover .model-hover{
	opacity: 0;
}
.post-hover:hover .model-hover{
	opacity: 1 !important;
}
.post-image{
	
	width: 200px;
	height: 200px;
}
</style>
@endpush
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
				<a href="{{ route('private.users.list') }}">User List</a>
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
		<div class="col-md-8 append-post">
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
						<div class="desc">{{ $profile->tel_code }} {{ $profile->phone }}</div>
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
							<div class="number">{{ $profile->posts_count }}</div>
							<div class="title"><a href="{{route('private.user.viewpost', ['key' => ''])}}/{{$key}}">Post</a></div>
							
						</div>
						<div class="col">
							<div class="number">{{ $profile->follower_list_count }}</div>
							<div class="title">Followers</div>
						</div>
						<div class="col">
							<div class="number">{{ $profile->followed_list_count }}</div>
							<div class="title">Following</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- The Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
	      	<!-- Modal Header -->
	      	<div class="modal-header">
	        	<h4 class="modal-title">Most Relevant</h4>
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	      	</div>
	      	<!-- Modal body -->
	      	<div class="modal-body comment-post">
	      	</div>
		</div>
	</div>
</div>
<!--Confirm deleted modal-->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h2 class="modal-title" id="headtext-conform">
                    
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bodytext-conform">

            </div>
            <div class="modal-footer no-bd">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="confirmDeleted" data-loading-text="Checking.." data-loading="" data-text="" >Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push("js")

	<script src="{{ asset('private/assets/js/plugin/Lightcase/js/lightcase.js')}}"></script>
	<script type="text/javascript">
			var page=0;
			var lastPage=1;
			var ajaxCall = 0;
			var commentPage=0;
			var commentLastPage=1;
			var commentId=0;
			var userId={{$profile->id}};
			var typeId = 0;
	    	var postId = 0;
		
		$(document).ready(function() {
			getPost();
			
			$(window).scroll(function() { 
				if($(window).scrollTop() == $(document).height() - $(window).height()) { 
					// ajax call get data from server and append to the div
					if(lastPage > page){
	    				
						getPost();
					}
			 	} 
			 });
			$('.append-post').on('click','.getcomment',function(){
				var getCommentId = $(this).data('comment');
				commentId = getCommentId;
				commentPage = 0;
				commentLastPage = 1;
				if(commentId>0 && commentLastPage > commentPage)
				{
					getComment(commentId);
				}
				//lg(getCommentId);
			});
			$(".modal-dialog-scrollable .modal-body").scroll(function() { 
				var element = document.querySelector('.modal-dialog-scrollable .modal-body');
				if (element.offsetHeight + element.scrollTop === element.scrollHeight) {
					if(commentId > 0 && commentLastPage > commentPage)
					{
						getComment(commentId);
					}
				}
			});
	
	    
		    function getPost()
		    {

	    		if(ajaxCall == 0){
	    			page += 1;
	    			ajaxCall = 1;
					$.ajax({
				        type: "POST",
				        url: '{{ route("private.user.getposts") }}',
				        data: {page:page,user_data:userId},
				        dataType: "json",
				        success: function(response)
				        {
				           	if(response.success == 1){
				           		let data = response.data;
				           		page = data.current_page;
				           		lastPage = data.last_page;
				           		var posts = data.data;
				           		var postHtml = "";
				           		lg(posts.length);
				           		if(posts.length > 0){
				           			$.each(posts,function(key,post){
						           		var attachmentHtml = '';
				           				var attachments = post.attachments;
				           				if(attachments.length > 0){
				           					attachmentHtml = '<div class="row image-gallery">';
				           					var i = 0;
				           					$.each(attachments,function(key,attachment){
				           						var imgClass = "";
												if(attachments.length == 1){
													imgClass = "col-md-12";
												}else if(attachments.length == 2){
													imgClass = "col-md-6";
												}else if(attachments.length == 3){
													imgClass = "col-md-4";
												}
												if(i > 3){
													imgClass += "d-none";
												}
												i++;
												attachmentHtml += `<a href="${attachment.file}" data-rel="lightcase:post${post.id}" class="col-6 ${imgClass} mb-2">
													<img src="${attachment.thumb_file}" data-src='${attachment.file}' class="img-fluid post-image" data-type="${attachment.type}" >
												</a>`;
												
				           							
				           					});
				           							attachmentHtml += '</div';
				           				}
				           				if(post.blocked_at)
				           				{
				           					var blockicon = "d-block";
				           				}
				           				else
				           				{
				           					var blockicon = "d-none";
				           				}
				           				tempHtml = `<div class="card card-post card-round">
														<div class="card-body">
															<div class="d-flex post-hover">
																<div class="avatar">
																	<a href="{{route('private.user.profile', ['key' => ''])}}/{{$key}}"><img src="{{ $profile->profile_pic }}" alt="..." class="avatar-img rounded-circle"></a>
																</div>
																<div class="info-post ml-2 mw-100">
																	<a href="{{route('private.user.profile', ['key' => ''])}}/{{$key}}"><p class="username">{{ $profile->name }}</p></a>
																	<p class="date text-muted font-12">${moment(post.created_at).format("DD MMM YYYY HH:MM A")}</p>
																</div>
																<div class="drop-over text-right float-right ml-auto ">
																	<i class="fas fa-user-lock fas fa-user-lock float-left mr-2 ${blockicon}" id="blockicon-${post.id}"></i>
																	<a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
																	<div class="dropdown-menu" aria-labelledby="navbarDropdown">
																		
																	 		<a class="dropdown-item post-id " id="post-${post.id}" data-postid="${post.id}" data-type="${post.blocked_at ?  1 :  2}" href="#">${post.blocked_at ?  "Unblock" :  "Block"}</a>
																	 	
																	</div>
																</div>
															</div>
															<div class="separator-solid"></div>
																<p class="card-text">${post.text ? post.text : ""}</p>
															${attachmentHtml}
															<div class="separator-solid"></div>
															<div class="d-flex justify-content-around">
														    	<div class="p-2 text-secondary "><i class="fas fa-fire"></i> &nbsp; Like &nbsp; ${post.like_count > 0 ? post.like_count: 0 } </div>
														    	
														    		<a href="javascript:void(0);" class="text-secondary getcomment" data-comment="${post.id}"><div class="p-2 "><i class="fas fa-comment"></i> &nbsp; Comment &nbsp; ${post.comment_count > 0 ? post.comment_count: 0 }</div></a>
														    	
														    	<div class="p-2 text-secondary "><i class="fa fa-share"></i> &nbsp; Share &nbsp; ${post.share_count > 0 ? post.share_count: 0 }</div>
														  	</div>
														</div>
													</div>`;
										$(".append-post").append(tempHtml);
				           			});
				           		}
				           		else
				           		{
				           			postHtml =`<div class="col-md-12">
											<div class="card">
												<div class="card-body text-center">
												<i class="far fa-newspaper text-muted" style="font-size: 15em;"></i>
													<h2 class="text-muted">Post not available</h2>
												</div>
											</div>
										</div>`;
				           			$('.append-post').append(postHtml);
				           		}
				           		$('.append-post a[data-rel^=lightcase]').lightcase({
									transition: 'scrollHorizontal',
									showSequenceInfo: false,
									showTitle: false
								});

				           	}else{
				           		notifyWarning(response.message);
				           	}
				       	},
				       	complete: function(data, status) {
				       		ajaxCall = 0;
				       	}
				   	});	
			    }
		   	}
				

		    function getComment(commentId)
		    {
		    	commentPage += 1;
		    	$.ajax({
			        type: "POST",
			        url: '{{ route("private.user.getcommentlist") }}',
			        data: {page:commentPage,post_data:commentId},
			        dataType: "json",
			        success: function(response)
			        {
			           	if(response.success == 1){
			           		let data = response.data;
			           		commentPage = data.current_page;
			           		commentLastPage = data.last_page;
			           		var comments = data.data;
			           		var commentHtml = "";
			           		if(comments.length > 0){
			           			$('#myModal').modal();
			           			//lg(comments);
			           			$.each(comments,function(key,post){
			           				var subCommentHtml = '';
			           				var subComments = post.sub_comments;
			           				if(post.blocked_at)
	           						{
	           							var block = "d-block";
	           						}
	           						else
	           						{
	           							var block = "d-none";
	           						}
			           				if(subComments.length > 0){
			           					$.each(subComments,function(key,subcomment){
			           						if(subcomment.blocked_at)
			           						{
			           							var sub_block = "d-block";
			           						}
			           						else
			           						{
			           							var sub_block = "d-none";
			           						}
			           						subCommentHtml += `<div class="col-md-11 offset-md-1 pt-2">
												        		<div class="d-flex post-hover">
																	<div class="avatar avatar-xs">
																		<a href="{{route('private.user.profile', ['key' => ''])}}/{{$key}}"><img src="${subcomment.user.profile_pic}" alt="..." class="avatar-img rounded-circle"></a>
																	</div>
																	<div class="pt-1 ml-2">
																		<a href="{{route('private.user.profile', ['key' => ''])}}/${subcomment.user.key}"><small class="fw-bold">${subcomment.user.name}</small></a>
																		<small class="text-muted ml-2">${subcomment.text}</small><br>
																		<span class=""><small class="text-muted font-12"><i class="fas fa-fire"></i> &nbsp; Like &nbsp;${subcomment.like_count > 0 ? subcomment.like_count:0 }</small><small class="text-muted ml-2 font-12">${moment(subcomment.created_at).format("DD MMM YYYY HH:MM A")}</small></span>
																	</div>
																	<div class="pt-1 ml-2">
																		<div class="drop-over text-right float-right ml-auto">
																			<a class="dropdown-toggle model-hover" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
																			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
																				<a class="dropdown-item text-muted subcomment-id" id="subcomment-${subcomment.id}" data-subcommentid="${subcomment.id}"  data-type="${subcomment.blocked_at ?  1 :  2}" href="#">${subcomment.blocked_at ?  "Unblock" :  "Block"}</a>
																			</div>
																		</div>
																	</div>
																	<div class="pt-1 ml-2">
																		<i class="fas fa-user-lock fas fa-user-lock float-left mr-2 ${sub_block}" id="cmdblockicon-${subcomment.id}"></i>
																	</div>
																</div>
												        	</div>`;
			           					})
			           				}
			           				commentHtml +=`<div class="col-md-12">
									        		<div class="d-flex post-hover">
														<div class="avatar avatar-sm">
															<a href="{{route('private.user.profile', ['key' => ''])}}/{{$key}}"><img src="{{ $profile->profile_pic }}" alt="..." class="avatar-img rounded-circle"></a>
														</div>
														<div class="pt-1 ml-2">
															<a href="{{route('private.user.profile', ['key' => ''])}}/{{$key}}"><small class="fw-bold">{{ $profile->name }}</small></a>
															<small class="text-muted ml-2">${post.text}</small><br>
															<span><small class="text-muted font-12"><i class="fas fa-fire"></i> &nbsp; Like &nbsp;${post.like_count > 0 ? post.like_count: 0 }</small><small class="text-muted ml-2 font-12">${moment(post.created_at).format("DD MMM YYYY HH:MM A")}</small></span>
														</div>
														<div class="pt-1 ml-2">
															<div class="drop-over text-right float-right ml-auto">
																<a class="dropdown-toggle model-hover" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i>
																</a>
																<div class="dropdown-menu" aria-labelledby="navbarDropdown">
																	<a class="dropdown-item text-muted comment-id" id="comment-${post.id}" data-type="${post.blocked_at ?  1 :  2}" data-commentid="${post.id}" href="#">${post.blocked_at ?  "Unblock" :  "Block"}</a>
																</div>
															</div>
														</div>
														<div class="pt-1 ml-2">
															<i class="fas fa-user-lock fas fa-user-lock float-left mr-2 ${block}" id="cmdblockicon-${post.id}"></i>		
														</div>
													</div>
													${subCommentHtml}
									        	</div>`;
									
			           			});
			           			$(".comment-post").html(commentHtml);
			           		}else
			           		{
			           			notifyWarning('No comment');
			           		}
			           	}
			           	else
			           	{
			           		notifyWarning(response.message);
			           	}
			        }
			    });
		    }
		    function ConfirmMessage(typeId)
		    {
		    	if(typeId==1)
    			{
    				$("#headtext-conform").text('Unblock Confirmation');
    				$("#bodytext-conform").html('<p>Are you sure you want to unblock this?</p>');
    			}
    			else if(typeId==2)
    			{
    				$("#headtext-conform").text('Block Confirmation');
    				$("#bodytext-conform").html('<p>Are you sure you want to block this?</p>');
    			}	
		    }

		    $('.append-post').on('click', ".post-id",function(e){
		    	e.preventDefault();
		    	var typeId = $(this).attr("data-type");
		    	var postId = $(this).attr("data-postid");
		    	if(postId > 0)
		    	{
		    		ConfirmMessage(typeId);
		    		$("#deleteConfirmModal").modal({ backdrop: 'static', keyboard: false }).off('click', '#confirmDeleted').on('click', '#confirmDeleted', function () {
                        loadButton('#confirmDeleted');
                        $.ajax({
                           type: "POST",
                           url: '{{ route("private.user.postblock") }}',
                           data: {type: typeId,id:postId},
                           dataType: "json",
                           success: function(data) {
                                loadButton('#confirmDeleted');
                                $("#deleteConfirmModal").modal('hide');
                                if(data.success == 1){
	                                if(typeId==1)
                                    {
                                    	console.log($('.append-post #blockicon-'+postId).removeClass('d-block').addClass('d-none'));
                                    	$('.append-post #post-'+postId).attr('data-type','2').text('Block');
                                    }
                                    else if(typeId==2)
                                    {
                                    	console.log($('.append-post #blockicon-'+postId).removeClass('d-none').addClass('d-block'));
                                    	$('.append-post #post-'+postId).attr('data-type','1').text('Unblock');
                                    }

                                    notifySuccess(data.message);
                                    //table.fnDraw();
                                }
                                else
                                {
                                    notifyError(data.message);
                                }
                           }
                        }); 
                   });
		    	}
		    });

		    $('.comment-post').on('click', ".subcomment-id",function(e){
		    	e.preventDefault();
		    	var typeId = $(this).attr("data-type");
		    	var subCommentId = $(this).attr("data-subcommentid");

		    	if(subCommentId > 0)
		    	{
		    		ConfirmMessage(typeId);
		    		$("#deleteConfirmModal").modal({ backdrop: 'static', keyboard: false }).off('click', '#confirmDeleted').on('click', '#confirmDeleted', function (e) {
                        loadButton('#confirmDeleted');
                        $.ajax({
                           type: "POST",
                           url: '{{ route("private.user.commentblock") }}',
                           data: {type: typeId,id:subCommentId},
                           dataType: "json",
                           success: function(data) {
                                loadButton('#confirmDeleted');
                                $("#deleteConfirmModal").modal('hide');
                                //$("#myModal").modal('hide');
                                if(data.success == 1){

                                	if(typeId==1)
                                    {
                                    	console.log($('.comment-post #cmdblockicon-'+subCommentId).removeClass('d-block').addClass('d-none'));
                                    	$('.comment-post #subcomment-'+subCommentId).attr('data-type','2').text('Block');
                                    }
                                    else if(typeId==2)
                                    {
                                    	console.log($('.comment-post #cmdblockicon-'+subCommentId).removeClass('d-none').addClass('d-block'));
                                    	$('.comment-post #subcomment-'+subCommentId).attr('data-type','1').text('Unblock');
                                    }
                                    notifySuccess(data.message);
                                    //table.fnDraw();
                                }
                                else
                                {
                                    notifyError(data.message);
                                }
                           }
                        }); 
                   });
		    	}
		    });

		    $('.comment-post').on('click', ".comment-id",function(e){
		    	e.preventDefault();
		    	var typeId = $(this).attr("data-type");
		    	var commentId = $(this).attr("data-commentid");
		    	//alert(commentId);
		    	if(commentId > 0)
		    	{
		    		ConfirmMessage(typeId);
		    		$("#deleteConfirmModal").modal({ backdrop: 'static', keyboard: false }).off('click', '#confirmDeleted').on('click', '#confirmDeleted', function (e) {
                        loadButton('#confirmDeleted');
                        $.ajax({
                           type: "POST",
                           url: '{{ route("private.user.commentblock") }}',
                           data: {type: typeId,id:commentId},
                           dataType: "json",
                           success: function(data) {
                                loadButton('#confirmDeleted');
                                $("#deleteConfirmModal").modal('hide');
                                //$("#myModal").modal('hide');
                                if(data.success == 1){
                                	if(typeId==1)
                                    {
                                    	console.log($('.comment-post #cmdblockicon-'+commentId).removeClass('d-block').addClass('d-none'));
                                    	$('.comment-post #comment-'+commentId).attr('data-type','2').text('Block');
                                    }
                                    else if(typeId==2)
                                    {
                                    	console.log($('.comment-post #cmdblockicon-'+commentId).removeClass('d-none').addClass('d-block'));
                                    	$('.comment-post #comment-'+commentId).attr('data-type','1').text('Unblock');
                                    }
                                    notifySuccess(data.message);
                                    //table.fnDraw();
                                }
                                else
                                {
                                    notifyError(data.message);
                                }
                           }
                        }); 
                   });
		    	}
		    });
	    });
	</script>
@endpush