<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('bulkPermission')
                ->label('Bulk Permission')
                ->modalHeading('Bulk Permission Generator')
                ->form([
                    \Filament\Forms\Components\TextInput::make('group_name')
                        ->label('Permission Group Name')
                        ->required()
                        ->helperText('Seperate Panel from "-". eg: User-Admin_Panel'),
                    \Filament\Forms\Components\CheckboxList::make('actions')
                        ->label('Available Permissions')
                        ->options([
                            'view' => 'View', //view self
                            'list' => 'List', //view all
                            'create' => 'Create',
                            'edit' => 'Edit',
                            'delete' => 'Delete',
                            'bulkdelete' => 'Bulk Delete',
                            'export' => 'Export',
                            'import' => 'Import',
                            'approve' => 'Approve',
                            'reject' => 'Reject',
                            'archive' => 'Archive',
                            'restore' => 'Restore',
                        ])
                        ->columns(5)
                        ->default(['view', 'list', 'create', 'edit', 'delete'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $group = \Illuminate\Support\Str::slug($data['group_name'], '_');
                    $adminRole = \App\Models\Role::where('name', 'admin')->first();
                    foreach ($data['actions'] as $action) {
                        $permission = $action . '.' . $group;
                        $perm = \App\Models\Permission::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => 'web',
                        ]);
                        if ($adminRole && !$adminRole->hasPermissionTo($perm)) {
                            $adminRole->givePermissionTo($perm);
                        }
                    }
                }),
        ];
    }
}
