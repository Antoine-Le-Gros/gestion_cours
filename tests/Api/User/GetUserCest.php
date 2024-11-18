<?php

namespace App\Tests\Api\User;

use App\Factory\UserFactory;
use Tests\Support\ApiTester;

class GetUserCest
{
    /**
     * @return string[]
     */
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'email' => 'string',
            'roles' => 'array',
            'firstname' => 'string',
            'lastname' => 'string',
            'isActive' => 'boolean',
            'login' => 'string',
            'hoursMax' => 'integer',
        ];
    }

    // tests
    public function AnonymousCanGetUsers(ApiTester $I): void
    {
        UserFactory::createOne();
        $I->sendGet('/api/users/1');
        $I->seeResponseCodeIs(200);
    }
}
