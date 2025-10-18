<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Surat Penawaran Harga {{ $data->code }}</title>
		<style>
			body {
				font-family: "Times New Roman", Times, serif;
			}
			
			th {
				font-size:18px;
			}
		
			.invoice-box {
				font-size: 20px;
				font-family: "Times New Roman", Times, serif;
				color: #555;
				page-break-after: always;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			@media print {
				@page {size: A4 portrait; }
			}

			.invoice-box.rtl {
				direction: rtl;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
			
			@page { margin: 1cm; }
			body { margin: 1cm; }
			
			input[type=checkbox] {
			  accent-color: white;
			  color:black;
			  outline: 1px solid black;
			}
		</style>
	</head>
	<body onload="window.print()">
		<div class="invoice-box" style="font-size:22px !important;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="text-align:center;" colspan="2">
						<img src="{{ url('website/logo_al_rev_3.jpg') }}" width="100%" height="auto">
					</td>
				</tr>
				<tr>
					<td width="50%">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="30%">Nomor</td>
								<td style="text-align:left !important;">: {{ $data->code }}</td>
							</tr>
							<tr>
								<td width="30%">Perihal</td>
								<td style="text-align:left !important;">: Permohonan Jaminan Pelaksanaan</td>
							</tr>
						</table>
						<br>
						Kepada
						<br>
						Yth. {{ $data->to_whom }}
						<br>
						{{ $data->company }}
						<br>
						Di {{ ucwords(strtolower($data->city->name)) }}
					</td>
					<td width="50%">
						Surabaya, {{ App\Helper\SMB::tgl_indo($data->date) }}
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						<p>
							Dengan hormat,
						</p>
						<p>Yang bertanda tangan di bawah ini:</p>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="25%">Nama Perusahaan</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">PT PERWIRA TAMARAYA ABADI</td>
							</tr>
							<tr>
								<td width="25%">Alamat</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									Pergudangan Bumi Maspion Blok IX E-1<br>
									Romokalisari Benowo Surabaya
								</td>
							</tr>
							<tr>
								<td width="25%">Nomor Rekening</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ $data->no_rekening }}
								</td>
							</tr>
						</table>
						<p>Dengan ini mengajukan permohonan Jaminan Pelaksanaan untuk:</p>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="25%">Pekerjaan</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">{{ $data->alProject->name }}</td>
							</tr>
							<tr>
								<td width="25%">Ditujukan Kepada</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									Kepala {{ $data->alProject->alCustomer->name }}
								</td>
							</tr>
							<tr>
								<td width="25%">Alamat</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ $data->alProject->alCustomer->address }}
								</td>
							</tr>
							<tr>
								<td width="25%">Nilai Jaminan Penawaran</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									Rp {{ number_format($data->nominal,0,',','.') }},-
								</td>
							</tr>
							<tr>
								<td width="25%">Terbilang</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ App\Helper\SMB::terbilang($data->nominal) }} Rupiah
								</td>
							</tr>
							<tr>
								<td width="25%">Masa Berlaku</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ $data->period }} Hari Kalender
								</td>
							</tr>
							<tr>
								<td width="25%">Tanggal Terbit</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ App\Helper\SMB::tgl_indo($data->date) }}
								</td>
							</tr>
							<tr>
								<td width="25%">Terhitung dari tanggal terbit</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ App\Helper\SMB::tgl_indo(date('Y-m-d', strtotime($data->date . " + ".$data->period." days"))) }}
								</td>
							</tr>
						</table>
						<p>
							Demikian surat permohonan jaminan pelaksanaan ini kami buat. Atas perhatian Bapak/Ibu, kami sampaikan terima kasih.
						</p>
					</td>
				</tr>
				<tr>
					<td style="text-align: justify;">
						&nbsp;
					</td>
					<td colspan="2" style="text-align: center;">
						Hormat kami,
						<br>
						<b>PT. PERWIRA TAMARAYA ABADI</b>
						<br><br><br><br><br>
						<b>Prawiro Tedjo Tjandra</b>
						<br>Direktur
					</td>
				</tr>
			</table>
		</div>
		<div class="invoice-box" style="font-size:12px !important;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="text-align:right;" colspan="2">
						<img src="{{ url('website/logo_bank_mandiri.png') }}" width="auto" height="30px">
						<br>
						<b>APLIKASI PENERBITAN GARANSI</b>
						<br><i>APPLICATION for GUARANTEE ISSUANCE</i>
					</td>
				</tr>
				<tr>
					<td width="50%">
						Kepada : {{ $data->company }}
						<br><i>To</i>
					</td>
					<td width="50%">
						No. Aplikasi : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: justify;">
						<table cellpadding="5" cellspacing="0" width="100%" border="1">
							<tr>
								<td style="text-align:center;">
									<b>APLIKASI BILA DITANDATANGANI PEMOHON & DITANDATANGANI BANK BERLAKU SEBAGAI PERJANJIAN PENERBITAN GARANSI</b>
									<br><i>This Application when signed by Applicant and acknowledged by Bank is deemed to be the Guarantee Issuance Agreement</i>
								</td>
							</tr>
							<tr>
								<td>
									<b>A.  KOLOM UNTUK PEMOHON/</b><i>to be filled in by Applicant</i>
								</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<table cellpadding="5" cellspacing="0" width="100%">
										<tr>
											<td>
												Nama/Name : <b>PT PERWIRA TAMARAYA ABADI</b>
											</td>
											<td>
												NPWP/Tax Registration No  :  02.458.040.9-604.000
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									Alamat/Address : <b>PERGUDANGAN BUMI MASPION BLOK IX/E-1, ROMOKALISARI, BENOWO, SURABAYA</b>
								</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<table cellpadding="5" cellspacing="0" width="100%">
										<tr>
											<td>
												
											</td>
											<td>
												No.Telp/Phone No :
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									Kami mohon bantuan Saudara untuk menerbitkan Bank Garansi (BG) atau  Standby Letter of Credit (SBLC) *) untuk kepentingan dan atas beban kami dengan ketentuan-ketentuan sbb :
									<br><i>We herewith request you to issue on our behalf and for our account your Bank Guarantee (BG) or Standby Letter of Credit (SBLC) *) as per the following conditions:</i>
									<i>*) Coret yang tidak perlu/Cross the inappropriate one</i>
								</td>
							</tr>
							<tr>
								<td>
									Tujuan Penggunaan/<i>Purpose of Guarantee</i>:
									<p>
										<table cellpadding="5" cellspacing="0" width="100%">
											<tr>
												<td width="33%">
													<input type="checkbox" class="checkbox"> Tender/Bid
												</td>
												<td width="33%" style="text-align:left !important;">
													<input type="checkbox"> Uang Muka/Advance Payment
												</td>
												<td width="33%">
													<input type="checkbox"> Jaminan Kredit
												</td>
											</tr>
										</table>
									</p>
									<p>
										<input type="checkbox" checked> Pelaksanaan/Performance, mencakup/consists of (pilih salah satu/please select one): <b><i>{{ $data->type_bg }}</i></b>
									</p>
									<p>
										<ul>
											<li>Pemeliharaan/Retention</li>
											<li>Perdagangan/Commercial</li>
											<li>Pelaksanaan Pekerjaan/Performance</li>
											<li>Pembayaran/Payment</li>
											<li>Lain-lain: Cukai/Custom, Shipping Guarantee, Eksplorasi</li>
										</ul>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									Bahasa : <input type="checkbox" class="checkbox" checked> Bahasa Indonesia <input type="checkbox"> English
									<br><i>Language</i>
								</td>
							</tr>
							<tr>
								<td>
									Transaksi yang Dijamin*): SPK/Kontrak/Perjanjian/P.O/Undangan Tender/Dokumen lain (sebutkan) <b>{{ $data->for_bg }}</b>
									<br><i>Work Order/Agreement/P.O/ Invitation to Bid/Other (please specify) <b>{{ $data->for_bg }}</b></i>
									<br><i>No. {{ $data->contract_no }}
									<br>dari atau antara/from or between  ..................................................................................................................................................................................................................................................................................................................................................</i>

								</td>
							</tr>
							<tr>
								<td>
									<p>
										<table cellpadding="5" cellspacing="0" width="100%">
											<tr>
												<td width="33%">
													Format Garansi:
													<br><i>Legal Form</i>
												</td>
												<td width="33%" style="text-align:left !important;">
													<input type="checkbox"> Bank Standard
												</td>
												<td width="33%">
													<input type="checkbox"> Format Terlampir/Enclosed
												</td>
											</tr>
										</table>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>
										<table cellpadding="5" cellspacing="0" width="100%">
											<tr>
												<td width="33%">
													Tunduk pada (dan perubahannya):
													<br><i>Subject To (and its subtitution)</i>
												</td>
												<td width="67%" style="text-align:left !important;">
													<input type="checkbox" checked> Untuk/for Bank Garansi: SK Dir BI No. 23/88/KEP/DIR & SE Dir BI No. 23/7/UKU
													Saya setuju Bank Garansi tunduk pada pasal 1832 KUH-Perdata                                             
                                                    <br><i>we agree this Bank Guarantee is subject to clause 1832 Indonesian Civil Law</i>
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td width="50%">
																<input type="checkbox" class="checkbox"> Untuk/for SBLC: ISP 98 (Recommended)
															</td>
															<td width="50%" style="text-align:left !important;">
																<input type="checkbox"> UCP Latest version
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>
										<table cellpadding="" cellspacing="5" width="100%" border="0">
											<tr>
												<td width="50%">
													<table cellpadding="5" cellspacing="0" width="100%">
														<tr>
															<td width="25%">
																Cara penerbitan :
																<br><i>Issuance Method</i>
															</td>
															<td width="25%">
																<input type="checkbox" checked> Swift
															</td>
															<td width="25%" style="text-align:left !important;">
																<input type="checkbox"> Telex
															</td>
															<td width="25%" style="text-align:left !important;">
																<input type="checkbox"> Mail
															</td>
														</tr>
													</table>
												</td>
												<td width="50%" style="text-align:left !important;">
													Sejak Tanggal : <b>{{ $data->date }}</b>
													<br><i>Valid From</i>
												</td>
											</tr>
											<tr>
												<td width="50%">
													Nilai Garansi : Rp. <b>{{ number_format($data->nominal,0,',','.') }},-</b>
													<br><i>Guarantee Amount</i>
												</td>
												<td width="50%" style="text-align:left !important;">
													Tgl. / Tempat Berakhir Garansi : <b>{{ App\Helper\SMB::tgl_indo(date('Y-m-d', strtotime($data->date . " + ".$data->period." days"))) }}</b>
													<br><i>Date & Place of Expiry</i>
												</td>
											</tr>
										</table>
									</p>
								</td>
							</tr>
							<tr>
								<td><b>Terbilang : {{ strtoupper(App\Helper\SMB::terbilang($data->nominal)) }} RUPIAH.</b></td>
							</tr>
							<tr>
								<td><b>Penerima ( Beneficiary)</b></td>
							</tr>
							<tr>
								<td>
									<table cellpadding="" cellspacing="5" width="100%" border="0">
										<tr>
											<td width="20%">
												Nama/<i>Name</i>
											</td>
											<td width="80%" style="text-align:left !important;">
												{{ strtoupper($data->alProject->alCustomer->name) }}<br>
												SELAKU PENANDATANGAN KONTRAK
											</td>
										</tr>
										<tr>
											<td width="20%">
												Alamat/<i>Address</i>
											</td>
											<td width="80%" style="text-align:left !important;">
												{{ strtoupper($data->alProject->alCustomer->address) }}<br>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table cellpadding="" cellspacing="5" width="100%" border="0">
										<tr>
											<td width="60%">
												
											</td>
											<td width="40%" style="text-align:left !important;">
												Phone / Fax No :<br><br>Contact Person :
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table cellpadding="" cellspacing="5" width="100%" border="0">
										<tr>
											<td width="33%">
												Keterlibatan Bank Lain:
												<br><i>Involvement of Correspondent Bank</i>
											</td>
											<td width="33%" style="text-align:left !important;">
												<ul style="list-style-type: none;">
													<li>
														<input type="checkbox" class="checkbox"> Ya/Yes
														<br>Jika Ya, sebagai/<i>If Yes, as</i>:
													</li>
													<li>
														<input type="checkbox" class="checkbox"> Penerbit/Issuing (Indirect Guarantee)
													</li>
													<li>
														<input type="checkbox" class="checkbox"> Advising without Confirmation
													</li>
													<li>
														<input type="checkbox" class="checkbox"> Advising with Confirmation
													</li>
												</ul>
											</td>
											<td width="33%">
												<ul style="list-style-type: none;">
													<li>
														<input type="checkbox" class="checkbox"> Tidak/No
														<br>Jika Ya, sebagai/<i>If Yes, as</i>:
													</li>
													<li>
														<input type="checkbox" class="checkbox"> Jika Tidak, maka Penyerahan Asli Garansi /<i>If No, handing over original Guarantee to/by</i>:

													</li>
													<li>
														<input type="checkbox" class="checkbox"> Pemohon/Applicant <input type="checkbox" class="checkbox"> By Mail <input type="checkbox" class="checkbox"> By Courier 
													</li>
												</ul>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<b>Dokumen-dokumen yang dibutuhkan/Documents Required:</b>
								</td>
							</tr>
							<tr>
								<td>
									<b>a. Pernyataan tertulis dari Penerima bahwa Pemohon wanprestasi/ Certificate of Default from Beneficiary</b>
								</td>
							</tr>
							<tr>
								<td>
									<b>b. Khusus untuk Standby LC : Sight Draft yang ditujukan kepada Bank/ <i>Sight Draft addressed to  the Bank (Only for Standby LC)</i></b>
								</td>
							</tr>
							<tr>
								<td>
									<b>c. .................................................................................................................................</b>
								</td>
							</tr>
							<tr>
								<td>
									<b>d. .................................................................................................................................</b>
								</td>
							</tr>
							<tr>
								<td>
									<ol>
										<li>
											Sebagai sumber dana pelunasan kewajiban (Cover) Garansi yang timbul, kami menyediakan Cover sbb (isi yang sesuai):         
											<br><i>As source of payment of the outstanding Guarantee, we provide Cover as follows ( cross the appropriate  number and fill in the blank)</i>
											<ol type="a">
												<li>
													Fasilitas Non Cash Loan kami pada Bank Mandiri
													<br><i>Our Uncommitted Facility with Bank Mandiri</i>
													<br>Dalam hal penerbitan BG yang masih berlaku sampai sesudah tanggal  berakhirnya perjanjian ini, maka Pemohon/Debitur berjanji untuk  memenuhi segala ketentuan  dalam Perjanjian ini  perihal pelunasan segala  kewajiban (Pemohon/Debitur) kepada Bank yang berkaitan  dengan penerbitan BG dimaksudi
												</li>
												<li>
													Blokir KMK pada Cabang ................................................ No. ....................... atas nama .............................
													<br><i>Working Capital Loan Branch ....................................................</i>
												</li>
												<li>
													Counter Guarantee dari Bank .............................................................................
													<br><i>Counter Guarantee from Bank</i>
												</li>
												<li>
													Setoran Tunai 100% 
													<br><i>Cash Deposit 100%</i>
												</li>
												<li>
													Rekening Giro <b>{{ $data->company }} No {{ $data->no_rekening }} atas nama PT Perwira Tamaraya Abadi</b>
													<br><i>Current Account with Mandiri Branch</i>
												</li>
												<li>
													Rekening Tabungan/Tabungan Bisnis Bank Mandiri Cabang ................................................... No ..................................  atas nama .............................................
													<br><i>Saving Account with Mandiri Branch</i>
												</li>
												<li>
													Deposito/Deposit on Call Bank Mandiri Cabang ........................................................ No .................................. atas nama ............................................
													<br><i>Time Deposit of Bank Mandiri Branch</i>
												</li>
											</ol>
											<p>
												Dalam hal nama Cover yang diserahkan pada butir 1. (d) dan atau (e) berbeda dengan nama Pemohon, maka/If the Cover we provide as specified in 1. (d) and or (e) is under different name:
											</p>
											<ol type="a">
												<li>
													Surat Kuasa No. ...........................................................................
												</li>
												<li>
													Kami menyerahkan Surat Persetujuan Pemilik Rekening/Deposito dan atau Surat Persetujuan untuk menjaminkan dari istri/suami.
													<br><i>We provide you Agreement Letter from the Owner and or Agreement Letter from the spouse</i>
												</li>
												<li>
													Kami menyatakan bahwa Bank dapat melakukan pengikatan Cover sesuai ketentuan.
													<br><i>We understand  that Bank will have the Cover pledge as needed</i>
												</li>
											</ol>
										</li>
										<li>
											Kami mengetahui dan menyetujui bahwa penerbitan Garansi ini tunduk kepada Syarat-Syarat Umum Penerbitan Garansi seperti tercantum di Aplikasi ini
											<br><i>We have taken note and agreed of the General Terms and Conditions  in connection with the issuance of the Guarantee at the back of this Aplication.</i>
										</li>
										<li>
											Kami mengetahui dan menyetujui segala persyaratan pemanfaatan produk Garansi termasuk manfaat, risiko dan biaya-biaya yang melekat pada produk tersebut
											<br><i>We have taken note and agreed of the term and conditions in using Guarantee including its benefits, risks involved and Bank charges in using the product</i>
										</li>
										<li>
											Segala biaya yang timbul agar dibebankan ke rekening kami : (Bila tidak kami istruksikan lain) A/C No. .................................................................... Valuta/Currency: ........................  Cabang/Branch: ........................................................................................................
											<br><i>Bank charges to be debited for our account with you.</i>
										</li>
										<li>
											Kami memberikan kuasa yang tidak dapat ditarik kembali kepada Bank untuk memblokir, membuka blokir dan mendebet rekening-rekening kami pada Bank guna melunasi segala kewajiban Kami kepada Bank berkenaan penerbitan Garansi ini. Kuasa Kami tersebut tidak akan berakhir karena sebab-sebab yang termaktub dalam pasal-pasal 1813, 1814 dan 1816 KUH Perdata.
											<br><i>We hereby give the Bank authorization that cannot be withdrawn to debit our accounts with the Bank to pay all our outstanding to the Bank regarding the issuance o f the Guarantee. The authorization will not end because of any  reasons stated in clause 1813, 1814 and 1816 Civil Law.</i>
										</li>
										<li>
											Kami membebaskan Bank dari setiap dan segala tuntutan, gugatan, tagihan, tanggung jawab baik secara langsung maupun tidak langsung berkenaan dengan segala konsekuensi yang timbul dari pemblokiran dan atau pencairan dan atau penggunaan dana dalam rekening-rekening kami.
											<br><i>We shall release the Bank from any claims, suits, liabilities, directly or indirectly that may incur as consequences of the accounts’ utilization.</i>
										</li>
										<li>
											Kami menjamin bahwa pejabat/orang yang membubuhkan tanda tangan pada Aplikasi ini adalah pejabat/orang yang secara sah menurut hukum berhak sekaligus berwenang mewakili Kami dalam perjanjian ini yang dengan maksud apapun dapat dibuktikan dan dikemukakan di depan hukum.
											<br><i>We certify that the person who  sign this Application is the authorized person that legally has the right to represent us in this agreement and in any cause can be proven and presented under the law.</i>
										</li>
									</ol>	
								</td>
							</tr>
							<tr>
								<td>
									<b>Perhatian:  Instruksi tambahan  pada lembaran terpisah jika menjadi bagian Aplikasi ini wajib ditandatangani Pemohon.
									<br>Attention: Additional Instruction on separate sheet(s) if become part of this Application have to be sign by the Applicant</b>
								</td>
							</tr>
							<tr>
								<td style="text-align: justify;">
									<table cellpadding="" cellspacing="5" width="100%" border="0">
										<tr>
											<td width="50%">
												{{ $data->city->name }}, {{ App\Helper\SMB::tgl_indo($data->date) }}
												<br>
												Hormat kami,
												<br>
												<b>PT. PERWIRA TAMARAYA ABADI</b>
												<br><br><br>Materai 10rb<br><br><br>
												<b>PRAWIRO TEDJO TJANDRA</b>
												<br><i>Direktur</i>
											</td>
											<td width="50%" style="text-align:left;">
												<br><br><br><br><br><br><br><br>
												<br><b>Pemegang Rekening (apabila berbeda dengan Pemohon)</b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="invoice-box" style="font-size:12px !important;">
			<table cellpadding="0" cellspacing="0" width="100%" border="1">
				<tr>
					<td width="50%" style="text-align:justify;">
						<ol>
							<li>
								Pemohon mengetahui, bahwa sesuai dengan Aplikasi Pemohon, Bank menerbitkan Garansi sesuai dengan dasar hukum atau aturan dari masing-masing jenis Garansi yaitu:
								<ol type="a">
									<li>Independent Garansi adalah Garansi, dapat berupa Standby LC atau Demand Guarantee, yang mewajibkan pembayaran pada kesempatan pertama atas permintaan pembayaran dari Beneficiary dimana keberatan yang timbul berdasarkan underlying transaction diabaikan. Garansi ini tunduk pada ICC Rules (Uniform Customs and Practices for Documentary Credit/UCP atau International Standby Practices/ISP atau Uniform Rules on Demand Guarantee/URDG) (ICC Rule) yang berlaku pada saat penerbitan Garansi.</li>
									<li>Bank Garansi adalah Garansi yang diterbitkan berdasarkan SK Dir BI No. 23/88/KEP/DIR tanggal 18 Maret 1999 (SK Dir BI) dan bersifat accessoir (merupakan perjanjian ikutan dari perjanjian pokoknya).</li>
								</ol>
							</li>
							<li>
								Pernyataan-pernyataan Pemohon Berkaitan dengan Perbedaan Jenis Garansi
								<ol type="a">
									<li>Pemohon menyetujui bahwa apabila Penerima Jaminan/Beneficiary dari sebuah Independen Garansi mengajukan klaim sesuai dengan syarat-syarat dalam Garansi, maka pembayaran akan dilakukan dengan segera dan PT. Bank Mandiri (Persero), Tbk (selanjutnya disebut Bank) tidak berkewajiban untuk memeriksa kebenaran pernyataan yang diberikan Beneficiary pada klaim yang diajukan (misalnya apakah kewajiban kontrak memang sudah jatuh tempo, atau apakah kewajiban atas kontrak telah dilaksanakan</li>
									<li>Pemohon menyetujui bahwa kecuali apabila menurut penilaian Bank terdapat kecurigaan bahwa klaim yang diajukan oleh Beneficiary merupakan penipuan/fraud, Bank tidak dapat menolak klaim yang sesuai dengan Garansi meskipun terdapat penolakan adanya wan prestasi atas underlying transaction (misalnya bahwa kewajiban sebenarnya belum jatuh tempo, atau bahwa sesungguhnya kewajiban telah dilaksanakan sesuai kontrak dll).  Hal ini juga berlaku apabila klaim diajukan karena Pemohon wan prestasi yang disebabkan oleh  hal-hal diluar kontrol Pemohon, seperti force majeure, perang, bencana alam dll.</li>
									<li>Pemohon menyetujui bahwa Garansi yang bersifat independent tunduk pada ICC Rule dan hal ini perlu dicantumkan dalam format Garansi yang akan diterbitkan.  Dalam hal Pemohon meminta klausula tersebut dihilangkan, maka :
										<ol type="i">
											<li>Pemohon telah diberitahu oleh Bank bahwa BG dimaksud sebaiknya tunduk pada aturan internasional perihal Guarantee yaitu URDG ICC Publication No. 758 (atau International Standby Practices, ICC Publication No 590) karena BG bersifat Independent</li>
											<li>Pemohon dengan sukarela meminta Bank untuk tidak memasukkan klausula tersebut dan menerbitkan BG sesuai format dari Beneficiary</li>
											<li>Pemohon akan menanggung segala akibat yang ditimbulkan dengan tidak dimasukkannya klausula tersebut dalam BG yang akan diterbitkan</li>
											<li>Pemohon membebaskan Bank dari setiap dan segala tuntutan, gugatan, tagihan, tanggung jawab baik secara langsung maupun tidak langsung yang timbul berkenaan dengan tidak dimasukkannya klausula tersebut dalam BG yang akan diterbitkan</li>
										</ol>
									</li>
									<li>Pemohon menyetujui bahwa Garansi yang diterbitkan berdasarkan SK Dir BI, merupakan accessoir dari perjanjian pokok/Underlying Transactionnya sehingga jangka waktu Garansi akan berakhir selain karena berakhirnya jangka waktu seperti yang tercantum dalam Garansi, juga karena berakhirnya perjanjian pokok.  Dalam hal Pemohon meminta Bank untuk menerbitkan Garansi yang tunduk pada SK Dir BI tersebut dengan jangka waktu yang lebih lama dari perjanjian pokok, maka:
										<ol type="i">
											<li>Pemohon menyetujui bahwa Garansi tersebut akan tetap berlaku sampai dengan tanggal jatuh tempo Garansi walaupun perjanjian pokok  telah berakhir</li>
											<li>Pemohon menyetujui bahwa kewajiban Bank kepada Beneficiary dan kewajiban Pemohon terhadap Bank atas Garansi tersebut berakhir setelah terdapat pembayaran klaim atau setelah berakhirnya masa klaim Garansi, mana yang lebih dulu</li>
											<li>Pemohon berjanji melunasi segala kewajiban Pemohon kepada Bank</li>
										</ol>
									</li>
									<li>Pemohon menyetujui bahwa Garansi yang diterbitkan berdasarkan SK Dir BI merupakan accessoir dari perjanjian pokok/Underlying Transactionnya sehingga Garansi baru berlaku effektif apabila perjanjian pokoknya telah belaku effektif atau telah ditandatangani para pihak.  Dalam hal Pemohon meminta Bank menerbitkan Garansi yang tunduk pada SK Dir BI tersebut namun perjanjian pokoknya belum ditandatangani, maka:
										<ol type="i">
											<li>Pemohon menyatakan bahwa telah terdapat kesepakatan para pihak walaupun perjanjian belum ditandatangani</li>
											<li>Pemohon berjanji akan menyerahkan copy perjanjian yang mendasari penerbitan Garansi kepada Bank setelah perjanjian ditandatangani</li>
											<li>Pemohon sedapat mungkin akan mencantumkan bahwa perjanjian pokok berlaku effektif pada tanggal penerbitan Garansi.  Dalam hal perjanjian pokok berlaku efektif melebihi tanggal penerbitan Garansi, Pemohon telah mengetahui bahwa kewajiban Bank terhadap Garansi tersebut berlaku effektif pada tanggal yang sama dengan berlakunya perjanian pokok tersebut.  Pemohon membebaskan Bank dari segala akibat yang timbul dari padanya.</li>
										</ol>
									</li>
									<li>Pemohon menyetujui bahwa Garansi yang diterbitkan oleh Bank Koresponden di luar Indonesia berdasarkan Kontra Garansi dari Bank, tunduk pada hukum negara bank penerbit, kecuali apabila disebutkan lain.  Pemohon mengetahui bahwa Bank Mandiri, dalam hal ini, tidak memiliki kompetensi yang cukup untuk memeriksa keabsahan dokumen yang diterbitkan berdasarkan hukum negara lain dan Bank Mandiri berwenang, namun tidak berkewajiban, untuk menafsirkan Garansi seolah-olah tunduk pada hukum Indonesia dan bertindak sesuai hukum tersebut.  Pemohon berjanji membayar biaya-biaya yang timbul, termasuk biaya legal, akibat penerbitan Garansi berdasarkan hukum negara lain.</li>
								</ol>
							</li>
						</ol>
					</td>
					<td width="50%" style="text-align:justify;">
						<ol start="5">
							<li>
								<ol type="a" start="7">
									<li>Terhadap permintaan perubahan Garansi, Pemohon menyetujui untuk menyampaikan permohonan secara tertulis kepada Bank disertai bukti-bukti yang mendasari perubahan dan membayar biaya-biaya yang ditentukan oleh biaya (termasuk bunga dan atau denda, bila ada) lain yang timbul sehubungan dengan penerbitan dan pembayaran klaim Garansi, dan dalam hal saldo rekening tidak mencukupi, menagih kepada Pemohon.  Apabila Pemohon tidak melunasi kewajibannya kepada Bank, maka:
										<ol type="i">
											<li>Pemohon mengakui dengan tegas dan menyetujui bahwa semua pembayaran yang telah dilakukan oleh Bank termasuk biaya-biaya yang timbul merupakan kewajiban Pemohon yang telah jatuh waktu dan harus dibayar kembali kepada Bank.</li>
											<li>Pemohon mengakui bahwa Bank berhak – tanpa dikuasakan untuk itu – dengan tanpa pemberitahuan terlebih dahulu kepada Pemohon untuk mendebet/mencairkan rekening/deposito yang diserahkan dan/atau rekening-rekening atas nama Pemohon yang ada pada Bank untuk melunasi kewajiban Pemohon.</li>
											<li>Dalam hal rekening dan atau deposito yang diserahkan Pemohon merupakan rekening dan atau deposito atas nama pihak ketiga, Pemohon menyerahkan dokumen-dokumen yang diperlukan Bank termasuk namun tidak terbatas pada dokumen pengikatan.</li>
											<li>Apabila Bank tidak dapat mendebet/mencairkan rekening tersebut karena sebab apapun, Pemohon wajib segera menyerahkan pembayaran dari sumber lain untuk melunasi kewajiban Pemohon kepada Bank.</li>
											<li>Pemohon menyetujui segala tindakan yang dianggap perlu oleh Bank termasuk namun tidak terbatas pada mengalihkannya ke dalam bentuk lain sesuai pertimbangan Bank.  Pemohon menandatangani dokumen-dokumen (apabila ada) yang diperlukan Bank.</li>
										</ol>
									</li>
								</ol>
							</li>
							<li>
								Konversi Mata Uang
								<br>
								Pemohon menyetujui tanpa syarat bahwa setiap pengkonversian suatu mata uang atau valuta ke dalam mata uang atau valuta lain sehubungan dengan pembayaran-pembayaran atau pembebanan-pembebanan atau perhitungan-perhitungan dalam rangka pembukaan dan atau pembayaran Garansi akan dilakukan berdasarkan kurs jual yang ditetapkan oleh Bank. Pemohon menyetujui bahwa risiko kerugian karena perubahan kurs menjadi tanggungan Pemohon.
							</li>
							<li>
								Format Garansi
								<br>
								<ol type="a">
									<li>Pemohon menyetujui bahwa Bank akan menggunakan standard Garansi Bank, yang pada dasarnya tunduk pada hukum Indonesia, kecuali apabila nature bisnis atau permintaan Pemohon menghendaki lain, maka format tersebut terlebih dahulu harus mendapat persetujuan Bank.</li>
									<li>Pemohon menyetujui bahwa dalam hal Pemohon meminta Bank menerbitkan Garansi yang tidak sesuai dengan standar Bank, Bank berhak menetapkan standar minimum klausula yang wajib tercantum dalam Garansi tersebut.</li>
									<li>Dalam hal Pemohon meminta Bank menerbitkan Membebaskan Bank dari akibat hokum dari penerbitan BG yang telah sesuai dengan Aplikasi nasabah.</li>
								</ol>
							</li>
							<li>
								Hukum yang Berlaku dan Domisili Hukum
								<br>
								Mengenai syarat-syarat umum dan perjanjian penerbitan Garansi beserta segala akibat yang berakar dari padanya, Pemohon menyetujui untuk memberlakukan hukum Indonesia dan memilih tempat kedudukan hukum yang tetap dan secara umum pada Kantor Panitera Pengadilan Negeri yang wewenangnya meliputi wilayah tempat kantor Bank. Dengan tidak mengurangi ketentuan peraturan yang berlaku, Pemohon menyetujui bahwa Bank berhak untuk mengajukan tuntutan hukum terhadap Pemohon melalui Pengadilan Negeri Lainnya yang berwenang di dalam wilayah Republik Indonesia. Garansi dapat mempunyai kedudukan dan domisili hukum lain dengan persetujuan Bank.
							</li>
							<li>
								Perubahan Peraturan
								<br>
								Pemohon menyetujui apabila terdapat perubahan peraturan ketentuan dan/atau tambahan peraturan tentang Garansi yang dikeluarkan oleh International Chamber of Commerce (ICC), Bank Indonesia dan/atau Pemerintah dan/atau peraturan ketentuan yang berlaku pada Bank maka bank berhak setiap saat dapat mengubah syararat & kondisi ini. Perubahan-perubahan tersebut akan diberitahukan kepada Pemohon dan berlaku sejak pemberitahuan dari Bank.
							</li>
						</ol>
					</td>
				</tr>
				<tr>
					<td colspan="2"><b>B. KOLOM UNTUK BANK :</b></td>
				</tr>
				<tr>
					<td colspan="2">
						<ol>
							<li>
								Berdasarkan permintaan Pemohon di atas, Bank menerbitkan Garansi setelah Pemohon memenuhi kewajiban-kewajiban sebagai berikut:
								<br>
								<ol type="a">
									<li>Menandatangani Aplikasi ini dengan dibubuhi meterai yang cukup.</li>
									<li>
										<ul type="square">
											<li>
												Menggunakan Limit Fasilitas Bank Garansi
											</li>
											<li>
												Menggunakan Blokir KMK A/C No ..........................................................atas nama ...........................................................................................
											</li>
											<li>
												Menyerahkan setoran margin tunai dari nilai Garansi atau senilai ...............................................................................( terbilang: .............
             ................................................................................................................................................................................................................................ ) yang dikuasai dan dibukukan kedalam rekening setoran jaminan tersendiri oleh Bank.
											</li>
											<li>
												Menyerahkan setoran margin berupa blokir Giro/Tabungan/Tabungan Bisnis*) A/C No..................................... atas nama : .............................
     ......................................................... senilai ........................................................ ( terbilang:...............................................................................
     ................................................................................................................................................................................................................................ )

											</li>
											<li>
												Menyerahkan setoran margin dengan komposisi :
												<br>
												<ol type="a">
													<li>Tunai  ...................... % atau senilai ..................................................................................... (terbilang : ......................................................................................................................................................... )
