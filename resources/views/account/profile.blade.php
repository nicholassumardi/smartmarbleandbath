<section id="content">
   <div class="content-wrap">
      <div class="container clearfix">
         <div class="row clearfix">
            <div class="col-md-12">
               <img src="{{ $profile->photo() }}" class="alignleft img-circle img-thumbnail my-0" alt="{{ $profile->name }}" style="max-width: 84px;">
               <div class="heading-block border-0">
                  <h3>{{ $profile->name }}</h3>
                  <span>Your Profile Bio</span>
               </div>
               @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     <i class="icon-check-circle"></i>
                     <strong>Success!</strong> {{ session('success') }}
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
               <div class="row clearfix">
                  <div class="col-lg-12">
                     <form id="login-form" name="login-form" class="mb-0" action="{{ url('account/profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                           <label class="col-lg-2 col-md-3 text-capitalize col-form-label">Photo</label>
                           <div class="col-lg-10 col-md-9">
                              <input type="file" name="photo" id="photo" class="form-control no-outline">
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-2 col-md-3 text-capitalize col-form-label">Full Name</label>
                           <div class="col-lg-10 col-md-9">
                              <input type="text" name="name" id="name" class="form-control no-outline" value="{{ $profile->name }}" placeholder="Enter full name" required>
                              @error('name') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-2 col-md-3 text-capitalize col-form-label">Email</label>
                           <div class="col-lg-10 col-md-9">
                              <input type="email" name="email" id="email" class="form-control no-outline" value="{{ $profile->email }}" placeholder="Enter email" required>
                              @error('email') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group row">
                           <label class="col-lg-2 col-md-3 text-capitalize col-form-label">Phone</label>
                           <div class="col-lg-10 col-md-9">
                              <input type="text" name="phone" id="phone" class="form-control no-outline" value="{{ $profile->phone }}" placeholder="Enter phone" required>
                              @error('phone') <small class="text-danger font-italic">{{ $message }}</small> @enderror
                           </div>
                        </div>
                        <div class="form-group mt-3">
                           <button type="submit" class="button button-3d bg-teal float-right m-0">Save</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="w-100 line d-block d-md-none"></div>
         </div>
      </div>
   </div>
</section>