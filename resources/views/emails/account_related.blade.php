<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000; ">
	<table style="max-width: 700px; width: 100%; border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin: 20px auto;width:100%; border: 1px solid #ddd;" cellpadding="0" cellspacing="0">
  		<tbody style="">
  			<tr style="vertical-align: top;">
    			<td style="padding: 20px; background: #f5f8fa; word-break: break-word;border-collapse: collapse !important;vertical-align: top; text-align: center;font-family: 'Quicksand', sans-serif;">
    				<img src="{{ $message->embed(asset('/uploads/logo.png'))}}" style="max-width: 40%; width: 100%;" alt="Logo" />
    			</td>
    		</tr>
    		<tr>
    			<td style="padding-left: 50px; padding-right: 50px;font-family: 'Quicksand', sans-serif;">
    				<h4 style="line-height: 10px; margin: auto;padding:35px 0 10px 0; font-size: 25px; color: #000; font-weight: 700;">
    					Hello {{ $user['name'] }}
    				</h4>
    				<p style="margin-bottom: 10px;">
   						<span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
    						{!! $body !!}
    					</span>
    				</p>
                    
                    <p style="margin-bottom: 20px; margin-top: 50px;">
                        <span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
                            Regards,
                        </span>
                    </p>
                    <p style="margin-bottom: 20px;">
                        <span style="max-width: 50%; width: 100%; font-size: 18px; font-weight: 500; color: #6B6B6B;">
                            {{ config('app.name') }}
                        </span>
                    </p>
                    <hr />
                    <p style="margin-bottom: 20px;">
                        <span style="max-width: 50%; width: 100%; font-size: 16px; font-weight: 500; color: #6B6B6B;">
                            If you're having trouble clicking the "Notification Action" button, copy and paste the URL below into your web browser: <a href="javascript:void(0)">test.com</a>
                        </span>
                    </p>
    			</td>
    		</tr>
            <tr style="background: #f5f8fa;">
                <td style="padding-left: 50px; padding-right: 50px; padding-top: 20px;font-family: 'Quicksand', sans-serif;">
                    <p style="font-size: 16px; padding-bottom: 20px; color: #6B6B6B;; text-align: center; font-weight: 200;">
                        Copyright © 2021, Soberlistic. All rights reserved.
                    </p>
                </td>
            </tr>
    	</tbody>
    </table>
</body>
</html>

	