yang dikuasai dan dibukuan kedalam rekening setoran jaminan tersendiri oleh Bank.
</li>
													<li>
														Blokir Giro/Tabungan/Tabungan Bisnis A/C No...................................................................................... atas nama : ............................................................................senilai......................................................................................(terbilang:............................................................................................................................................................................................................................................................)
													</li>
												</ol>
											</li>
											<li>
												Menyerahkan setoran margin berupa Deposito/Deposit on Call No ............................ .....................................atas nama : ..............................
     ................................................................. senilai........................................................(terbilang:.........................................................................
     .............................................................................................................................................................................................................................. )
 berdasarkan Surat Pencairan Deposito No. ................................................................. Tanggal ...........................................................

											</li>
										</ul>
										Dalam hal margin yang diserahkan mempunyai nama yang berbeda dengan nama Pemohon, margin tersebut dimiliki oleh Badan Hukum dan telah dilakukan pengikatan sesuai ketentuan dan Surat Persetujuan Pemilik Rekening dan atau pasangan telah diterima.
									</li>
									<li>
										Melunasi provisi penerbitan sebesar ..............%  dari nilai Garansi, biaya administrasi ............................................. dan biaya-biaya lainnya sesuai ketentuan tarif Bank  yang telah diberitahukan kepada Pemohon.
									</li>
								</ol>
							</li>
							<li>
								Bank setuju membuka Garansi hingga jumlah setinggi-tingginya sebesar ........................................................................ (terbilang : .............................................................................................................................. rupiah)
							</li>
							<li>
								Apabila dipandang perlu, mengenai hal-hal yang dianggap belum cukup diatur atau yang perlu diubah/ditambah Pemohon dan Bank sepakat untuk mengaturnya lebih lanjut dalam perjanjian yang dibuat terpisah namun tetap menjadi satu kesatuan dari perjanjian penerbitan Garansi ini.
							</li>
							<li>
								Dokumen Perjanjian Garansi berstatus "Complete”
							</li>
							<li>
								Perjanjian Penerbitan Garansi ini mulai berlaku terhitung sejak tanggal penandatanganan oleh Bank.
							</li>
						</ol>
						<b>
							{{ $data->city->name }}, ....................................
							<br>
							Menyetujui,
							<br>
							{{ $data->company }}.
							<br><br><br><br><br><br><br>
							...................................................
							<br>
							(..................................................)
						</b>
						<br><br>
						<table cellpadding="5" cellspacing="0" width="40%" border="1">
							<tr>
								<td colspan="2">
									<b>C. KOLOM UNTUK PROCESSING UNIT</b>
								</td>
							</tr>
							<tr>
								<td width="40%">
									<b>Nomor Garansi</b>
								</td>
								<td width="60%" style="text-align:left !important;">
									
								</td>
							</tr>
							<tr>
								<td width="40%">
									<b>Diserahkan kpd</b>
								</td>
								<td width="60%" style="text-align:left !important;">
									
								</td>
							</tr>
							<tr>
								<td width="40%">
									<b>Dibuka dengan</b>
								</td>
								<td width="60%" style="text-align:left !important;">
									<table cellpadding="5" cellspacing="0" width="100%">
										<tr>
											<td width="33%">
												<input type="checkbox"> Swift
											</td>
											<td width="33%" style="text-align:left !important;">
												<input type="checkbox"> Telex
											</td>
											<td width="33%" style="text-align:left !important;">
												<input type="checkbox"> Mail
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="40%">
									<b>Tanggal dibuka</b>
								</td>
								<td width="60%" style="text-align:left !important;">
									
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<table cellpadding="5" cellspacing="0" width="40%">
										<tr style="text-align:center;">
											<td width="50%">
												Diproses Oleh:
												<br><br><br>
												______________
											</td>
											<td width="50%" style="text-align:left !important;">
												Diotorisasi Oleh:
												<br><br><br>
												______________
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="invoice-box" style="font-size:18px !important;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<img src="{{ url('website/logo_bank_mandiri.png') }}" width="auto" height="30px">
					</td>
				</tr>
				<tr>
					<td style="text-align:center;">
						<h1>SURAT KUASA</h1>
						<h4>No. {{ str_replace('SP','SK',$data->code) }} tanggal {{ App\Helper\SMB::tgl_indo($data->date) }}</h4>
					</td>
				</tr>
				<tr>
					<td style="text-align:justify;">
						<p>
							Yang bertanda tangan dibawah ini :
						</p>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="25%">Nama</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">Prawiro Tedjo Tjandra</td>
							</tr>
							<tr>
								<td width="25%">Jabatan</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									Direktur
								</td>
							</tr>
							<tr>
								<td width="25%">Nama Perusahaan</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									PT Perwira Tamaraya Abadi
								</td>
							</tr>
							<tr>
								<td width="25%">NPWP</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									02.458.040.9-604.000
								</td>
							</tr>
							<tr>
								<td width="25%">Alamat</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									Pergudangan Bumi Maspion Blok IX/E-1
								</td>
							</tr>
						</table>
						<p>
							bertindak untuk atas nama PT Perwira Tamaraya Abadi sesuai jabatannya, selanjutnya dalam surat kuasa ini disebut sebagai <b>Pemberi Kuasa</b>, dengan ini memberi kuasa kepada :
						</p>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="25%">Nama</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">{{ $data->company }}</td>
							</tr>
							<tr>
								<td width="25%">Alamat</td>
								<td>:</td>
								<td style="text-align:left !important;padding-left:10px;">
									{{ $data->address.', '.$data->city->name }}
								</td>
							</tr>							
						</table>
						<p>
							selanjutnya dalam Surat Kuasa ini disebut sebagai <b>Penerima Kuasa</b>, untuk (cantumkan sesuai bentuk Cover):
						</p>
						<ul type="square">
							<li>
								Cover Giro Margin Tunai:
								<br>
								Mendebit rekening Giro/Tabungan*) No ........................... atas nama ......................... nominal (terbilang) ................... guna menyelesaikan kewajiban pembayaran Bank Garansi/LC/SKBDN/SBLC*) sesuai Form Aplikasi No. ...........................**)
							</li>
							<li>
								Cover Blokir Giro/Tabungan*):
								<br>
								<ol>
									<li>Memblokir rekening Giro/Tabungan*) No {{ $data->no_rekening }} atas nama PT Perwira Tamaraya Abadi nominal (terbilang) {{ App\Helper\SMB::terbilang($data->nominal) }} Rupiah.</li>
									<li>Mendebit sewaktu-waktu rekening Giro/Tabungan*) No ........................... atas nama ......................... Nominal (terbilang)  ........................... guna menyelesaikan kewajiban pembayaran Bank Garansi /LC/SKBDN/SBLC*) sesuai Form Aplikasi No 8.Br.SGK/BG.........................../2014 **)</li>
								</ol>
							</li>
							<li>
								Cover Deposito:
								<br>
								<ol>
									<li>Menyimpan dan  menguasai bilyet deposito No ....................... tanggal ...................... atas nama ...................... nominal (terbilang) .......................</li>
									<li>Mencairkan sewaktu-waktu Deposito No. ..................... tanggal ......................... atas nama ....................... nominal (terbilang) ............................... ke rekening Giro No. ..................... guna menyelesaikan kewajiban pembayaran Bank Garansi /LC/SKBDN/SBLC*) sesuai Form Aplikasi No 8.Br.SGK/BG....................../2014 **)</li>
								</ol>
							</li>
						</ul>
						<p>
							Agar Surat Kuasa ini dipergunakan sebagaimana mestinya.
						</p>
						<p>
							{{ $data->city->name }}, {{ App\Helper\SMB::tgl_indo($data->date) }}
						</p>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="50%">
									<b>Pemberi Kuasa</b>
									<br><br><br>Materai Rp 10.000,-<br><br><br>
									(Prawiro Tedjo Tjandra)
									<br>
									Direktur
								</td>
								<td width="50%" style="text-align:left;">
									<b>Penerima Kuasa</b>
									<br>PT Bank Mandiri (Persero) Tbk
									<br><br><br><br><br>
									________________________
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						*)   Coret yang tidak perlu
						<br>
						**)  Diisi oleh Unit Servicing Penerima Aplikasi
						<br>
						(*)  Hub Manager/Spoke Manager/TSC Manager sesuai Juklak No. CMB.WPMG/TF.1009/2007 tanggal  22.03.2007
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>