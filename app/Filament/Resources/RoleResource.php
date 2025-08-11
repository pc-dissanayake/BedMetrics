<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'fas-user-tag';

    protected static ?string $navigationGroup = "User Settings";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Fieldset::make('Permissions')
                    ->schema([
                        // Forms\Components\Actions::make([
                        //     Forms\Components\Actions\Action::make('addAllPermissions')
                        //         ->label('Add All')
                        //         ->visible(fn ($livewire) => $livewire->record?->id == 1)
                        //         ->action(function ($set) {
                        //             $set('permissions', \App\Models\Permission::pluck('id')->toArray());
                        //         }),
                        // ])->columnSpanFull(),
                        Forms\Components\CheckboxList::make('permissions')
                            ->relationship('permissions', 'name')
                            ->columnSpanFull()
                            ->searchable()
                            ->bulkToggleable()
                            ->label('Permissions')
                            ->options(function () {
                                return \App\Models\Permission::all()->mapWithKeys(function ($permission) {
                                    $parts = explode('.', $permission->name);
                                    $action = isset($parts[0]) ? ucfirst($parts[0]) : $permission->name;
                                    $rest = isset($parts[1]) ? $parts[1] : '';
                                    $modelPanel = explode('-', $rest);
                                    $model = isset($modelPanel[0]) ? str_replace('_', ' ', ucfirst($modelPanel[0])) : '';
                                    $panel = isset($modelPanel[1]) ? str_replace('_', ' ', ucfirst($modelPanel[1])) : '';
                                    $label = '<span>' . $action . '</span>';
                                    if ($model || $panel) {
                                        $label .= ' : <span class="text-blue-600" style="color: #2563eb;">' . trim($model) . '</span>';
                                        if ($panel) {
                                            $label .= ' | <span class="text-green-600" style="color: #16a34a;">' . trim($panel) . '</span>';
                                        }
                                    }
                                    return [$permission->id => $label];
                                });
                            })
                            ->descriptions(function () {
                                return \App\Models\Permission::all()->mapWithKeys(function ($permission) {
                                    return [$permission->id => $permission->description];
                                });
                            })
                            ->columns(3)
                            ->allowHtml()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                // If admin (id=1), preselect all permissions
                                if ($record && $record->id == 1) {
                                    $component->state(\App\Models\Permission::pluck('id')->toArray());
                                }
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
