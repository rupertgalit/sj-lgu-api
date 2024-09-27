<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Category_Id' => $this->Category_Id,
            'Category_Name' => $this->Category_Name,
            'Amount' => $this->Amount,
            
        ];
    }
    
}
