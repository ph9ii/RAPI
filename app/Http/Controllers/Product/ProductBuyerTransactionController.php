<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;


class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
        $this->middleware('scope:purchase-product')->only(['store']);
        $this->middleware('can:purchase,buyer')->only('store');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $buyer
     * @param  \App\Product  $product
     * @param  \App\Product  $product
     * @return \Illuminate\Support\Facades\DB
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        if($product->seller_id == $buyer->id) {
            return $this->errorResponse('Buyer must be different from the seller', 409);
        }

        if($product->quantity < $request->quantity) {
            return $this->errorResponse('Purchase quantity is larger than the product quantity', 409);
        }

        if(!$buyer->isVerified()) {
            return $this->errorResponse('Buyer is not verified', 409);
        }

        if(!$product->seller->isVerified()) {
            return $this->errorResponse('Seller must be verified', 409);
        }

        if(!$product->isAvailable()) {
            return $this->errorResponse('Product is not available', 409);
        }


        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'product_id' => $product->id,
                'buyer_id' => $buyer->id,
            ]);

            return $this->showOne($transaction, 201);
        });

    } 

  }