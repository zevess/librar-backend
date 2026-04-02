<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Подтверждение почты </title>
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
                                        <p style="margin: 0;">Для подтверждения пароля нажмите на кнопку:</p>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="border-radius: 6px;" bgcolor="#d1ebe9">
                                                    <a href="{{ $verifyUrl }}" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #333333; text-decoration: none; border-radius: 6px; padding: 12px 24px; border: 1px solid #d1ebe9; display: inline-block; font-weight: 600;">
                                                        Подтвердить
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
                                        Если у вас проблемы при клике на кнопку "Подтвердить", скопируйте и вставьте ссылку ниже в ваш браузер:<br>
                                        <a href="{{ $verifyUrl }}" style="color: #a1a1aa; text-decoration: underline;">{{ $verifyUrl }}</a>
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

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Сброс пароля</title>
    <style>
      body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        background: #e7fefc;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        width: 100%;
      }

      main {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-items: center;
        gap: 8px;
        max-width: 40rem;
        padding: 1.25rem;
        margin-top: 1.5rem;
        border-radius: 0.75rem;
        background-color: #ffffff;
        box-shadow:
          0 10px 15px -3px rgba(0, 0, 0, 0.1),
          0 4px 6px -2px rgba(0, 0, 0, 0.05);
      }

      .title {
        margin-top: 1rem;
        margin-bottom: 1rem;
        font-size: 1.875rem;
        line-height: 2.25rem;
        font-weight: 700;
        text-transform: uppercase;
      }

      .link-button {
        align-self: center;
        width: fit-content;
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        background: #d1ebe9;
      }
      .message {
        align-self: flex-start;
      }
      .subcopy {
        width: 100%;
        border-top: 1px solid #e4e4e7;
        margin-top: 25px;
        padding-top: 25px;
        color: #a1a1aa;
      }
      .subcopy a {
        text-decoration: underline;
      }
    </style>
</head>
<body>
    <main>
      <h1 class="title">LIBRAR</h1>
      <div class="message">
        <p>Здравствуйте, !</p>
        <p>Для сброса пароля нажмите на кнопку:</p>
      </div>
      <a class="link-button" href="{{ $resetUrl }}">Сбросить</a>
      <div class="subcopy">
        <p>
          Если у вас проблемы при клике на кнопку "Сбросить", скопируйте и вставьте ссылку ниже в
          ваш браузер: <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
        </p>
      </div>
    </main>
  </body>
</html>
 --}}
