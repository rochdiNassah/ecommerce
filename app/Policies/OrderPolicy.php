<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the member can update order's status.
     *
     * @param  \App\Models\User  $member
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateStatus(User $member, Order $order)
    {
        if (!isset($member->id) || !isset($order->deliveryDriver->id)) {
            return false;
        }
        
        return $member->id === $order->deliveryDriver->id;
    }
}
