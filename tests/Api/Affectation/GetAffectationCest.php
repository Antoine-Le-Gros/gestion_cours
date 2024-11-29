<?php

namespace App\Tests\Api\Affectation;

use App\Factory\AffectationFactory;
use App\Factory\CourseFactory;
use App\Factory\CourseTitleFactory;
use App\Factory\ModuleFactory;
use App\Factory\SemesterFactory;
use App\Factory\TagFactory;
use App\Factory\TypeCourseFactory;
use App\Factory\UserFactory;
use App\Factory\YearFactory;
use Tests\Support\ApiTester;

class GetAffectationCest
{
    protected static function before(): void
    {
        YearFactory::createOne();
        SemesterFactory::createOne();
        TagFactory::createOne();
        ModuleFactory::createOne();
        CourseTitleFactory::createOne();
        TypeCourseFactory::createOne();
        CourseFactory::createOne();
        UserFactory::createOne();
    }

    /**
     * @return string[]
     */
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'numberGroupTaken' => 'integer',
        ];
    }

    protected static function getAffectation(ApiTester $I): void
    {
        AffectationFactory::createOne();
        $I->sendGet('/affectations/1');

        $I->seeResponseCodeIs(200);
    }
}
