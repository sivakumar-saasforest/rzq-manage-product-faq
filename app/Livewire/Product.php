<?php

namespace App\Livewire;

use App\Models\ProductFaq;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use RzqApplication\Plugin\Store\Product as StoreProduct;

class Product extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductFaq::query())
            ->columns([
                TextColumn::make('product_id'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('edit')
                    ->url(fn (ProductFaq $record): string => route("product-fqz-edit", $record->id)),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(self::formData())
            ->statePath('data');
    }

    public function formData()
    {
        $products = new StoreProduct();
        $products = (object) json_decode($products->all(), true);
        $convertCollection = collect($products->data);

        $options = [];
        foreach ($convertCollection as $value) {
            $options[$value['id']] = $value['name']['en'] ?? $value['name'];
        }

        return [
            Select::make('product_id')
                ->unique(table: ProductFaq::class)
                ->required()
                ->label('Product')
                ->options($options),
            Repeater::make('question_and_answer')->schema([
                TextInput::make('question')->required(),
                TextInput::make('answer')->required(),
            ])
        ];
    }

    public function create()
    {
        $data = $this->form->getState();
        $productFAQ = ProductFaq::create([
            'user_id' => auth()->user()->id,
            'product_id' => $data['product_id'],
        ]);

        foreach ($data['question_and_answer'] as $value) {
            $productFAQ->faqs()->create([
                'question' => $value['question'],
                'answer' => $value['answer']
            ]);
        }

        return redirect()->to('/');
    }

    public function render(): View
    {
        return view('livewire.product');
    }
}
