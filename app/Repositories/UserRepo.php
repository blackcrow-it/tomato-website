<?php

namespace App\Repositories;

use App\User;
use DB;
use Exception;

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
            if ($user->money < $money) {
                throw new Exception('User ' . $user->username . ' do not enough money to remove.');
            }

            $user->money -= $money;
            $user->save();
        }, 1);
    }

    public function getFirstByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getFirstByEmailAndCode($email, $code)
    {
        return User::where('email', $email)
            ->where('code',  $code)
            ->first();
    }
}
