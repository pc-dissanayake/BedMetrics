<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Pages\Actions\ModalAction;
use Illuminate\Support\Str;
use App\Models\Permission;

class BulkPermissionModal extends ModalAction
{
    public static function make(string $name = 'bulkPermission'): static
    {
        return parent::make($name)
            ->label('Bulk Permission')
            ->modalHeading('Bulk Permission Generator')
            ->form([
                TextInput::make('group_name')
                    ->label('Permission Group Name')
                    ->required(),
                CheckboxList::make('actions')
                    ->label('Available Permissions')
                    ->options([
                        'view' => 'View',
                        'list' => 'List',
                        'create' => 'Create',
                        'edit' => 'Edit',
                        'delete' => 'Delete',
                        'export' => 'Export',
                        'import' => 'Import',
                    ])
                    ->required(),
            ])
            ->action(function (array $data) {
                $group = Str::slug($data['group_name'], '_');
                foreach ($data['actions'] as $action) {
                    $permission = $action . '.' . $group;
                    Permission::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => 'web',
                    ]);
                }
            });
    }
}
