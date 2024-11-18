<?php

namespace App\Tests\Api\Course;

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
}
