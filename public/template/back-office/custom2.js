$(function() {
  
});

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

/* setInterval(function () {
	cekNotif();
}, 60000); */

function cekNotif(){
	$.ajax({
		url: 'https://smartmarbleandbath.com/admin/profile/getNotification',
		type: 'POST',
		dataType: 'JSON',
		data: {},
		contentType: false,
		processData: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function() {
			
		},
		success: function(response) {
			if(response.status == '200'){
				Push.create(response.title, {
					body: response.message,
					icon: 'https://smartmarbleandbath.com/website/icon.png',
					link: 'admin/login',
					/*timeout: 4000,*/
					onClick: function () {
						window.focus();
						this.close();
					},
					vibrate: [200, 100, 200, 100, 200, 100, 200]
				});
			}
		},
		error: function() {
			
		}
	});
}