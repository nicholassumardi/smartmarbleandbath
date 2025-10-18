<div class="content-wrapper">
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">Update News</span>
				</h4>
			</div>
			<div class="header-elements">
				<div class="d-flex justify-content-center">
					<a href="{{ url('admin/master_data/digital/news') }}" class="btn bg-secondary btn-labeled btn-labeled-left">
						<b><i class="icon-arrow-left7"></i></b> Back To List
					</a>
				</div>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Master Data</a>
					<a href="javascript:void(0);" class="breadcrumb-item">Digital</a>
					<a href="{{ url('admin/master_data/digital/news') }}" class="breadcrumb-item">News</a>
					<span class="breadcrumb-item active">Update</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      @if($errors->any())
         <div class="alert bg-warning text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <ul>
               @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @elseif(session('success'))
         <div class="alert bg-success text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Success!</span> {{ session('success') }}
         </div>
      @elseif(session('failed'))
         <div class="alert bg-danger text-white alert-styled-left alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <span class="font-weight-semibold">Failed!</span> {{ session('failed') }}
         </div>
      @endif
		<div class="card">
			<div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data" id="form_data">
               @csrf
               <div class="row justify-content-center">
                  <div class="col-md-5">
                     <div class="form-group">
                        <div class="text-center">
                           <a href="{{ $news->image() }}" id="preview_image" data-lightbox="Image" data-title="Preview Image">
                              <img src="{{ $news->image() }}" class="img-fluid img-thumbnail w-100" style="max-width:500px;">
                           </a>
                           <p class="text-danger font-italic mt-3">
                              Maximum file size is <b>100KB</b> & the only files supported are <b>jpeg, jpg, png</b>
                           </p>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <div class="custom-file">
                              <input type="file" id="image" name="image" class="form-control h-auto" accept="image/x-png,image/jpg,image/jpeg" onchange="previewImage(this, '#preview_image')">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group"><hr></div>
               <div class="form-group">
                  <label>Title :<span class="text-danger">*</span></label>
                  <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" value="{{ old('title', $news->title) }}">
               </div>
               <div class="form-group">
                  <label>Category :<span class="text-danger">*</span></label>
                  <select name="category_id" id="category_id" class="select2">
                     <option value="">-- Choose --</option>
                     @foreach($category as $c)
                        <option value="{{ $c->id }}" {{ ((old('category_id') == $c->id) ? 'selected' : (($news->category_id == $c->id) ? 'selected' : '')) }}>{{ $c->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label>Tags :</label>
                  <select name="tags[]" id="tags" class="select2-tags" multiple>
                     @if(old('tags', $news->newsTags))
                        @foreach(old('tags', $news->newsTags) as $t)
                           <option value="{{ old('tags') ? $t : $t->tags }}" selected>{{ old('tags') ? $t : $t->tags }}</option>
                        @endforeach
                     @endif
                  </select>
               </div>
               <div class="form-group">
                  <label>Content :<span class="text-danger">*</span></label>
                  <textarea name="description" id="description" class="description">{!! old('description', $news->description) !!}</textarea>
               </div>
               <div class="form-group"><hr></div>
               <div class="form-group text-right">
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="2" {{ $news->status == 2 ? 'checked' : '' }}>
                        Draft
                     </label>
                  </div>
                  <div class="form-check form-check-inline">
                     <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="1" {{ $news->status == 1 ? 'checked' : '' }}>
                        Publish
                     </label>
                  </div>
               </div>
               <div class="form-group text-right">
                  <button type="reset" id="btn_reset" class="btn bg-danger btn-labeled btn-labeled-left">
                     <b><i class="icon-sync"></i></b> Reset Form
                  </button>
                  <button type="submit" id="btn_submit" class="btn bg-warning btn-labeled btn-labeled-left">
                     <b><i class="icon-pencil7"></i></b> Save
                  </button>
               </div>
            </form>
			</div>
		</div>
	</div>

<script>
   $(function() {
      ckEditor('description');

      $('#btn_submit').click(function() {
         $('#btn_reset').attr('disabled', true);
         $('#btn_submit').attr('disabled', true);
         $('#btn_submit').html('<b><i class="icon-spinner4 spinner"></i></b> Processed ...');
         $('#form_data').submit();
      });
   });
</script>