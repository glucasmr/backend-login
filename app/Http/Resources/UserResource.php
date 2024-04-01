<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(
 *             property="id",
 *             type="integer",
 *             description="ID do usuário"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             description="Nome do usuário"
 *         ),
 *         @OA\Property(
 *             property="username",
 *             type="string",
 *             description="Nome de usuário"
 *         ),
 *         @OA\Property(
 *             property="email",
 *             type="string",
 *             format="email",
 *             description="Endereço de email do usuário"
 *         ),
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="Mensagem de resposta"
 *     ),
 *     @OA\Property(
 *         property="token",
 *         type="string",
 *         description="Token de autenticação do usuário"
 *     ),
 * )
 */
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
