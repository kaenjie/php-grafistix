<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    // define properties
    public $status;
    public $message;
    public $resource;

    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @return void
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * toArray
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'success'   => $this->status,
            'message'   => $this->message,
            'data'      => [
                'id'            => $this->id,
                'full_name'     => $this->full_name,
                'email'         => $this->email,
                'address'       => $this->address,
                'city'          => $this->city,
                'paket'         => $this->paket,
                'payment_method'=> $this->payment_method,
                'has_paid'      => $this->has_paid,
                'order_date'    => $this->order_date,
                'package'       => new PackageResource($this->whenLoaded('package'))
            ]
        ];
    }
}
