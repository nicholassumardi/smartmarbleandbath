var swalInit = swal.mixin({
   buttonsStyling: false,
   confirmButtonClass: 'btn btn-primary',
   cancelButtonClass: 'btn btn-light'
});

lightbox.option({
   resizeDuration: 100,
   wrapAround: true
});

$(function() {
   $('html').tooltip({selector: '[data-popup="tooltip"]'});
   $('.form-check-input-styled').uniform();
   $('.select2').select2();
   $('.form-check-input-switch').bootstrapSwitch();
   
   $('.select2-tags').select2({
      tags: true
   });

   setInterval(function() {
      var d = new Date();
      var s = d.getSeconds();
      var m = d.getMinutes();
      var h = d.getHours();
      $('#header-clock-realtime').text(('0' + h).substr(-2) + ':' + ('0' + m).substr(-2) + ':' + ('0' + s).substr(-2));
   }, 1000);

   $('.form-check-input-styled-primary').uniform({
      wrapperClass: 'border-primary-600 text-primary-800'
   });

   $('.form-check-input-styled-danger').uniform({
      wrapperClass: 'border-danger-600 text-danger-800'
   });

   $('.form-check-input-styled-success').uniform({
      wrapperClass: 'border-success-600 text-success-800'
   });

   $('.form-check-input-styled-warning').uniform({
      wrapperClass: 'border-warning-600 text-warning-800'
   });

   $('.form-check-input-styled-info').uniform({
      wrapperClass: 'border-info-600 text-info-800'
   });

   $('.form-check-input-styled-custom').uniform({
      wrapperClass: 'border-indigo-600 text-indigo-800'
   });
   
	var element = $('.sidebar-sticky'),
		originalY = element.offset().top;

	var topMargin = 73;

	element.css('position', 'relative');
	element.css('border', '1px solid;');
	
	$(window).on('scroll', function(event) {
		var scrollTop = $(window).scrollTop();

		/* element.stop(false, false).animate({
			top: scrollTop < originalY
					? 0
					: scrollTop - originalY + topMargin
		}, 0);*/
		var imgtop = scrollTop < originalY ? 0 : scrollTop - originalY + topMargin;
		element.css('top', imgtop + 'px');
	});
	
	$('.nav-item-submenu').hover(function() {
		if($('.navbar-top').hasClass('sidebar-xs')){
			/* if ($(window).width() < 1072) { */
				$('.navbar-top').removeClass('sidebar-xs');
			/* } */
		}
	  }, function() {
		
	  }
	);
	
	$('.content-wrapper').click(function() {
		if ($(window).width() < 1072) {
			if(!$('.navbar-top').hasClass('sidebar-xs')){
				$('.navbar-top').addClass('sidebar-xs');
			}
		}
	});
	
	$('#modal_version').on('hidden.bs.modal', function (e) {
		$('#data_version_title').html('');
		$('#data_version_content').html('');
	});

});

function ckEditor(selector) {
   CKEDITOR.replace(selector, {
      height: 250,
      extraPlugins: 'forms'
   });
}

$.dateString = function(param) {
   var date   = new Date(param);
   var string = date.toDateString();
   var parse  = string.split(' ');

   return parse[1] + ' ' + parse[3];
};

function previewImage(event, selector) {
   if(event.files && event.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
         $(selector).attr('href', e.target.result);
         $(selector + ' img').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(event.files[0]);
   }
}

function loadingOpen(selector) {
   $(selector).waitMe({
      effect: 'timer',
      text: 'Please Wait ...',
      bg: 'rgba(255,255,255,0.7)',
      color: '#000',
      waitTime: -1,
      textPos: 'vertical'
   });
}

function loadingClose(selector) {
   $(selector).waitMe('hide');
}

function notif(type, background, message) {
   new Noty({
      theme: ' alert ' + background + ' text-white alert-styled-left p-0',
      text: message,
      type: type,
      timeout: 1000
   }).show();
}

