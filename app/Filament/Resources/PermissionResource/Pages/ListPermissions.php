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
                ->label('Add Bulk Permissions')
                ->modalHeading('Bulk Permission Generator')
                ->form([
                    \Filament\Forms\Components\TextInput::make('permission_model')
                        ->label('Permission Model')
                        ->required()-
                        ->helperText('E.g. User, Product, etc.'),
                    \Filament\Forms\Components\Select::make('panel')
                        ->label('Panel')
                        ->options([
                            'admin_panel' => 'Admin Panel',
                            'app_panel' => 'App Panel',
                        ])
                        ->default('admin_panel')
                        ->required(),
                    \Filament\Forms\Components\CheckboxList::make('actions')
                        ->label('Available Permissions')
                        ->options([
                            'view' => 'View',
                            'list' => 'List',
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
                    \Filament\Forms\Components\TextInput::make('description')
                        ->label('Description (optional)')
                        ->nullable(),
                ])
                ->action(function (array $data) {
                    $model = str_replace(' ', '_', strtolower($data['permission_model']));
                    $panel = str_replace(' ', '_', strtolower($data['panel']));
                    $adminRole = \App\Models\Role::where('name', 'admin')->first();
                    foreach ($data['actions'] as $action) {
                        $permission = $action . '.' . $model . '-' . $panel;
                        $perm = \App\Models\Permission::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => 'web',
                        ], [
                            'description' => ucwords($action)." : ".$data['description'] ?? null,
                        ]);
                        if ($adminRole && !$adminRole->hasPermissionTo($perm)) {
                            $adminRole->givePermissionTo($perm);
                        }
                    }
                }),
        ];
    }
}
