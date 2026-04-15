<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Отслеживаемая книга снова доступна</title>
</head>
<body style="margin: 0; padding: 0; background-color: #e7fefc; font-family: Helvetica, Arial, sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">       
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 640px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);" class="wrapper">
                    
                    <tr>
                        <td class="padding" style="padding: 30px 40px; text-align: center;">
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-size: 30px; line-height: 36px; font-weight: 700; text-transform: uppercase; color: #333333; padding-bottom: 20px;">
                                        {{ config('app.name') }}
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="left" style="font-size: 16px; line-height: 24px; color: #333333; padding-bottom: 20px;">
                                        <p style="margin: 0 0 10px 0;">Здравствуйте!</p>
                                        <p style="margin: 0;">Отслеживаемая вами книга <b>{{ $bookTitle }}</b> снова доступна для бронирования.</p>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="border-radius: 6px;" bgcolor="#d1ebe9">
                                                    <a href="{{ $bookUrl }}" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #333333; text-decoration: none; border-radius: 6px; padding: 12px 24px; border: 1px solid #d1ebe9; display: inline-block; font-weight: 600;">
                                                        Перейти
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 30px; border-top: 1px solid #e4e4e7; padding-top: 20px;">
                                <tr>
                                    <td align="center" style="font-size: 12px; line-height: 18px; color: #a1a1aa;">
                                        Если у вас проблемы при клике на кнопку "Перейти", скопируйте и вставьте ссылку ниже в ваш браузер:<br>
                                        <a href="{{ $bookUrl }}" style="color: #a1a1aa; text-decoration: underline;">{{ $bookUrl }}</a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
