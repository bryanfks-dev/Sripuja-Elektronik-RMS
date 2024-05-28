<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Notifikasi Atur Ulang Kata Sandi'))
            ->greeting(Lang::get('Halo') . " {$notifiable->username},")
            ->line(Lang::get('Kamu menerima email ini karena kami menerima permintaan mengatur ulang kata sandi untuk akun milikmu.'))
            ->action(Lang::get('Atur Ulang Kata Sandi'), $this->resetUrl($notifiable))
            ->line(Lang::get('Link ini akan berakhir dalam :count menit.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Jika kamu tidak meminta untuk megatur ulang kata sandi, kamu tidak perlu melakukan tindakan apa-apa.'));
    }

    protected function resetUrl(mixed $notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }

}
