<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Dynamically configure Laravel Mailer using settings from database.
     */
    public function configureMailer(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        $mailer = Setting::get('mail_mailer', 'smtp');
        $host = Setting::get('mail_host', 'smtp.mailtrap.io');
        $port = Setting::get('mail_port', '2525');
        $username = Setting::get('mail_username', '');
        $password = Setting::get('mail_password', '');
        $encryption = Setting::get('mail_encryption', 'tls');
        $fromAddress = Setting::get('mail_from_address', 'info@artisanwood.sa');
        $fromName = Setting::get('mail_from_name', Setting::get('site_name_ar', 'أرتيزان للأعمال الخشبية'));

        Config::set('mail.default', $mailer);
        Config::set("mail.mailers.{$mailer}.host", $host);
        Config::set("mail.mailers.{$mailer}.port", (int)$port);
        Config::set("mail.mailers.{$mailer}.encryption", $encryption ?: null);
        Config::set("mail.mailers.{$mailer}.username", $username);
        Config::set("mail.mailers.{$mailer}.password", $password);
        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.from.name', $fromName);
    }

    /**
     * Send a test email to verify SMTP settings.
     *
     * @param string $recipient
     * @return array
     */
    public function sendTestEmail(string $recipient): array
    {
        try {
            $this->configureMailer();

            $siteName = Setting::get('site_name_ar', 'أرتيزان للأعمال الخشبية والديكور');
            $senderEmail = Setting::get('mail_from_address', 'info@artisanwood.sa');

            Mail::html("
                <div style='font-family: Arial, sans-serif; direction: rtl; text-align: right; background-color: #f8fafc; padding: 30px;'>
                    <div style='max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;'>
                        <div style='background: linear-gradient(135deg, #8B5A2B, #553926); padding: 25px; text-align: center; color: #ffffff;'>
                            <h1 style='margin: 0; font-size: 22px;'>{$siteName}</h1>
                            <p style='margin: 5px 0 0 0; font-size: 13px; color: #f5efe8;'>رسالة اختبار اتصال خادم البريد (SMTP Test)</p>
                        </div>
                        <div style='padding: 30px; color: #334155; line-height: 1.6;'>
                            <h3 style='color: #8B5A2B; margin-top: 0;'>تهانينا! تم التحقق من إعدادات البريد بنجاح 🎉</h3>
                            <p>هذه رسالة اختبارية تم إرسالها من لوحة تحكم موقع الورشة للتأكد من عمل إعدادات خادم البريد الإلكتروني (SMTP) بشكل سليم.</p>
                            <div style='background-color: #f1f5f9; padding: 15px; border-radius: 10px; font-size: 12px; font-family: monospace;'>
                                <strong>تاريخ الإرسال:</strong> " . date('Y-m-d H:i:s') . "<br>
                                <strong>المرسل:</strong> {$senderEmail}<br>
                                <strong>المستلم:</strong> {$recipient}
                            </div>
                        </div>
                        <div style='background-color: #f8fafc; padding: 15px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0;'>
                            جميع الحقوق محفوظة &copy; " . date('Y') . " {$siteName}
                        </div>
                    </div>
                </div>
            ", function ($message) use ($recipient, $siteName) {
                $message->to($recipient)
                    ->subject("تجربة إرسال بريد من منصة {$siteName}");
            });

            return [
                'success' => true,
                'message' => 'تم إرسال البريد التجريبي بنجاح إلى ' . $recipient,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'فشل إرسال البريد: ' . $e->getMessage(),
            ];
        }
    }
}
