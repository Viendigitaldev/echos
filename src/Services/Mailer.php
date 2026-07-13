<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

final class Mailer
{
    public static function send(string $to, string $subject, string $body, bool $isHtml = false, ?string $altBody = null): bool
    {
        $host = Setting::get('mail_host');
        $username = Setting::get('mail_username');

        if ($host === '' || $username === '') {
            Logger::info('Mailer: SMTP not configured (host/username missing), skipping send.', ['to' => $to, 'subject' => $subject]);
            return false;
        }

        $fromAddress = Setting::get('mail_from_address') ?: $username;
        $fromName = Setting::get('mail_from_name', 'Echos');
        $encryption = Setting::get('mail_encryption', 'tls');

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = (int) Setting::get('mail_port', '587');
            $mail->SMTPAuth = true;
            $mail->Username = $username;
            $mail->Password = Setting::get('mail_password');
            if ($encryption !== '') {
                $mail->SMTPSecure = $encryption;
            }

            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->isHTML($isHtml);
            $mail->Body = $body;
            if ($isHtml && $altBody !== null) {
                $mail->AltBody = $altBody;
            }

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            Logger::error('Mailer send failed: ' . $mail->ErrorInfo, ['to' => $to, 'subject' => $subject]);
            return false;
        }
    }
}
