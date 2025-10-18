<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row justify-content-center">
            <div class="col-lg-5 col-md-10 col-12">
               @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     <i class="icon-check-circle"></i>
                     <strong>Success!</strong> {{ session('success') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               @elseif(session('info'))
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                     <i class="icon-info-circle"></i>
                     <strong>Ooopsss!</strong> {{ session('info') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               @elseif(session('failed'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     <i class="icon-times-circle"></i>
                     <strong>Sorry,</strong> {{ session('failed') }}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               @endif
               <div class="card mb-0">
                  <div class="card-header">
                     <div class="card-title">
                        <div class="text-center">
                           <h5 class="mt-4 mb-0 text-uppercase font-weight-semibold text-dark">Buy anything will be cheaper at Smart Marble</h5>
                           <h6 class="mt-2 text-muted">Login now to continue your transaction</h6>
                        </div>
                     </div>
                  </div>
                  <div class="card-body" style="padding:40px;">
                     <form id="login-form" name="login-form" class="mb-0" action="{{ url('account/login') }}" method="POST">
                        @csrf
                        <div class="row">
                           <div class="col-12 form-group">
                              <label class="font-size-13">Email :</label>
                              <input type="email" name="email" id="email" class="form-control font-size-13 no-outline" placeholder="Enter email">
                           </div>
                           <div class="col-12 form-group">
                              <label class="font-size-13">Password :</label>
                              <div class="input-group" id="show_password">
                                 <input type="password" name="password" id="password" class="form-control font-size-13 no-outline" placeholder="Enter password">
                                 <div class="input-group-append" style="height:33px;">
                                    <span class="input-group-text">
                                       <a href="javascript:void(0);" aria-hidden="true">
                                          <i class="icon-eye-slash"></i>
                                       </a>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12">
                              <button type="submit" class="button button-3d bg-teal m-0 col-12 button-small">Login</button>
                           </div>
                           <div class="col-12 text-center mt-4">
                              <div>
                                 <small>
                                    <a href="javascript:void(0);" id="link_forgot_password" data-toggle="modal" data-target=".bs-example-modal-lg" class="text-primary">Forgot Password?</a>
                                 </small>
                              </div>
                              <small class="font-italic">don't have an account? <a href="{{ url('account/register') }}" class="text-primary font-size-13">Register here</a></small>
                           </div>
                        </div>
                     </form>
                     <div class="line line-sm"></div>
                     <form method="POST" action="{{ url('account/login_social_media') }}">
                        @csrf
                        <div class="center">
                           <button type="submit" name="submit" class="button button-rounded si-facebook si-colored col-12 button-small" value="facebook">
                              <i class="icon-facebook"></i> Login With Facebook
                           </button>
                           <button type="submit" name="submit" class="button button-rounded si-google si-colored col-12 button-small" value="google" style="background:#ec3923 !important;">
                              <i class="icon-google"></i> Login With Google
                           </button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<div class="modal fade bs-example-modal-lg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-body">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
               <div id="notif_forgot_password"></div>
               <div class="form-group font-size-12">
                  <label class="font-size-11">Email :</label>
                  <input type="text" name="email_forgot_password" id="email_forgot_password" class="form-control font-size-12 no-outline" placeholder="Enter email">
               </div>
               <div class="form-group"><hr></div>
               <div class="form-group">
                  <div class="text-right">
                     <button type="button" onclick="forgotPassword()" class="button bg-teal button-mini"><i class="icon-line-send"></i> Send</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   $(function() {
      $('#link_forgot_password').click(function() {
         $('#notif_forgot_password').html('');
         $('#email_forgot_password').val('');
      });
   });

   function forgotPassword() {
      if($('#email_forgot_password').val()) {
         $.ajax({
            url: '{{ url("account/forgot_password") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
               email: $('#email_forgot_password').val()
            },
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
               $('#notif_forgot_password').html('');
               loadingOpen('.modal-content');
            },
            success: function(response) {
               loadingClose('.modal-content');
               if(response.status == true) {
                  $('#email_forgot_password').val('');
                  $('#notif_forgot_password').html(`
                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icon-check-circle"></i>
                        <strong>Success!</strong> ` + response.message + `
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  `);
               } else {
                  $('#notif_forgot_password').html(`
                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icon-times-circle"></i>
                        <strong>Sorry,</strong> ` + response.message + `
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  `);
               }
            },
            error: function() {
               loadingClose('.modal-content');
               Swal.fire('Server error!', '', 'error');
            }
         });
      } else {
         Swal.fire('Please enter email!', '', 'info');
      }
   }
</script>