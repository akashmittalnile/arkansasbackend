<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arkanasas</title>
</head>

<body>

    <table align="center" cellpadding="0" cellspacing="0" width="100%" style="font-family: Helvetica , sans-serif; margin:0px auto 10px auto; vertical-align: top; background: #261313; padding: 20px;">
        <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" width="100%" style="border:5px solid #fff">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding: 30px">
                                <table align="center" cellpadding="0" cellspacing="0" width="100%" style="background: #ffffff;">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="padding: 30px">
                                                <div style="display: flex;align-items: center;">
                                                    <img src="{{ url('upload/business-logo/'.$course->business_logo) }}" alt="" width="100" style="width: 100px;height: auto;">
                                                    <div style="margin-left: 10px;">
                                                        <h3 style="font-size: 25px;font-weight: bold;margin-bottom: 0px;">{{$course->company_name ?? "NA"}}</h3>
                                                        <h5 style="font-size: 20px;font-weight: normal;margin-top: 10px;">{{$course->professional_title ?? "NA"}}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td valign="top" style="padding: 30px">
                                                <img src="{{ asset('assets/website-images/logo.jpg') }}" alt="" style="float: right;" width="200px">
                                            </td>
                                        </tr>
                        </tr>

                    </tbody>
                </table>
                <table align="center" cellpadding="0" cellspacing="0" width="100%" style="background: #ffffff;">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding: 0px 30px 30px 30px">
                                <p style="font-size: 20px;">{{ date('M d, Y', strtotime($date->updated_date)) }}</p>
                            </td>
                            <td></td>
                            <td valign="top" style="padding: 0px 30px 30px 30px">
                                <h6 style="margin: 0 0 10px 0;    text-align: right;color: #2d2f3c;font-size: 16px;font-weight: normal;padding: 0;color: #6A6A6A;">https://arkansaspermanentcosmeticsinstitute.com/</p>
                            </td>
                        </tr>

                        <tr>
                            <td valign="top" style="padding: 0px 30px">
                                <p style="font-size: 23px; margin-bottom: 0px;">This certificate is awarded to</p>
                                <h3 style="font-size: 60px;font-weight: bold; color: #E0B220;margin-top: 10px;">{{ $user->first_name ?? "NA" }} {{ $user->last_name ?? "" }}</h3>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding: 0px 30px">
                                <p style="font-size: 23px;  margin-bottom: 0px;">For completing the requirements of</p>
                                <h5 style="font-size: 35px;font-weight: bold;margin-top: 10px;">{{ $course->title ?? "NA" }}</h5>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table align="center" cellpadding="0" cellspacing="0" width="100%" style="background: #ffffff;">
                    <tbody>
                        <tr style="align-items: center;">
                            <td valign="top" style="padding: 50px 30px 30px 30px; width: 40%;">
                                <img src="{{ url('upload/signature/'.$course->signature) }}" alt="" width="300px">
                                <hr>
                                <p style="font-weight: bold;">Executive Director, Center of supply Chain Research</p>
                                <p>{{ $course->first_name ?? "NA" }} {{ $course->last_name ?? "" }}</p>
                            </td>
                            <td valign="top" style="padding: 50px 30px 30px 30px">
                                <img src="{{ asset('assets/website-images/badge.jpg') }}" alt="" width="200px" style="margin: 0 auto;display: block;">
                            </td>
                            <td valign="top" style="padding: 50px 30px 30px 30px;width: 40%;">
                                <img src="{{ url('upload/signature/'.$admin->signature) }}" alt="" width="300px">
                                <hr>
                                <p style="font-weight: bold;">Associate Dean for Professional Graduate Programs <br> and Executive Education</p>
                                <p>{{ $admin->first_name ?? "NA" }} {{ $admin->last_name ?? "" }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>

        </tr>
        </tbody>
    </table>
    </td>
    </tr>

    </table>



</body>

</html>