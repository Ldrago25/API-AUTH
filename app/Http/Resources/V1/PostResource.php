<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'numTicket'=> $this->numTicket,
            'price'=> $this->price,
            'dateGame'=>$this->dateGame,
            'images'=>$this->raffles
            /*'author'=>[
                'name'=>$this->user->name,
                'email'=>$this->user->email
            ]*/
        ];
    }
}