function select2ServerSide(selector, endpoint) {
   $(selector).select2({
      placeholder: '-- Choose --',
      minimumInputLength: 3,
      allowClear: true,
      cache: true,
      dropdownParent: $('body').parent(),
      ajax: {
         url: endpoint,
         type: 'GET',
         dataType: 'JSON',
         delay: 250,
         data: function(params) {
            return {
               search: params.term
            };
         },
         processResults: function(data) {
            return {
               results: data.items
            }
         }
      }
   });
}

function formatRupiah(angka){
	var number_string = angka.value.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}
 
	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	//angka.value = 'IDR ' + rupiah;
	angka.value = rupiah;
}

Notification.requestPermission().then(function (permission) {
    if(permission == 'denied'){
		setInterval(function(){ 
			errorNotification();
		}, 1000);
	}
});

function errorNotification(){
	notif('error', 'bg-danger', 'Please allow notification!');
}

// cekNotif();

/* setInterval(function () {
	cekNotif();
}, 60000); */

// function cekNotif(){
// 	$.ajax({
// 		url: 'https://smartmarbleandbath.com/admin/profile/getNotification',
// 		type: 'POST',
// 		dataType: 'JSON',
// 		data: {},
// 		contentType: false,
// 		processData: false,
// 		headers: {
// 			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
// 		},
// 		beforeSend: function() {
			
// 		},
// 		success: function(response) {
// 			if(response.status == '200'){
// 				Push.create(response.title, {
// 					body: response.message,
// 					icon: 'https://smartmarbleandbath.com/website/icon.png',
// 					link: 'admin/login',
// 					/*timeout: 4000,*/
// 					onClick: function () {
// 						window.focus();
// 						this.close();
// 					},
// 					vibrate: [200, 100, 200, 100, 200, 100, 200]
// 				});
// 			}
// 		},
// 		error: function() {
			
// 		}
// 	});
// }

//disable enter
$(document).keypress(
  function(event){
    if (event.which == '13') {
		if($("textarea").is(":focus")){
			if(e.which == 13) {
				
			}
		}else if($(".cash-flow-input").is(":focus")){
			if(e.which == 13) {
				
			}
		}else{
			notif('error', 'bg-danger', 'Ups! Enter key has been disabled.');
			event.preventDefault();
		}
    }
});

window.prettyPrint && prettyPrint();

var defaultOpts = {
  draggable: true,
  resizable: true,
  movable: true,
  keyboard: true,
  title: true,
  modalWidth: 320,
  modalHeight: 320,
  fixedContent: true,
  fixedModalSize: false,
  initMaximized: false,
  gapThreshold: 0.02,
  ratioThreshold: 0.1,
  minRatio: 0.05,
  maxRatio: 16,
  headToolbar: ['maximize', 'close'],
  footToolbar: ['zoomIn', 'zoomOut', 'prev', 'fullscreen', 'next', 'actualSize', 'rotateRight'],
  multiInstances: true,
  initEvent: 'click',
  initAnimation: true,
  fixedModalPos: false,
  zIndex: 1090,
  dragHandle: '.magnify-modal',
  progressiveLoading: true
};

/* chat */
$(function() {
	$('#remove-chat-box').click(function() {
		$('.chat-box').fadeOut("fast");	
	});
	
	$('.show-chat-box').click(function() {
		$('.chat-box').fadeIn("fast");
		$('.chat-dropdown').trigger('click');
		goBottom();
	});
	
	goBottomSidebar();
});

function goBottom(){
	$(".chat-main").animate({
		scrollTop: $(
		  '.chat-main').get(0).scrollHeight
	}, 500);
}

function goBottomSidebar(){
	if($(".nav-link.active").length > 0){
		$(".card-sidebar-mobile").animate({ scrollTop: $(".nav-link.active").position().top - 250 }, "fast");
	}
}

function showVersion(){
	$.ajax({
		url: 'https://smartmarbleandbath.com/admin/setting/version/get_latest_version',
		type: 'POST',
		dataType: 'JSON',
		data: {},
		contentType: false,
		processData: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function() {
			loadingOpen('body');
		},
		success: function(response) {
			if(response.status == '200'){
				$('#modal_version').modal('toggle');
				$('#data_version_title').html(response.version);
				$('#data_version_content').html(response.changelog);
			}
			
			loadingClose('body');
		},
		error: function() {
			
		}
	});
}