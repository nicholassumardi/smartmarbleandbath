<section id="content">
   <div class="content-wrap">
      <div class="container">
         <div class="row justify-content-center">
            <div class="postcontent col-lg-9">
               <h3>Send us an Email</h3>
               <form action="" method="POST">
                  @csrf
                  <div class="row">
                     <div class="col-md-4 form-group">
                        <label class="font-size-13">Name <small>*</small></label>
                        <input type="text" id="name" name="name" class="sm-form-control font-size-13 no-outline" required>
                     </div>
                     <div class="col-md-4 form-group">
                        <label class="font-size-13">Email <small>*</small></label>
                        <input type="email" id="email" name="email" class="sm-form-control font-size-13 no-outline" required>
                     </div>
                     <div class="col-md-4 form-group">
                        <label class="font-size-13">Phone <small>*</small></label>
                        <input type="text" id="phone" name="phone" class="sm-form-control font-size-13 no-outline" required>
                     </div>
                     <div class="w-100"></div>
                     <div class="col-md-12 form-group">
                        <label class="font-size-13">Subject <small>*</small></label>
                        <input type="text" id="subject" name="subject" class="sm-form-control font-size-13 no-outline" required>
                     </div>
                     <div class="w-100"></div>
                     <div class="col-12 form-group">
                        <label class="font-size-13">Message <small>*</small></label>
                        <textarea class="sm-form-control font-size-13 no-outline" id="message" name="message" rows="6" cols="30" required></textarea>
                     </div>
                     <div class="col-12 form-group">
                        <button type="submit" class="button button-3d bg-teal">Send Message</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
   $(function() {
      var status = '{{ session("success") }}';
      if(status) {
         Swal.fire('Success!', status, 'success');
      }
   });
</script>