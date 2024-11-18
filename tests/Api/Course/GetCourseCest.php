<?php

namespace App\Tests\Api\Course;

use App\Factory\CourseFactory;
use App\Factory\CourseTitleFactory;
use App\Factory\ModuleFactory;
use App\Factory\SemesterFactory;
use App\Factory\TagFactory;
use App\Factory\TypeCourseFactory;
use App\Factory\YearFactory;
use Tests\Support\ApiTester;

class GetCourseCest
{
    /**
     * @return string[]
     */
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'SAESupport' => 'string',
            'groupMaxNumber' => 'int',
        ];
    }

    // tests
    public function AnonymousCanGetCourses(ApiTester $I): void
    {
        YearFactory::createOne();
        SemesterFactory::createOne();
        TagFactory::createOne();
        ModuleFactory::createOne();
        CourseTitleFactory::createOne();
        TypeCourseFactory::createOne();
        CourseFactory::createOne();

        $I->sendGet('/api/courses/1');
        $I->seeResponseCodeIs(200);
    }
}
