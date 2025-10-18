<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row justify-content-center">
            <div class="col-md-5">
               @if($errors->any())
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                     <i class="icon-times-circle"></i>
                     <ul>
                        @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                        @endforeach
                     </ul>
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
                  <div class="card-body" style="padding:40px;">
                     <form id="login-form" name="login-form" class="mb-0" action="{{ url()->full() }}" method="POST">
                        @csrf
                        <div class="row">
                           <div class="col-12 form-group">
                              <label>New Password:</label>
                              <input type="password" name="password" id="password" class="form-control no-outline" placeholder="Enter password" required>
                              @error('password') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                           <div class="col-12 form-group">
                              <label>Password Confirmation:</label>
                              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control no-outline" placeholder="Enter password confirmation" required>
                              @error('password_confirmation') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                           <div class="col-12 form-group mt-3">
                              <button type="submit" class="button button-3d bg-teal float-right m-0">Reset Now</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>