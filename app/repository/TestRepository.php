<?php
declare(strict_types = 1);
/**
 * The file is part of Dcr/framework
 */

namespace app\repository;

//不能用laravel的门面 Illuminate\Support\Facades\DB;
use app\model\UserModel;
use Illuminate\Support\Collection;
use support\Db;

class TestRepository
{
    public function fromRepos(): void
    {
        echo PHP_EOL;
        echo 'test Di';
    }

    public function test(): Collection
    {
        $users = UserModel::query()->where('id', '>', 1)
            ->orderBy('id', 'desc')->get(['id']);
        //        $allProject = objToArray($allProject);
        debug($users);
        return $users;
    }

    public static function test2(): bool
    {
        return true;
    }
}
