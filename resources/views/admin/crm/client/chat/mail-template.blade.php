<html>
<head>
    <title>{{ config('app.name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>a{text-decoration: none}table img{display: block;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;}table tr{border-collapse: collapse;}table p{margin: 0;-webkit-text-size-adjust: none;-ms-text-size-adjust: none;mso-line-height-rule: exactly;line-height: 190%}</style></head>
<body style="background-color:#18263e;padding:0;margin:0">
<table align="center" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%;padding:0;border-collapse:collapse;background-color:#18263e">
    <tr>
        <td style="padding:30px">
            <table align="center" cellpadding="0" cellspacing="0" border="0" width="660" style="margin:0 auto;width:600px;padding:0;border-collapse:collapse">
                <tr>
                    <td align="center">
                        <img style="width:190px;height:55px;border:0" width="190" height="55" border="0" src="https://www.developro.pl/images/logo-biale.png" alt="DeveloPro">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding:0 30px">
            <table align="center" cellpadding="0" cellspacing="0" border="0" width="660" style="margin:0 auto;width:660px;padding:0;border-collapse:collapse;background:white">
                <tr>
                    <td style="font-family:Arial;font-size:14px;padding:25px 30px;line-height:24px">
                        <p style="text-align:center">{{ config('app.name') }}</p>
                        <p><b>Wiadomość wysłana: <?= date("d.m.Y - H:i:s"); ?> ze strony: {{ $request->page }}</b></p>
                        <hr style="border:0;border-bottom:1px solid #ececec" />
                        <p><b>Imię i nazwisko:</b> {{ $request->name }}</p>
                        @if($request->email)<p style="margin:0"><b>E-mail:</b> {{ $request->email }}</p>@endif
                        @isset($request->phone)<p style="margin:0"><b>Telefon:</b> {{ $request->phone }}</p>@endisset
                        @if(!empty($investmentName) || isset($property))
                        <hr style="border:0;border-bottom:1px solid #ececec" />
                        @if(!empty($investmentName))<p style="margin:0"><b>Inwestycja:</b> {{ $investmentName }}</p>@endif
                        @isset($property->building->name)<p style="margin:0"><b>Budynek:</b> {{ $property->building->name }}</p>@endisset
                        @isset($property->name)<p style="margin:0"><b>Lokal:</b> {{ $property->name }}</p>@endisset
                        @endif
                        <hr style="border:0;border-bottom:1px solid #ececec" />
                        <p>{{ $request->message }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @if($signature)
    <tr>
        <td style="padding:0 30px">
            <table align="center" cellpadding="0" cellspacing="0" border="0" width="660" style="margin:0 auto;width:660px;padding:0;border-collapse:collapse;background:white">
                <tr>
                    <td style="padding:0 30px 25px">
                        {!! nl2br($signature) !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif
    <tr>
        <td style="height: 100px" height="100">&nbsp;</td>
    </tr>
</table>
</body>
</html>
