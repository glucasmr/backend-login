<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public static $wrap = null;

     public function toArray($request)
    {
        return [
            'message' => $this->when(isset($this->resource['message']),function(){
                    return $this->resource['message'];
                }),
            'user' => [
                'name' => $this->resource['user']['name'],
                'username' => $this->resource['user']['username'],
                'email' => $this->resource['user']['email'],
            ],
            'token' => $this->when(isset($this->resource['token']), function(){
                return $this->resource['token'];
            })
        ];
    }
}
