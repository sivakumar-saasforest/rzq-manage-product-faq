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
use Illuminate\Contracts\View\View;
use RzqApplication\Plugin\Store\Product as StoreProduct;

class ProductFaqView extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];
    public $product;

    public function mount($id)
    {
        $this->product = ProductFaq::find($id);
        $this->form->fill([
            'product_id' => $this->product->product_id,
            'question_and_answer' => $this->product->faqs->toArray()
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
            ->disabled()
                // ->unique(table: ProductFaq::class, ignoreRecord: true)
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
        
        $this->product->faqs()->delete();

        foreach ($data['question_and_answer'] as $value) {
            $this->product->faqs()->create([
                'question' => $value['question'],
                'answer' => $value['answer']
            ]);
        }

        return redirect()->to('/');
    }

    public function render(): View
    {
        return view('livewire.product-faq-view');
    }
}
