<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('changePassword')
                ->label('Change Password')
                ->modalHeading('Change User Password')
                ->form([
                    \Filament\Forms\Components\TextInput::make('password')
                        ->label('New Password')
                        ->password()
                        ->required()
                        ->minLength(8),
                    \Filament\Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirm Password')
                        ->password()
                        ->required()
                        ->same('password'),
                ])
                ->action(function (array $data, $record) {
                    $record->password = bcrypt($data['password']);
                    $record->save();
                })
                ->successNotificationTitle('Password changed successfully!'),
        ];
    }
}
