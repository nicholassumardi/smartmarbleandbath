<table>
	@foreach($arrResult as $rowwarehouse)
	@php
	$no = 1;
	$prevSize = null;
	$grandtotalfinal = 0;
	@endphp
	<table>
		<tr>
			<td align="center" colspan="10" style="background-color:#b80d76;color:white;"><b style="font-size: 18pt;">{{
					$rowwarehouse['warehouse_name'] }}</b></td>
		</tr>
	</table>
	<table border="1" cellpadding="3" cellspacing="0" style="width:100%; font-size:13px;">
		<thead>
			<tr align="center" style="background-color:#324148;color:white;font-size:25px !important;">
				<th>NO</th>
				<th>IMAGE</th>
				<th>PRODUCT</th>
				<th>SIZE</th>
				<th>Thickness</th>
				<th>QTY (sqm)</th>
				<th>QTY (BOX)</th>
				<th>USD</th>
				<th>PRICE/m<sup>2</sup></th>
				<th>Price</th>
				<th>Grandtotal</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rowwarehouse['detail'] as $row)
			@if ($prevSize != $row['size'])
			<tr>
				<td align="center" colspan="10" style="background-color:#b80d76;color:white;"><b style="font-size: 14pt;">{{
						$row['size'] }}</b></td>
			</tr>
			@php
			$prevSize = $row['size'];
			@endphp
			@endif
	
			<tr>
				<td align="center">{{ $no }}</td>
				{{-- <td align="center"><img src="{{$row['image']}}" alt=""></td> --}}
				<td><b style="font-size:13pt;">{{'asset/'. $row['product_name'] }}</b></td>
				<td align="center">
					<b style="font-size:13pt;">
						{{ $row['size'] }}
						<br>
						({{ $row['m2'] }}) m<sup>2</sup>
					</b>
				</td>
				<td align="center"><b style=" font-size:13pt;">{{ $row['thickness'] }} mm<sup>2</sup></b></td>
				<td align="center"><b style=" font-size:13pt;">{{ $row['qty_sqm'] }} m<sup>2</sup></b></td>
				<td align="center">
					<b style="font-size:13pt;">
						{{ round($row['qty']).' Box'}}
						<br>
						{{$row['carton_pcs']}}
					</b>
				</td>
				<td class="text-right"><b style=" font-size:13pt;">$</b></td>
				<td class="text-right"><b style=" font-size:13pt;">Rp
						{{number_format($row['pricefinal_sqm'],2,'.',',')}}</b></td>
				<td class="text-right"><b style=" font-size:13pt;">Rp
						{{number_format($row['pricefinal'],2,'.',',')}}</b></td>
				<td class="text-right"><b style=" font-size:13pt;">Rp {{number_format(round($row['pricefinal'] *
						$row['qty']),0,'.',',')}}</b></td>
			</tr>
	
			@php
			$no ++;
			$grandtotalfinal += $row['pricefinal'] * $row['qty'];
			@endphp
			@endforeach
			<tr class="text-center">
				<th colspan="9"><b>Total</b></th>
				<th><b>Rp. {{number_format($grandtotalfinal,2,'.',',')}}</b></th>
			</tr>
		</tbody>
	</table><br>
	@endforeach
</table>