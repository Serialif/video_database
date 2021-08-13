<?php

namespace App\Tests\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testIsTrue(): void
    {
        $tag = new Tag();

        $tag->setName('name');
        $tag->setBgcolor('bgcolor');
        $tag->setColor('color');

        $this->assertTrue($tag->getName() === 'name');
        $this->assertTrue($tag->getBgcolor() === 'bgcolor');
        $this->assertTrue($tag->getColor() === 'color');
    }

    public function testIsFalse(): void
    {
        $tag = new Tag();

        $tag->setName('name');
        $tag->setBgcolor('bgcolor');
        $tag->setColor('color');

        $this->assertFalse($tag->getName() === 'false');
        $this->assertFalse($tag->getBgcolor() === 'false');
        $this->assertFalse($tag->getColor() === 'false');
    }

    public function testIsEmpty(): void
    {
        $tag = new Tag();

        $this->assertEmpty($tag->getName());
        $this->assertEmpty($tag->getBgcolor());
        $this->assertEmpty($tag->getColor());
    }
}
