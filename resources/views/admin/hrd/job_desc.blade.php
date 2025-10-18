<div class="content-wrapper">
	<div class="page-header page-header-light sidebar-sticky">
		<div class="page-header-content header-elements-md-inline">
			<div class="page-title d-flex">
				<h4>
					<i class="icon-arrow-left52 mr-2"></i> 
					<span class="font-weight-semibold">HRD Job Desc</span>
				</h4>
			</div>
		</div>
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="{{ url('admin/dashboard') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
					<a href="javascript:void(0);" class="breadcrumb-item">HRD</a>
					<span class="breadcrumb-item active">Job Desc</span>
				</div>
			</div>
		</div>
	</div>
	<div class="content">
      <ul class="nav nav-pills nav-pills-bordered nav-justified">
         <li class="nav-item">
            <a href="#job_desc_surabaya" class="nav-link active" data-toggle="tab">Surabaya</a>
         </li>
         <li class="nav-item">
            <a href="#job_desc_jakarta" class="nav-link" data-toggle="tab">Jakarta</a>
         </li>
      </ul>
      <div class="tab-content">
         <div class="tab-pane fade show active" id="job_desc_surabaya">
            <div class="card-group-control card-group-control-right">
               @foreach($job_desc_sby as $jds)
                  <div class="card mb-2">
                     <div class="card-header">
                        <h6 class="card-title">
                           <a class="text-default collapsed" data-toggle="collapse" href="#job-{{ $jds->id }}">
                              {{ $jds->position() }}
                           </a>
                        </h6>
                     </div>
                     <div id="job-{{ $jds->id }}" class="collapse">
                        <div class="card-body">
							@if($jds->position == '2')
								<ul class="nav nav-tabs nav-tabs-solid nav-justified border-1" style="margin-bottom: 0rem;">
									<li class="nav-item"><a href="#job-desk" class="nav-link secretary-tab active" data-toggle="tab">Job Desk</a></li>
									<li class="nav-item"><a href="#karakter" class="nav-link secretary-tab" data-toggle="tab">Karakter</a></li>
									<li class="nav-item"><a href="#legal" class="nav-link secretary-tab" data-toggle="tab">Legal Document</a></li>
									<li class="nav-item"><a href="#akun" class="nav-link secretary-tab" data-toggle="tab">Akun Perwira</a></li>
								</ul>
								<ul class="nav nav-tabs nav-tabs-solid nav-justified border-1" style="margin-bottom: 0rem;">
									<li class="nav-item"><a href="#asuransi" class="nav-link secretary-tab" data-toggle="tab">Asuransi</a></li>
									<li class="nav-item"><a href="#kartu-kredit" class="nav-link secretary-tab" data-toggle="tab">Kartu Kredit</a></li>
									<li class="nav-item"><a href="#apec" class="nav-link secretary-tab" data-toggle="tab">Apec</a></li>
									<li class="nav-item"><a href="#tiket-sales-mkj" class="nav-link secretary-tab" data-toggle="tab">Tiket Sales MKJ</a></li>
									<li class="nav-item"><a href="#cea" class="nav-link secretary-tab" data-toggle="tab">CEA</a></li>
								</ul>
								<ul class="nav nav-tabs nav-tabs-solid nav-justified border-1" style="margin-bottom: 1.25rem;">
									<li class="nav-item"><a href="#trip" class="nav-link secretary-tab" data-toggle="tab">Trip Pak David & Ibu Sanny</a></li>
									<li class="nav-item"><a href="#gathering" class="nav-link secretary-tab" data-toggle="tab">Gathering Shanaya Resort</a></li>
									<li class="nav-item"><a href="#favorit" class="nav-link secretary-tab" data-toggle="tab">Makanan Favorit Pak David</a></li>
									<li class="nav-item"><a href="#tjs" class="nav-link secretary-tab" data-toggle="tab">TJS Untuk Admin Sales</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane fade show active" id="job-desk">
										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">JOB DESK :</span></span></strong></span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sekretaris PT Perwira Tamaraya Abadi memiliki tanggung jawab atas :</span></span></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Legal Document (Asli &amp; Scan) PT Perwira Tamaraya Abadi dan CV Perwira Indo Persada</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen pribadi (Asli &amp; Scan) keluarga Bpk David</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Asuransi keluarga Bpk David</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Seluruh Kartu kredit Bpk David dan Ibu Sanny</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Travel Document keluarga Bpk David, meliputi Paspor, Visa, dan APEC Business Travel Card</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akomodasi Trip Bpk David &amp; keluarga meliputi : Tiket Pesawat, Hotel, dan Itinerary</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akomodasi Trip Sales KMJ meliputi : Tiket Pesawat dan Hotel</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Makan siang Bpk David dan tamu </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Pekerjaan yang berkaitan dengan admin sales, meliputi : pembuatan kode produk, customer, Sales Order customer Perwira &amp; SMB Jakarta, dan middleman fee di TJS</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PIC komunikasi antara Team Perwira Surabaya dan SMB Jakarta</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Special event seperti ulang tahun baik keluarga Bpk David maupun rekan perusahaan</span></span></span></span></li>
										</ol>
									</div>
									<div class="tab-pane fade" id="karakter">
										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KARAKTER PAK DAVID :</span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cepat, Tepat, dan Detail</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">To the point, dan tidak suka dengan penjelasan yang berbelit</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tidak suka dengan sikap menunda pekerjaan</span></span></span></span></li>
										</ol>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KARAKTER BU SANNY :</span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Efektif dan Detail</span></span></span></span></li>
										</ol>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KARAKTER SEKRETARIS :</span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Inisiatif</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cepat tanggap</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Detail</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tidak menunda pekerjaan</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Mengerjakan segala sesuatu hingga tuntas</span></span></span></span></li>
											<li><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ramah dan komunikatif</span></span></li>
										</ol>

									</div>
									<div class="tab-pane fade" id="legal">
										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen asli disimpan dalam 1 Map, di meja sekretaris.</span></span></span></span><br></br></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dalam map tersebut berisi :</span></span></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen asli keluarga Bpk David</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Kelahiran Prawiro Tedjo Tjandra, Sanny Rahayu Tjandra, Keiko Tjandra, Hiro Tjandra</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Perkawinan untuk Suami dan Istri </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kartu Keluarga</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NPWP Prawiro Tedjo Tjandra, Sanny Rahayu</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Surat Keterangan Terdaftar Prawiro Tedjo Tjandra, Sanny Rahayu Tjandra</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ijazah SMA Prawiro Tedjo Tjandra</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SKCK (lama)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Nikah dan Penyerahan Anak (Gereja Bethel Indonesia)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Penetapan Pengadilan Ganti Nama untuk Prawiro Tedjo Tjandra dan Sanny Rahayu</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Surat Keterangan Domisili Prawiro Tedjo Tjandra dan Sanny Rahayu</span></span></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen CEA </span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Pendirian CEA</span></span></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen PT Perwira Tamaraya Abadi</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Pendirian dan Perubahan (SK Menkeh ada di dalam akta)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NIB</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SIUP</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NPWP </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SKT</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SPPKP </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Surat Domisili</span></span></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Dokumen CV Perwira Indo Persada</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Akta Pendirian dan Perubahan (SK Menkeh ada di dalam akta)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NIB</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SIUP</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NPWP</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SKT</span></span></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kontrak Kerjasama Be Rich</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sertifikat Poodle </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Surat&quot; Kendaraan</span></span></span></span></li>
										</ul>

										<p>&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Scan Dokumen Asli disimpan di :</span></span></span></span></p>

										<p><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Removable Disk E &gt; Legal Document &gt;</span></span></p>

									</div>
									
									<div class="tab-pane fade" id="akun">
										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Email</span></span></strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif"> : secretary.bravat@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Pass : Pahlawan007</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Admin Website SMB</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">smartmarbleandbath.com/admin/login</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : smartmarbleandbath@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : 123456</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Xendit Indovica</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : info@indovica.id</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : Pahlawan07</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Xendit SMB</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : secretary.bravat@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : Baliwerti119121</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Paypal Penampungan Indovica</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : finance@indovica.id</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : Indovica123</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Website Indovica</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email: user</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : 123456</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KADIN</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">user : perwiratamarayaabadi</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pw : Pahlawan07</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">WIKA</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : secretary.bravat@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : Pahlawan07</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">DBS Bu Sanny</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">username : Sannyrahayu</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Password : Hiro123</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Starhub Acc</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Email : secretary.bravat@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Password : Pahlawan07#</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Account Pulse Asuransi Prudential</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : sannyrahayu@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">password : @Hiro123</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PermataMobileX</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">username : davidprawiro74</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">password : Pahlawan07</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Huawei</span></span></strong></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : secretary.bravat@gmail.com</span></span></span></span><br />
										<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">pass : Pahlawan07!</span></span></span></span></p><br>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Marriot Bonvoy </span></span></strong></span></span><br />
										<span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Pass : 30Agustus1974#</span></span></p><br>

									</div>
									
									<div class="tab-pane fade" id="asuransi" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<ol>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Folder Asuransi :</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Removable Disk E &gt; Penting &gt; Asuransi &gt; Excel Rincian Asuransi Bu Sanny</span></span></span></span></p>

										<ol start="2">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk asuransi CUTI PREMI, Pembayaran biaya asuransi diambil dari Nilai Tunai. Nilai Tunai harus lebih besar dari biaya asuransi. Oleh karena itu, harus di Update tiap bulan untuk cek apakah nilai tunai mencukupi.</span></span></span></span><br><br></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cara Update Nilai Tunai tiap bulan :</span></span></span></span></li>
										</ol>

										<ul style="margin-left:40px">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Telpon call centre Prudential (1500085) atau Manulife (0800 1606060) </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Telepon atas nama Ibu Sanny, dengan data yang ditanyakan sebagai berikut :</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">No Polis : (bacakan sesuai nomor polis yang ditanyakan)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Nama Lengkap : Sanny Rahayu Tjandra</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tempat Tanggal Lahir : Surabaya, 11 Desember 1976</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">No Telepon : 0811-3629-990</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Email : sannyrahayu@gmail.com</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Alamat : Villa Bukit Regency I PC 10/06 Surabaya</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Nama Ibu Kandung : Liliana Probo Tedjoisworo</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Nama Ayah Kandung : Boenarto Tedjoisworo</span></span></span></span></li>
										</ul>

										<ol start="4">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Jangan sampai Nilai Tunai tidak mencukupi untuk membayar Biaya Asuransi. Apabila tidak mencukupi maka polis asuransi akan mati/lapsed, dan polis tersebut tidak akan bisa mengcover tertanggung utama +- selama 1 tahun.</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh kasus : Nilai Tunai per bulan Januari 4 juta, biaya asuransi per bulan 2 juta. WAJIB ingatkan Bu Sanny untuk TOP UP Nilai Tunai. Karena Nilai Tunai sudah sangat mepet untuk pendebitan biaya asuransi bulan Februari. Top Up dikenai biaya 5%. Setelah Top Up harap konfirmasi ke agen yang bersangkutan</span></span></span></span></p>

										<ol start="5">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cara TOP UP via m-banking BCA :</span></span></span></span></li>
										</ol>

										<ul style="margin-left:40px">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">M-Payment / Pembayaran</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Asuransi</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Nama Perusahaan : Prudential</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Input Kode Bayar&nbsp; + No Polis (Kode Bayar Top Up : 7252) </span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh : 725221105835</span></span></span></span></li>
										</ul>

										<ol start="6">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Aplikasi PULSE by Prudential</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">email : sannyrahayu@gmail.com</span></span></span></span></p>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">password : @Hiro123</span></span></span></span></p>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">masuk ke bagian &quot;Polisku&quot; untuk melihat semua asuransi Prudential Bu Sanny</span></span></p>

									</div>
									<div class="tab-pane fade" id="kartu-kredit" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<ol>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Folder Kartu Kredit :</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Removable Disk E &gt; Secre Folder &gt; Main Job &gt; Kartu Kredit &gt; Excel Kartu Kredit Aktif</span></span></span></span></p>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sheet 1 : berisi data Kartu Kredit Pak David dan Bu Sanny</span></span></span></span></p>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sheet&nbsp; 2 : berisi data Pak David dan Bu Sanny untuk keperluan verifikasi ketika telepon call centre</span></span></span></span></p>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*abaikan Sheet yang lain.</span></span></span></span></p>

										<ol start="2">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Billing seluruh kartu kredit akan dikirimkan via email : secretary.bravat@gmail.com</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ketika menerima billing, hal yang perlu dilakukan :</span></span></span></span></p>

										<ul style="margin-left:40px">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cek berkala dan tukarkan poin kartu kredit yang akan kadaluarsa</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Print 1x untuk ditunjukkan ke Pak David, tiap transaksi diberi keterangan terlebih dahulu</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Print out tsb digunakan untuk file-ing pribadi di bantex kartu kredit warna hitam</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Upload File Billing ke TJS &gt; Finance &gt; Purchase Request &gt; Beri Keterangan</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh Keterangan : BILLING KK BNI 4665 7309 0002 0504 (Tgl Cetak : xx Nov 22, Jatuh Tempo : xx Des 22)</span></span></span></span></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*Apabila ada nota&quot; pengeluaran dari Pak David, di cek apabila pengeluaran via KK, di jepret di belakang print out billing. Apabila dari kartu debit, nota diberikan ke Pak Riyan.</span></span></span></span></li>
										</ul>

										<ol start="3">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila ada :</span></span></span></span></li>
										</ol>

										<ul style="margin-left:40px">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Annual Fee /Iuran Tahunan</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Membership Fee</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Overlimit Fee</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Late Payment Charge</span></span></span></span><br />
											<span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Harus dihapuskan dengan cara telpon call centre KK yang bersangkutan. Jangan lupa untuk meminta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; kode pelaporan sebagai bukti telah mengajukan penghapusan.</span></span></span></span></li>
										</ul>

										<ol start="4">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Lihat masa aktif kartu kredit. Apabila sudah mendekati expired, telpon call centre KK untuk request kartu baru. Setelah itu diaktifkan melalui hp Pak David.</span></span></span></span><br><br></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Yang perlu dipersiapkan untuk verifikasi KK ketika telepon via call centre</span></span></span></span></li>
										</ol>

										<ul>
											<li style="margin-left: 48px; text-align: justify;"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">No Kartu Kredit, Atas Nama, Masa Aktif Kartu, Kartu Tambahan Atas Nama? </span></span></span></span></li>
											<li style="margin-left: 48px; text-align: justify;"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Pembayaran Terakhir via apa dengan nominal berapa?</span></span></span></span></li>
											<li style="margin-left: 48px; text-align: justify;"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Data kartu kredit dari sheet 2.</span></span></li>
										</ul>

										<div class="text-center">
											<img src="{{ url('website/screenshot_2022-03-30_084902.png') }}" width="200px">
										</div>
									</div>
									<div class="tab-pane fade" id="apec" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<ol>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Folder APEC : </span></span></span></span></li>
										</ol>

										<p style="margin-left:54px; text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Removable Disk E &gt; APEC </span></span></span></span></p>

										<ol start="2">
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">APEC : Kartu&nbsp;Perjalanan Pebisnis (khususnya Direktur) &nbsp;yang berlaku di negara-negara anggota&nbsp;APEC (19 negara).</span></span></span></span><br><br></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Masa berlaku Kartu APEC Pak David (biasanya) beriringan dengan masa berlaku Paspor, karena pengajuannya bersamaan. Namun, paspor terbaru Pak David sudah diperpanjang terlebih dahulu, dan Kartu APEC masih dalam proses karena saat itu Australia sebagai negara yang mencetak kartu APEC lockdown. </span></span></span></span><br><br></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Saat ini dalam tahap perpanjangan kartu APEC dibantu oleh Ibu Ika (0812-9082-1689), tim dari Ibu Rami (0816-1910-525). Sudah bisa perpanjangan karena kartu APEC tidak lagi ada fisiknya (virtual/online melalui aplikasi ABTC)</span></span></span></span><br><br></li>
											<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tahapan Perpanjangan Kartu APEC :</span></span></span></span>
											<ul>
												<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kumpulkan semua berkas yang dibutuhkan untuk diserahkan ke Dirjen Imigrasi (Removable Disk E &gt; APEC &gt; Email Ibu Ika &gt; Checklist). Berkas Pak David dan Bu Sanny sudah saya sendirikan di masing&quot; folder. Berkas P. David yang kurang (SKCK dan Surat Rekomendasi dari Bank). Berkas B. Sanny yang kurang (SKCK, Pas Foto, Surat Rekomendasi Bank, Scan Paspor lama sebagai bukti perjalanan ke luar negri).</span></span></span></span></li>
												<li style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila Berkas sudah disubmit ke Dirjen Imigrasi melalui Ibu Ika (Rami Team), maka kita wajib bayar Rp. 6.000.000 / orang untuk biaya administrasi.</span></span></span></span></li>
												<li style="text-align:justify"><span style="font-size:12.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Menunggu approval dari 19 negara.</span></span></li>
											</ul>
											</li>
										</ol>

									</div>
									<div class="tab-pane fade" id="cea" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<p>1. Folder CEA : Removable Disk E &gt; CEA</p>
										<p>2. Organisasi Marketing yang diketuai oleh Pak David</p>
									</div>
									<div class="tab-pane fade" id="tiket-sales-mkj" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<p>Anggota Sales MKJ :</p>

										<ol>
											<li>Andre Sunyoto (P. Lukas) &ndash; Sales KM Jakarta (082348309709)</li>
											<li>Syamsul Arifin &ndash; Sales KM Surabaya (085106236788)</li>
											<li>Singgih Witanto &ndash; Sales KM Surabaya (081216130230)</li>
											<li>Ade Wirawan &ndash; Sales KM Jawa Tengah (085799899919)</li>
										</ol>

										<p>Job Desk :</p>

										<ol>
											<li>Pemesanan Tiket Pesawat</li>
											<li>Pemesanan Hotel Nite &amp; Days</li>
											<li>Pemesanan Hotel Eden, Kuta, Bali</li>
										</ol>

										<p>Langkah memesanan Tiket Pesawat / Hotel Nite &amp; Days:</p>

										<ol>
											<li>Pesan tiket di Traveloka</li>
											<li>Pembayaran tiket dengan Kartu Kredit Pak Henry Suhartono (081338189672)</li>
											<li>Membuat pengajuan pembayaran tiket ke cik Elly, format ada di file : Removable disk E &gt; Tiket &gt; Pilih salah satu folder sales, dan buka file word.
											<ul>
												<li>Tanggal jatuh tempo ditulis 1 bulan setelah tanggal issued tiket.</li>
												<li>nomor AC yang dicantumkan, disesuaikan dengan kartu kredit yang dipakai saat pembelian tiket</li>
											</ul>
											</li>
											<li>Lembar pengajuan, tiket pesawat, dan receipt di print, dan dimasukkan ke map orange yang ada di ruangan marketing MKJ untuk di ACC Cik Elly.</li>
											<li>Keesokan harinya setelah di ACC Cik Elly, ketiga dokumen di serahkan ke ce Suhsing di lantai 5 untuk reimbursement ke Pak Henry.</li>
										</ol>

										<p>Cara Pemesanan Hotel Eden Kuta Bali :</p>

										<ol>
											<li>Isi Free Stay Owner&rsquo;s Reservation Request Form (file ada di folder Tiket)</li>
											<li>Detail :
											<ul>
												<li>Owner&rsquo;s Name : Oei Lie Tjien</li>
												<li>Owner&rsquo;s Email : secretary.bravat@gmail.com</li>
												<li>Unit Type : Eden Suite</li>
												<li>Unit Number : 1321</li>
											</ul>
											</li>
											<li>Form di email ke : freestayownerunit@gmail.com, cc : edenreservation@myedenhotel-kuta-bali.com, salesdept@myedenhotel-kuta-bali.com</li>
											<li>Apabila terjadi pembatalan reservasi, harap segera konfirmasi pihak hotel agar sisa hak tinggal (poin) tidak berkurang.</li>
										</ol>

									</div>
									<div class="tab-pane fade" id="trip" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<p style="margin-left:40px"><strong>Pemesanan Tiket Pesawat / Hotel melalui Rodex</strong></p>

										<ol>
											<li>PIC Rodex yang mengurus tiket Pak David &gt; Intan (0857-8447-3037)<br><br></li>
											<li>Pak David pernah punya kredit di Rodex sebesar 20 juta dan sudah dipergunakan habis untuk trip Bali, dan pembelian tiket2 pesawat dengan rincian sebagai berikut :</li>
										</ol>

										<p>
										<strong>PERSIAPAN TRIP PAK DAVID &amp; BU SANNY</strong></p>

										<ol>
											<li>Pastikan sudah memesan tiket pesawat pulang pergi, hotel, dan menyiapkan itinerary bila diperlukan<br><br></li>
											<li>Untuk tiket pesawat, H-1 sebelum keberangkatan wajib untuk web-check in terlebih dahulu, dan pilih seat Aisle, barisan terdepan.<br><br></li>
											<li>Kirimkan rincian tiket pesawat dan e-boarding pass dengan format sebagai berikut :<br />
											<b>Batik Air 6585<br />
											Surabaya (Terminal 1A, Gate 10) &gt; Jakarta<br />
											Senin, 21 Maret 2022<br />
											Pukul 10.10 - 11.40<br />
											Boarding Time 09.40<br />
											Seat 18D dan 18E</b><br><br></li>
											<li>Kirimkan juga rincian hotel dengan rincian sebagai berikut :</li>
										</ol>

										<b><p style="margin-left:40px">HOTEL<br />
										Four Point Jakarta, Thamrin<br />
										Room Free Upgrade : 1 King Bed, Non Smoking, City View (No Breakfast)<br />
										Check in : Senin, 21 Maret 2022 (15.00)<br />
										Check Out : Jumat, 25 Maret 2022 (12.00)<br />
										Confirmation Number : 99560865<br />
										*Untuk pemesanan hotel melalui Marriot Bonvoy : dapat fasilitas free upgrade / free<br />
										Breakfast karena Membership Pak David : Platinum Elite<br />
										*Untuk pemesanan hotel melalui Marriot Vacation Club (MVC) bisa dapat free lounge<br />
										(tapi tergantung kebijakan hotel juga)</p></b>
										
										<p><strong>UNTUK SPECIAL TRIP (HOLIDAY/KE LUAR NEGERI)</strong></p>
										<p>1. Contoh Pembuatan Itinerary</p>

										<p><img alt="" src="https://smartmarbleandbath.com/website/Screenshot 2022-03-30 091912.png" style="height:247px; width:600px" /><br /><br>
										*Harus ada jam yang jelas, penerbangan menggunakan maskapai apa, menginap di hotel apa by agent apa<br />
										*Harus ada pilihan tempat rekreasi/tempat makan, dan disesuaikan juga dengan jarak tempuh ke hotel.<br /><br>
										2. Merincikan Pilihan Hotel dari beberapa Tour Agent (Rodex / Nelly Tour : 0812 3128 9668) dan<br />
										menyesuaikan dengan budgeting.<br />
										<img alt="" src="https://smartmarbleandbath.com/website/file_secret.jpeg" style="height:830px; width:585px" /></p>

										<p>&nbsp;</p>

									</div>
									<div class="tab-pane fade" id="gathering" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<ul>
											<li>Rencana Gathering untuk Tim Perwira Surabaya dan Jakarta (SMB Jakarta).</li>
											<li>Lokasi gathering di Shanaya Resort, Karangploso, Malang</li>
											<li>Sales Shanaya yang menangani PT Perwira : Alny Hairany Harahap (0896 3309 5403)</li>
											<li>File kesepakatan DP dan kunci harga untuk tahun 2022 ada di folder Tiket &gt; Shanaya Resort<br />
											<img alt="" src="https://smartmarbleandbath.com/website/file_secret_2.jpeg" style="height:183px; width:600px" /></li>
											<li>*Harga di atas belum termasuk biaya outbound</li>
										</ul>

										<p>Pembagian Kamar :</p>

										<ul>
											<li>1 Unit Singhasari Villa : Pak David &amp; Family</li>
											<li>1 Unit Singhasari Villa : Pak Anton &amp; Family</li>
											<li>1 Unit Ranggawuni Glamping 4 Bed : Serista, Caroline, Vero, Bella</li>
											<li>1 Unit Ranggawuni Glamping 3 Bed : Ismi, Reni, Santhi</li>
											<li>1 Unit Ranggawuni Glamping 3 Bed : Windy, Ryan, Edwin</li>
										</ul>

										<p>Pada tanggal 14 Januari, melakukan pembayaran DP 50% sebesar Rp. 9.860.000. Pelunasan dilakukan<br />
										di Shanaya Resort pada saat menginap.</p>

										<p><img alt="" src="https://smartmarbleandbath.com/website/file_secret_3.jpeg" style="height:342px; width:230px" /></p>

									</div>
									<div class="tab-pane fade" id="favorit" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<p><img alt="" src="https://smartmarbleandbath.com/website/file_secret_4.png" style="height:633px; width:593px" /></p>
									</div>
									<div class="tab-pane fade" id="tjs" style="font-family:&quot;Times New Roman&quot;,serif;font-size:12.0pt;">
										<p><img alt="" src="https://smartmarbleandbath.com/website/Screenshot 2022-03-30 094225.png" style="height:255px; width:453px" /></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">Akun TJS PERWIRA</span></span></span></strong><strong> </strong></span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </span></span><a href="mailto:secretary.bravat@gmail.com" style="color:blue; text-decoration:underline"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">secretary.bravat@gmail.com</span></span></a></span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Password&nbsp;&nbsp;&nbsp; : Pahlawan07</span></span></span></span></p>

										<p>&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">Akun TJS SMB JKT</span></span></span></strong></span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Email&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </span></span><a href="mailto:eva.smartmarble@gmail.com" style="color:blue; text-decoration:underline"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">eva.smartmarble@gmail.com</span></span></a></span></span></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Password&nbsp;&nbsp;&nbsp; : Pahlawan07</span></span></span></span></p>

										<p style="text-align:center">&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><a href="http://WWW.smartmarbleandbath.com/ADMIN/LOGIN" style="color:blue; text-decoration:underline"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">WWW.smartmarbleandbath.com/ADMIN/LOGIN</span></span></strong></a></span></span></p>

										<p>&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">CARA PEMBUATAN MASTER DATA PRODUK DI TJS</span></span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Master Data &gt; Product &gt; Product Type &gt; Add</span></span></span></span><br><br></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk <strong>Product Type</strong>, isi dari kolom Data &gt; Specification &gt; Stock &gt; Image, setelah itu Klik SAVE di pojok kanan bawah</span></span></span></span></li>
										</ol>

										<p style="margin-left:48px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*di kolom <strong>DATA</strong>, yang perlu diisi :</span></span></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Category</span></span></strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif"> : </span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">sesuaikan dengan kategori produk yang akan di input </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Bedakan kategori Keramik, Granite, dan Mosaic</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Granite, bedakan jenis granit matt (GVT : Glazed Vitrified Tile) atau polish (PGVT : Polish Glazed Tile)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Sanitary, bedakan antara Wastafel, Urinal, Closet, Bathub, Bathroom Equipment, Fitting, dll.</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Code : </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tulis nama produk yang akan diinput disini</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Faces :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Motif di permukaan granite ada berapa</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh : Statuario Smart Continue A B C D (4 faces)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh : Calacatta Bookmatch A B (2 Faces)</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Division :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk produk Perwira menggunakan Divisi Karya Modern, Untuk SMB JKT menggunakan divisi SMARTMARBLE</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Surface :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sesuaikan dengan permukaan tile</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Big Slab&nbsp; (uk 75x150, 90x180, 120x120, 120x240) &gt; Flat Polished </span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Color dan Pattern : </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sesuaikan dengan warna dan pattern tile yang diinput</span></span></span></span></li>
										</ul>

										<p>&nbsp;</p>

										<p style="margin-left:57px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*Move ke Kolom <strong>SPECIFICATION :</strong></span></span></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Material : </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sesuaikan dengan material low, middle, atau high</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Contoh : Ikad termasuk Low</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Loading Limit :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau Tile &gt; Tonnage, Kalau Sanitary &gt; Cubic</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Length, Width, dan Weight </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ikuti petunjuk table di atas untuk mengisi ukuran dan berat tile</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Thickness</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Keramik dan Granite Tile ketebalan 9 mm</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Big Slab ada yang ketebalannya 9 mm dan 12 mm</span></span></span></span></li>
										</ul>

										<p style="margin-left:144px">&nbsp;</p>

										<p style="margin-left:57px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*Move ke Kolom <strong>STOCK :</strong></span></span></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Buying Unit, Stock Unit, dan Selling Unit :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk tile, buying unit dalam bentuk box</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kecuali untuk sample (pcs), buying unit dalam bentuk pcs</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Sanitary buying unit bisa dalam bentuk pcs atau set</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Need to Stock : No</span></span></strong></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Small Stock , Min Stok, Max Stock : 0</span></span></strong></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Conversion : 1</span></span></strong></span></span></li>
										</ul>

										<p style="margin-left:96px">&nbsp;</p>

										<p style="margin-left:57px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*</span></span></strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Move ke <strong>Image :</strong></span></span></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Input gambar </span></span></span></span></li>
										</ul>

										<p style="margin-left:57px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">*<strong>SAVE di Pojok Kanan Bawah</strong></span></span></span></span></p>

										<p style="margin-left:57px">&nbsp;</p>

										<ol start="3">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Setelah Product Type berhasil dibuat, sekarang beralih ke <strong>SMB Item Code</strong></span></span></span></span></li>
										</ol>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Klik <strong>ADD</strong></span></span></span></span></li>
										</ul>

										<p style="margin-left:96px">&nbsp;</p>

										<p style="margin-left:96px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KOLOM DATA</span></span></strong></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Type</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ketik nama produk sesuai dengan yang ditulis di Code (Product Type) dan akan auto-generate</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">HS Code : </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">(6907.22.93) Glazed &gt; untuk Polished Tile</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">(6910.10.00) - &gt; untuk Sanitary</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">(6907.41.92)</span></span><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif"> Unglazed &gt; untuk Unpolished Tile</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Company :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk produk Perwira, pilih yang produk PTA HIGH / PTA LOW</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk produk SMB, pilih yang produk SMB HIGH / SMB LOW</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Brand, Country, dan Supplier :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Isi sesuai dengan ketentuan produk yang akan di input</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Grade :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KW 1 </span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KW 2 &gt; N</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KW 3 &gt; NN</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Check :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Already Checked</span></span></span></span></li>
										</ul>

										<p style="margin-left:104px">&nbsp;</p>

										<p style="margin-left:104px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KOLOM STOCK</span></span></strong></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Pcs / carton :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Jumlah tile dalam 1 kardus</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Misalkan granite uk 60x60 &gt; 4 pcs/carton</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Carton / pallet : </span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 20x20 &gt; 60 carton/pallet</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 25x40 &gt; 65 carton/pallet</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 60x60 &gt; 40 carton/pallet</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 80x80 &gt; 28 carton/pallet</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 60x120 &gt; 30 carton/pallet</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 100x100 &gt; 15 carton/pallet</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Stock Unit :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 60x120 &gt; 750 stok unit/container</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 60x60 &gt; 900 stok unit/container</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 20x20 &gt; 1000 stok unit/ container</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 80x80 &gt; 590 stok unit/container</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Uk 100x100 &gt; 470 stok unit/container</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Standart Container :</span></span></strong></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">200 ft</span></span></span></span></li>
										</ul>

										<p style="margin-left:95px">&nbsp;</p>

										<p style="margin-left:95px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KOLOM SHADE</span></span></strong></span></span></p>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Bertujuan untuk input stock dari ventura ke TJS </span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Warehouse : </span></span></strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kode Gudang</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Ventura &nbsp;: </span></span></strong></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Remote Desktop &gt; Ventura</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Username Ventura : stok, Password : 123</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Report &gt; Stock List &gt; Search di Item Name</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Masukkan No Item di kolom paling kiri</span></span></span></span></li>
										</ul>

										<ul>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Code :</span></span></strong></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Masukkan shading (kolom ketiga di ventura)</span></span></span></span></li>
											<li><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">ADD NEW lalu SAVE</span></span></strong></li>
										</ul>

										<p><img alt="" src="https://smartmarbleandbath.com/website/file_secret_5.png" style="height:335px; width:720px" /></p>

										<p><img alt="" src="https://smartmarbleandbath.com/website/file_secret_6.png" style="height:214px; width:720px" /></p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">CARA PEMBUATAN MASTER DATA CUSTOMER LIST DI TJS</span></span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Master Data &gt; Customer List</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Yang Penting untuk Diisi :</span></span></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Nama Customer</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Alamat</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">No Telepon</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Tipe Customer : Offline</span></span></span></span></li>
										</ul>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk customer berppn, wajib mengisi NPWP dan Alamat sesuai NPWP</span></span></span></span></li>
										</ol>

										<p>&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">CARA PEMBUATAN SALES ORDER</span></span></span></strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila customer Perwira, gunakan username : </span></span><a href="mailto:secretary.bravat@gmail.com" style="color:blue; text-decoration:underline"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">secretary.bravat@gmail.com</span></span></a><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">, Password : Pahlawan07, akun Shania Hartono</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila customer SMB JKT, gunakan username : </span></span><a href="mailto:eva.smartmarble@gmail.com" style="color:blue; text-decoration:underline"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">eva.smartmarble@gmail.com</span></span></a><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">, Password : Pahlawan07, akun Shania SMB</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Klik Sales &gt; Project &gt; Add</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">ISI FORM <strong>PROJECT INFORMATION (10%)</strong> :</span></span></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Project Name : Nama Project</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Customer : Nama Customer (atas nama pribadi / PT)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Country : Indonesia</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">City : Kota Proyek / Lokasi Barang Dikirim</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Timeline : Tanggal dimana barang harus dikirim</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PIC : Nama Customer / PIC Pemesanan Produk</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Consultant Name / Owner boleh ditulis none apabila tdk tahu namanya</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Bank Destination disesuaikan dengan cabang SBY/JKT dan BERPPN/TDK</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Supply Method : Barang dikirim full atau Partial</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Payment Method : Pembayaran Cash / Kredit</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Detail Payment : kalau cash (sebelum atau sesudah barang dikirim), kalau kredit (...pilihan bisa dilihat di TJS)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Term Payment : kalau customer bayar langsung saat itu juga, pilih saja default</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Discount diisi apabila ada potongan harga setelah pesanan ditotal (Discount dibawah dari total pesanan, bukan per item barang)</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PPN : customer ber ppn atau tdk.</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SAVE</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kita akan diarahkan kembali ke halaman utama, apabila ada revisi untuk poin&rdquo; di atas, klik tombol biru, untuk meneruskan isi SO, klik tombol orange (progress)</span></span></span></span></li>
										</ul>

										<ol start="5">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sales Activity Notes :</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila sales ada penawaran produk dan harga, bisa di tulis progressnya dengan mencantumkan bukti foto quotation, kemudian klik Add</span></span></span></span></li>
										</ul>

										<ol start="6">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PROJECT MATERIAL SPECIFICATION / SPEC PROJECT (15%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Input produk yang akan dipesan</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Input Area : misalkan granite untuk area Ruang Tamu, Sanitary untuk area kitchen/kamar mandi</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Tile harus konversikan dalam bentuk meter, jadi unit nya diganti meter (jangan box)</span></span></span></span></li>
										</ul>

										<p style="margin-left:96px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Jadi misal : customer pesan granite tile uk 60x60, 5 dos =</span></span></span></span></p>

										<p style="margin-left:96px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">1 box granite tile uk 60x60 (isi 4 pcs) = 1,44 meter </span></span></span></span></p>

										<p style="margin-left:96px"><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">JADI 1,44 x 5 = <strong>7,2 meter</strong></span></span></span></span></p>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk Sanitary, sesuaikan dengan pesanan dalam bentuk PCS atau SET</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SAVE &amp; NEXT</span></span></strong></span></span></li>
										</ul>

										<ol start="7">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">CONSULTANT MEETING (20%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Progress sales dengan customer yang bersangkutan, misalkan pada tanggal 28 Maret, dengan Ibu Annesh, Turun Purchase Order (PO).</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">ADD, lalu SAVE &amp; NEXT</span></span></strong></span></span></li>
										</ul>

										<ol start="8">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">QUOTATION (25%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Masukkan harga di kolom BEST PRICE.</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk tile, harga yang dimasukkan harus <strong>Harga per Meter</strong></span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SAVE &amp; NEXT</span></span></strong></span></span></li>
										</ul>

										<ol start="9">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">FORM SAMPLE (30%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Isi form ini apabila customer minta sample produk</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Cek, sample yang diinginkan ukuran utuh atau potongan 20x20</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SAVE &amp; NEXT</span></span></strong></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila tidak minta sample, bisa di <strong>SKIP</strong></span></span></span></span></li>
										</ul>

										<ol start="10">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">NEGOTIATION PROGRESS REPORT (35%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Progress sales dengan customer yang bersangkutan</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila ada progress, isi kemudian di <strong>SAVE &amp; NEXT </strong></span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau progress sama dengan sebelumnya, bisa di <strong>SKIP</strong></span></span></span></span></li>
										</ul>

										<ol start="11">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SO PROJECT (37%)</span></span></strong></span></span></li>
										</ol>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Sales : </span></span></span></span></li>
										</ul>

										<ul style="list-style-type:square">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Perwira : Ismi Suryaningthyas / Reni Megawati</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SMB JKT : Wini Hapsari</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Berlian Indah : Elisabeth</span></span></span></span></li>
										</ul>

										<ul style="list-style-type:circle">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Customer PO : Masukkan bukti Purchase Order Customer dari Sales</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Note : Diisi Nama PIC / Penerima dan No Telepon Penerima</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Apabila ada middleman fee, delivery cost, atau biaya potong,dll bisa diisi di Other Cost</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SAVE &amp; NEXT</span></span></strong></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">See All Sales Order</span></span></strong></span></span></li>
										</ul>

										<ul style="list-style-type:square">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">PO Customer : Untuk lihat folder PO yang sudah kita upload</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SO Product : File SO Product</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">SO Service : File SO untuk biaya kirim, biaya pack, middleman fee, dll</span></span></span></span></li>
										</ul>

										<ol start="12">
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">KALAU </span></span></strong><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">ada editan harga atau spec produk, perbaharui di <strong>SPEC PROJECT</strong>, lalu klik <strong>See All Sales Order</strong> &gt; Edit, ketik perubahan, jangan lupa <strong>TULIS REASON</strong> kenapa ada revisi, baru <strong>SAVE &amp; NEXT</strong></span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Mintakan ACC ke Pak Ryan dan Bu Ismi</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau sudah di ACC, Klik <strong>SKIP </strong>di <strong>DOWN PAYMENT (40%)</strong></span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau progress nya sudah 40%, sudah selesai dan bisa dilanjutkan ke VERO.</span></span></span></span></li>
										</ol>

										<p>&nbsp;</p>

										<p><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><strong><span style="font-size:14.0pt"><span style="background-color:yellow"><span style="font-family:&quot;Times New Roman&quot;,serif">REIMBURSEMENT dan PENGAJUAN MIDDLEMAN FEE</span></span></span></strong><strong> </strong></span></span></p>

										<ol>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Finance &gt; Purchase Request &gt; Klik Tanda <strong>Bags </strong>di sebelah foto profil kita &gt; ADD</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Untuk middleman fee dari Jakarta, mintakan berita acara komisi dari SMB JKT (Caroline / Serista), file tersebut di upload ke purchase request untuk pengajuan pembayaran komisi ke Bu Shanty</span></span></span></span></li>
											<li><span style="font-size:11pt"><span style="font-family:Calibri,sans-serif"><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau middleman fee dari Surabaya, file nya ada di TJS, di <strong>See All Sales Order, </strong>tidak perlu di upload ulang di purchase request</span></span></span></span></li>
										</ol>

										<p><span style="font-size:14.0pt"><span style="font-family:&quot;Times New Roman&quot;,serif">Kalau reimbursement, tuliskan untuk kebutuhan apa dan upload bukti nota pembayaran</span></span></p>

									</div>
								</div>
							@else
								{!! $jds->job !!}
							@endif
							
                        </div>
                     </div>
                  </div>
               @endforeach
            </div>
         </div>
         <div class="tab-pane fade" id="job_desc_jakarta">
            @foreach($job_desc_jkt as $jdj)
               <div class="card mb-2">
                  <div class="card-header">
                     <h6 class="card-title">
                        <a class="text-default collapsed" data-toggle="collapse" href="#job-{{ $jdj->id }}">
                           {{ $jdj->position() }}
                        </a>
                     </h6>
                  </div>
                  <div id="job-{{ $jdj->id }}" class="collapse">
                     <div class="card-body">
                        {!! $jdj->job !!}
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
	</div>
	<script>
		$(function() {
			$('.secretary-tab').on('click', function() {
				$('.secretary-tab').each(function() {
					$(this).removeClass('active');
				});
			});
		});
	</script>