<?php

namespace App\Filament\Auth;

use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;

class CustomRequestResetPassword extends BaseRequestPasswordReset
{
    public function request(): void
    {
        try {
            $this->rateLimit(3);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('Aksi mengatur ulang kata sandi sudah mencapai batas maksimal.', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/request-password-reset.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();
        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $data,
            function (CanResetPassword $user, string $token): void {
                if (!method_exists($user, 'notify')) {
                    $userClass = $user::class;
                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }

                $notification = new ResetPasswordNotification($token);
                $user->notify($notification);
            },
        );

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title('Gagal mengirim permintaan mengatur ulang kata sandi.')
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Permintaan mengatur ulang kata sandi telah dikirim.')
            ->success()
            ->send();

        $this->form->fill();
    }
}
