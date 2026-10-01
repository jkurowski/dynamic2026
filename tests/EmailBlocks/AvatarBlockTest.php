<?php

namespace App\Helpers\EmailTemplatesJsonParser\Tests\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Blocks\Avatar;

use PHPUnit\Framework\TestCase;


class AvatarBlockTest extends TestCase
{


    public function testGetShapeStyles()
    {
        $avatar = new Avatar();

        $circleStyles = $avatar->getShapeStyles('circle');
        $this->assertEquals('border-radius: 100%;', $circleStyles);

        $roundedStyles = $avatar->getShapeStyles('rounded');
        $this->assertEquals('border-radius: 8px;', $roundedStyles);

        $squareStyles = $avatar->getShapeStyles('square');
        $this->assertEquals('border-radius: 0;', $squareStyles);

        $defaultStyles = $avatar->getShapeStyles('unknown');
        $this->assertEquals('border-radius: 100%', $defaultStyles);
    }
}
