<?php

namespace Yemenpoint\FilamentCustomFields\Resources;

use Closure;
use Yemenpoint\FilamentCustomFields\CustomFields\FilamentCustomFieldsHelper;
use Yemenpoint\FilamentCustomFields\Models\CustomField;
use Yemenpoint\FilamentCustomFields\Resources\CustomFieldResource\Pages;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomFieldResource extends Resource
{
    protected static ?string $model = CustomField::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationGroup(): ?string
    {
        return config("filament-custom-fields.navigation_group", parent::getNavigationGroup());
    }

    public static function getPluralModelLabel(): string
    {
        return config("filament-custom-fields.custom_fields_label", parent::getPluralModelLabel()); // TODO: Change the autogenerated stub
    }

    public static function getModelLabel(): string
    {
        return config("filament-custom-fields.custom_fields_label", parent::getModelLabel()); // TODO: Change the autogenerated stub
    }


//    protected static ?Closure $canAccess = null;
//
//    protected static function canAccessPage(): bool
//    {
//        return static::$canAccess && (static::$canAccess)(auth()->user());
//    }
//
//    public function mount()
//    {
//        abort_unless(static::canAccessPage(), 403);
//    }
//
//    public static function canAccess(Closure $callback): void
//    {
//        static::$canAccess = $callback;
//    }
//
//    protected static function shouldRegisterNavigation(): bool
//    {
//        return static::canAccessPage();
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Select::make('type')->options(FilamentCustomFieldsHelper::getTypes())->default("text")->required(),
                        Forms\Components\Select::make('model_type')->options(config("filament-custom-fields.models"))->required(),
                        Forms\Components\TextInput::make('default_value'),
                        Forms\Components\TextInput::make('column_span')->default("sm:2"),
                        Forms\Components\TextInput::make('order')->numeric()->default(1),
                        Forms\Components\TextInput::make('rules'),
                        Forms\Components\Toggle::make('required')->default(true),
                        Forms\Components\Toggle::make('show_in_columns')->default(true),
                        Forms\Components\KeyValue::make('answers'),
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")->sortable(),
                Tables\Columns\TextColumn::make("order")->sortable(),
                Tables\Columns\TextColumn::make("title"),
                Tables\Columns\TextColumn::make("type"),
                Tables\Columns\TextColumn::make("model_type")->formatStateUsing(function ($state) {
                    $display = $state;
                    foreach (config("filament-custom-fields.models") as $key => $value) {
                        if ($value == $state) {
                            $display = $key;
                            break;
                        }
                    }
                    return $display;
                }),
                Tables\Columns\TextColumn::make("rules"),
                Tables\Columns\BooleanColumn::make("required"),
                Tables\Columns\BooleanColumn::make("show_in_columns"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCustomFields::route('/'),
            'create' => Pages\CreateCustomField::route('/create'),
            'edit' => Pages\EditCustomField::route('/{record}/edit'),
        ];
    }
}
