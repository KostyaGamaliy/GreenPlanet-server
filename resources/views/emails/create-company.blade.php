<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Заявка на створення компанії</title>
</head>
<body>
<h1 style="color: #333333; font-size: 24px; font-family: Arial, sans-serif;">Добрий день, {{$emailData['recipient']['full_name']}}!</h1>
<p style="color: #666666; font-size: 16px; font-family: Arial, sans-serif;">Адміністрація розглянула вашу заявку на створення компанії <strong>{{$emailData['company']['name']}}</strong>.</p>

@if($emailData['isStore'])
    <p style="color: #666666; font-size: 16px; font-family: Arial, sans-serif;">Ваша заявку було прийнято за датою {{ \Carbon\Carbon::now()->addHours(3)->format('Y-m-d H:i:s') }}.</p>
    <p style="color: #666666; font-size: 16px; font-family: Arial, sans-serif;">Сподіваємося, у вас все вийде. Ваша адміністрація!</p>
@else
    <p style="color: #666666; font-size: 16px; font-family: Arial, sans-serif;">Ваша заявку було відхилено. Спробуйте ще раз!</p>
@endif
</body>
</html>

