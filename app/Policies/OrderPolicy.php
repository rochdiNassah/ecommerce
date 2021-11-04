<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the member can update order's status.
     *
     * @param  \App\Models\Member  $member
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateStatus(Member $member, Order $order)
    {
        if (!isset($member->id) || !isset($order->deliveryDriver->id)) {
            return false;
        }
        
        return $member->id === $order->deliveryDriver->id;
    }
}
