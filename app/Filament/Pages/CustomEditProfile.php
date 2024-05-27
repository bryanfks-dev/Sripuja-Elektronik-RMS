<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\EditProfile;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;

class CustomEditProfile extends EditProfile
{
    protected function getForms(): array
    {
        $adminSchema = [
            $this->getUsernameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ];

        $karyawanSchema = [
            $this->getUsernameFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ];

        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema(auth()->user()->isAdmin()
                        ? $adminSchema : $karyawanSchema)
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(!static::isSimple()),
            ),
        ];
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->required()
            ->readOnly();
    }
}
