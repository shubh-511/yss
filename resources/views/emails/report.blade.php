<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Report</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.rtl table {
				text-align: right;
			}

			.rtl table tr td:nth-child(2) {
				text-align: left;
			}
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="https://connect.yoursafespaceonline.com/images/yss_new_logo4x.svg" style="width: 100%; max-width: 100px;" />
								</td>

								<td>
									Report <br />
									Created: {{ date('j F, Y', strtotime($bookingData['created_at'])) }}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									Your Safe Space<br />
									United Kingdom
								</td>

								<td>
									{{$bookingData['user']['name']}}<br />
									{{$bookingData['user']['email']}}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Payment Method</td>

					<td>@if($bookingData['created_by'] == 1) {{'Online'}} @else {{'Cash'}} @endif#</td>
				</tr>

				<tr class="details">
					<td>@if($bookingData['created_by'] == 1) {{'Online'}} @else {{'Cash'}} @endif</td>

					
				</tr>

				<tr class="heading">

					<td>Appointment Details</td>
					<td></td>
				</tr>

				<tr class="details">
					<td>{{'Counsellor Name'}}</td>
					<td>{{$bookingData['counsellor']['name']}}</td>
				</tr>
				<tr>
					<td>{{'Package Name'}}</td>
					<td>{{$bookingData['package']['package_name']}}</td>
				</tr>
				<tr>
					<td>{{'Session Duration'}}</td>
					<td>{{$bookingData['package']['session_hours']}} {{$bookingData['package']['session_minutes']}}</td>
				</tr>
				<tr>
					<td>{{'Amount To Paid'}}</td>
					<td>{{($bookingData['payment_detail']['amount'])/100}}</td>
				</tr>
				
				@if((count($logs) > 0))
				<tr class="heading">

					<td>Call Logs</td>
					<td></td>
				</tr>

				
				@foreach($logs as $log)
				<tr class="details">
						<td>{{'Initiated By'}}</td>
						<td>{{$log->initiated_by->name ?? '--'}}</td>
				</tr>
				<tr>
						<td>{{'Call Pick By'}}</td>
						<td>{{$log->picked_by->name ?? '--'}}</td>
				</tr>
				<tr>
						<td>{{'Call Cut By'}}</td>
						<td>{{$log->cutted_by->name ?? '--'}}</td>
				</tr>
				<tr>
						<td>{{'Call Status'}}</td>
						<td>{{$log['status']}}</td>
				</tr>
				<tr>
						<td>{{'Call Duration'}}</td>
						<td>{{$log['call_duration']?? '--'}}</td>
				</tr>
				@endforeach
				
				@endif
				<tr class="heading">

					<td>Selected Date & Slot</td>

					<td>Price</td>
				</tr>

				<tr class="item">
					<td>{{$bookingData['booking_date']}} - {{$bookingData['counsellor_timezone_slot']}}</td>

					<td>{{($bookingData['payment_detail']['amount'])/100}}</td>
				</tr>

				
				
				<tr class="total">
					<td></td>

					<td>Total: {{($bookingData['payment_detail']['amount'])/100}}</td>
				</tr>
			</table>
		</div>
	</body>
</html>