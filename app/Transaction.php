<?php

namespace App;


use App\Product;
use App\Buyer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;


class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $transformer = TransactionTransformer::class;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
