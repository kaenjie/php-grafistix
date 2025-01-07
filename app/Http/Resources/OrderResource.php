<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public $status;
    public $message;

    /**
     * __construct
     *
     * @param  bool $status
     * @param  string $message
     * @param  mixed $resource
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * toArray
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => [
                'id'            => $this->id,
                'full_name'     => $this->full_name,
                'email'         => $this->email,
                'address'       => $this->address,
                'city'          => $this->city,
                'payment_method'=> $this->payment_method,
                'has_paid'      => $this->has_paid,
                'order_date'    => $this->order_date,
                'package'       => new PackageResource($this->whenLoaded('package')),
            ]
        ];
    }
}
