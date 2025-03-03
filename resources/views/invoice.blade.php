@extends('layouts.masterlayout')

@section('title', 'Invoice'){{-- Title --}}
@section('page_heading', 'Invoice'){{-- Page Heading --}}

@section('modal_button')
	<div class="col-auto ms-auto d-print-none">
		<button type="button" class="btn btn-primary" onclick="javascript:window.print();">
			Download Invoice
		</button>
		<button type="button" class="btn btn-primary" onclick="javascript:window.print();">
			Print Invoice
		</button>
	</div>
@endsection

@section('content') {{-- Page Body --}}

	<div class="card card-lg">
		<div class="card-body">
			<div class="row">
				<div class="col-6">
					<p class="h3">{{Auth::user()->name}}</p>
					<address>
						11-A, Sardar Mall<br>
						Ahmedabad, Gujarat<br>
						India, 382350<br>
						{{Auth::user()->email}}
					</address>
				</div>
				<div class="col-6 text-end">
					<p class="h3">{{$data->username}}</p>
					<address>
						Street Address<br>
						State, City<br>
						Region, Postal Code<br>
						{{$data->email}}<br>
						{{$data->mobile}}<br>
					</address>
				</div>
				<div class="col-12 my-5">
					<h1>Invoice INV/001/15</h1><br>
				</div>
			</div>
			<table class="table table-transparent table-responsive">
				<thead>
					<tr>
						<th class="text-center" style="width: 1%"></th>
						<th>Product</th>
						<th class="text-center" style="width: 1%"></th>
						<th class="text-end" style="width: 1%">Qnt</th>
						<th class="text-end" style="width: 1%">Unit</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($expenses as $expense)
						<tr class="main-items">
							<td class="text-center">{{$expense->id}}</td>
							<td>
								<p class="strong mb-1">{{$expense->item}}</p>
							</td>
							<td class="text-center"></td>
							<td class="text-end">1</td>
							<td class="text-end amount" data-amount="{{$expense->amount}}">₹{{$expense->amount}}</td>
						</tr>
					@endforeach
					<tr>
						<td colspan="4" class="strong text-end">Subtotal</td>
						<td class="text-end subtotal">₹</td>
					</tr>
					<tr>
						<td colspan="4" class="strong text-end">Vat Rate</td>
						<td class="text-end">20%</td>
					</tr>
					<tr>
						<td colspan="4" class="strong text-end">Vat Due</td>
						<td class="text-end due">₹</td>
					</tr>
					<tr>
						<td colspan="4" class="font-weight-bold text-uppercase text-end">Total Due</td>
						<td class="font-weight-bold strong text-end allTotal">₹</td>
					</tr>
				</tbody>
			</table>
			<p class="text-secondary text-center mt-5">Thank you very much for doing business with us. We look forward to
				working with you again!</p>
		</div>
	</div>

	<script>
		$(document).ready(function () {
			const ref = $('.main-items');
			const amount = $('.amount').data('amount');

			let total = 0;

			$('.amount').each(function () {
				const value = parseInt($(this).data('amount')) || 0;
				total += value;
			});

			var subtotal = $('.subtotal').append(total);

			var Due = total * 20 / 100;

			$('.due').append(Due);

			var allTotal = total + Due;

			$('.allTotal').append(allTotal);

		});
	</script>

@endsection