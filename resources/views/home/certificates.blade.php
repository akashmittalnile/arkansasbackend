<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='https://fonts.googleapis.com/css?family=Tanger' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=CormorantGaramond' rel='stylesheet'>
	<title>Arkanasas</title>
</head>
<body>
	
 	<table align="center" cellpadding="0" cellspacing="0" width="0%"  style="font-size: 23px; margin:0px auto 10px auto; vertical-align: top; background: url('assets/website-images/bg.jpg');background-position: center;background-repeat: no-repeat;background-size: cover;border: 5px solid white;">
      <tbody>
		<tr>
			<td valign="top" style="padding: 50px">
				<img src="{{ assets('assets/website-images/logo-2.png') }}" alt="" style="width: 350px;height: auto;">
			</td> 
			<td valign="top" style="padding: 30px 50px 60px 50px">
				<table align="center" cellpadding="0" cellspacing="0" width="100%"  style="font-family: Helvetica , sans-serif; margin:0px auto 10px auto; vertical-align: top;">
					<tr>
						<td valign="top" style="padding: 0px 50px">
							<h1 style="color: white;font-weight: bold; text-align: right;color: #ECC94A;font-family: lateef;font-size: 85px;letter-spacing: 10px;margin-bottom: 0;">CERTIFICATE</h1>
							<h1 style="color: white;font-weight: 100; text-align: right;color: #ECC94A;font-family: lateef;font-size: 38px;margin-top: 0;">OF COMPLETION</h1>
							<p style="color: white;text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;margin-bottom: 0;">This certificate is awarded to</p>
							<h1 style="color: white;font-weight: 100; text-align: right;color: #ECC94A;font-family: tangerine;font-size: 95px;margin-top: 0;margin-bottom: 0;letter-spacing: 5px;">{{ $user->first_name ?? "NA" }} {{ $user->last_name ?? "" }}</h1>
							<hr align="right" style="height:2px;border-width:0;color:gray;width: 70%;opacity: 0.5;margin-top: 0; background-color:#ECC94A">
							<p style="color: white;font-weight: 100; text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;">This is certify that [{{ $user->first_name ?? "NA" }} {{ $user->last_name ?? "" }}] has succesfully completed the <br> [{{ $course->title ?? "NA" }}] at Permanent Makeup University. <br>This Achievement is a testament to your hardwork, <br> determination, and passion for lifelong learning. We <br> congratulate you on this remarkable accomplishment and <br> wish you continued success endeavors</p>
						</td>
						<table align="right" cellpadding="0" cellspacing="0" width="90%"  style="margin:0px auto 10px auto; vertical-align: top;">
							<tr>
								<td valign="top" style="padding: 0px;">
									<h1 style="color: white;font-weight: 100; text-align: right;color: #ECC94A;font-family: tangerine;font-size: 48px;margin-top: 0;margin-bottom: 0;">Admin Name</h1>
									<hr align="right" style="height:2px;border-width:0;color:gray;width: 70%;opacity: 0.5;margin-top: 0; background-color:#ECC94A">
									<p style="color: white;text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;margin-bottom: 0;">[{{ $admin->first_name ?? "NA" }} {{ $admin->last_name ?? "" }}]</p>
									<p style="color: white;text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;margin-bottom: 0;font-weight: 100;font-size: 20px;">Administrator</p>
								</td>
								<td valign="top" style="padding: 0px 50px 0px 0px">
									<h1 style="color: white;font-weight: 100; text-align: right;color: #ECC94A;font-family: tangerine;font-size: 48px;margin-top: 0;margin-bottom: 0;">Instructors Name</h1>
									<hr align="right" style="height:2px;border-width:0;color:gray;width: 55%;opacity: 0.5;margin-top: 0; background-color:#ECC94A">
									<p style="color: white;text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;margin-bottom: 0;">[{{ $course->first_name ?? "NA" }} {{ $course->last_name ?? "" }}]</p>
									<p style="color: white;text-align: right;color: #ECC94A;font-family: lateef;margin-top: 0;margin-bottom: 0;font-weight: 100;font-size: 20px;">Mentor</p>
								</td>
							</tr>
						</table>
					</tr>
				</table>
			</td> 
		</tr>
	  </tbody>
	</table>



</body>
</html>