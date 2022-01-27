<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000; ">
	<table style="max-width: 700px; width: 100%; border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin: 20px auto;width:100%; border: 1px solid #ddd;" cellpadding="0" cellspacing="0">
  		<tbody style="">
    		<tr>
    			<td style="padding-left: 50px; padding-right: 50px;font-family: 'Quicksand', sans-serif;">
    				<h4 style="line-height: 10px; margin: auto;padding:35px 0 10px 0; font-size: 25px; color: #000; font-weight: 700;">
    					Hello {{ $bookedUser['name'] }}  has booked your package.
    				</h4>
    				<p style="margin-bottom: 10px;">
   						<span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
    						You have received a booking; you have an appointment with {{ $bookedUser['name'] }} at {{$booking['slot']}} on {{$booking['booking_date']}}. On the day of your appointment please login to the site and navigate to your bookings, then select the booking and when you are ready begin your session. On your fist session your browser will ask for permission to use your camera and microphone, accept these please so your connection to your client works! Please make sure you have a good internet connection and that you will not be interrupted so you can have the best session!.
    					</span>
    				</p>
                    
                    <p style="margin-bottom: 20px; margin-top: 50px;">
                        <span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
                            Kind regards
                        </span>
                    </p>
                    <p style="margin-bottom: 20px;">
                       <span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
                            {{ config('app.name') }}
                            <br>
                            <a href="https://soberlistic.com">{{ config('app.name') }}.com
                        </span>
                    </p>
                      <tr style="vertical-align: top;">
                        <td style="padding: 20px; background: #f5f8fa; word-break: break-word;border-collapse: collapse !important;vertical-align: top; text-align: center;font-family: 'Quicksand', sans-serif;">
                            <img src="{{ $message->embed(asset('/uploads/logo.png'))}}" style="max-width: 40%; width: 100%;" alt="Logo" />
                        </td>
                    </tr>
                    <hr />
                     <p style="margin-bottom: 20px;">
                         <span style="max-width: 50%; width: 100%; font-size: 16px; font-weight: 500; color: #6B6B6B;">
                            This e-mail is intended only for the person(s) or entity to which it is addressed and may contain confidential information. Any review, distribution, copying, printing, or other use of this e-mail by anyone other than the name recipient is prohibited. If you have received this e-mail in error or are not the named recipient, please notify the sender immediately and permanently delete this e-mail and all copies of it. Thank you. If you no longer wish to receive these emails please Unsubscribe (link) but be aware if you do you will not receive any notifications you may need to book, amend booking or have the full booking functionality of SoberListic. <a href="https://soberlistic.com/privacy-policy">Privacy Policy</a><br><a href="https://soberlistic.com/terms-and-conditions">Terms & Conditions </a>
                        </span>
                    </p>
    			</td>
    		</tr>
            <tr style="background: #f5f8fa;">
                <td style="padding-left: 50px; padding-right: 50px; padding-top: 20px;font-family: 'Quicksand', sans-serif;">
                   <p style="font-size: 16px; padding-bottom: 20px; color: #6B6B6B;; text-align: center; font-weight: 200;">
                         ©2022 Wellness Tech LTD Company Number 13156843 <a href="https://find-and-update.company-information.service.gov.uk/company/13156843">link to company’s house </a>
                    </p>
                </td>
            </tr>
    	</tbody>
    </table>
</body>
</html>
