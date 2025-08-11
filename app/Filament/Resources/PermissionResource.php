<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'fas-user-gear';
    protected static ?string $navigationGroup = "User Settings";

        public static function getPermissions(): array
    {
        return [
            'view' => 'view.user-admin_panel',
            'viewAny' => 'list.user-admin_panel',
            'create' => 'create.user-admin_panel',
            'update' => 'edit.user-admin_panel',
            'delete' => 'delete.user-admin_panel',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('guard_name')
                ->label('Guard Name')
                ->required()
                ->options([
                'web' => 'Web',
                'api' => 'API',
                ])
                ->default('web'), 
                
                Forms\Components\TextInput::make('description')
                ->label('Description')->columnSpanFull()
                ->maxLength(255),
            ])->columns(2);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('guard_name')->label('Guard'),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
