<?php

namespace App\Repositories;

use App\User;
use DB;

class UserRepo
{
    public function setMoney($userId, $money)
    {
        $money = intval($money);

        if ($money < 0) {
            $money = 0;
        }

        DB::transaction(function () use ($userId, $money) {
            $user = User::lockForUpdate()->findOrFail($userId);
            $user->money = $money;
            $user->save();
        }, 1);
    }

    public function addMoney($userId, $money)
    {
        $money = intval($money);

        if ($money < 0) {
            $money = 0;
        }

        DB::transaction(function () use ($userId, $money) {
            $user = User::lockForUpdate()->findOrFail($userId);
            $user->money += $money;
            $user->save();
        }, 1);
    }

    public function removeMoney($userId, $money)
    {
        $money = intval($money);

        if ($money < 0) {
            $money = 0;
        }

        DB::transaction(function () use ($userId, $money) {
            $user = User::lockForUpdate()->findOrFail($userId);
            $user->money -= $money;
            $user->save();
        }, 1);
    }
}
