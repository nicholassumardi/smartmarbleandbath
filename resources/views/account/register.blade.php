<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
               @if($errors->any())
                  <div class="alert alert-warning">Data you sent is incorrect</div>
               @endif
               <div class="card mb-0">
                  <div class="card-body">
                     <div class="text-center">
                        <h5 class="mb-2 font-weight-bold text-uppercase">Sign Up Now</h5>
                        <small class="mt-4 font-italic">Already have an account? <a href="{{ url('account/login') }}" class="text-primary font-size-13">Login here</a></small>
                     </div>
                     <div class="form-group"><hr></div>
                     <form id="login-form" name="login-form" class="mb-0" action="{{ url('account/register') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                           <label class="col-lg-3 text-capitalize col-form-label font-size-13">Full Name :</label>
                           <div class="col-lg-9">
                              <input type="text" name="name" id="name" class="form-control font-size-13 no-outline" value="{{ old('name') }}" placeholder="Enter full name" required>
                              @error('name') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-3 text-capitalize col-form-label font-size-13">Email :</label>
                           <div class="col-lg-9">
                              <input type="email" name="email" id="email" class="form-control font-size-13 no-outline" value="{{ old('email') }}" placeholder="Enter email" required>
                              @error('email') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-3 text-capitalize col-form-label font-size-13">Phone :</label>
                           <div class="col-lg-9">
                              <input type="text" name="phone" id="phone" class="form-control font-size-13 no-outline" value="{{ old('phone') }}" placeholder="Enter phone" required>
                              @error('phone') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-3 text-capitalize col-form-label font-size-13">Password :</label>
                           <div class="col-lg-9">
                              <div class="input-group" id="show_password">
                                 <input type="password" name="password" id="password" class="form-control font-size-13 no-outline" placeholder="Enter password" required>
                                 <div class="input-group-append" style="height:33px;">
                                    <span class="input-group-text">
                                       <a href="javascript:void(0);" aria-hidden="true">
                                          <i class="icon-eye-slash"></i>
                                       </a>
                                    </span>
                                 </div>
                              </div>
                              @error('password') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group">
                           <p class="text-center text-muted mt-4 mb-4">
                              <small>
                                 By registering, you agree to the applicable <a href="{{ url('information/terms_and_conditions') }}" class="text-primary">Terms and Conditions</a>
                              </small>
                           </p>
                        </div>
                        <div class="form-group mt-3">
                           <button type="submit" class="button button-3d bg-teal float-right m-0">Register</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>