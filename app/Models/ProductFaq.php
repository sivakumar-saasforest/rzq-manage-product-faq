<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function faqs()
    {
        return $this->hasMany(FaqItem::class, 'product_faq_id', 'id');
    }
}
