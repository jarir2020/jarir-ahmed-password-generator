<?php

namespace JarirAhmed\PasswordGenerator\Tests;

use JarirAhmed\PasswordGenerator\PasswordGenerator;
use PHPUnit\Framework\TestCase;

class PasswordGeneratorTest extends TestCase
{
    public function testRespectsRequestedLength()
    {
        foreach ([4, 8, 16, 64, 200] as $len) {
            $this->assertSame($len, strlen(PasswordGenerator::generate($len)));
        }
    }

    public function testDefaultLengthInRange()
    {
        $len = strlen(PasswordGenerator::generate());
        $this->assertGreaterThanOrEqual(12, $len);
        $this->assertLessThanOrEqual(16, $len);
    }

    public function testContainsEveryCharacterClass()
    {
        for ($i = 0; $i < 50; $i++) {
            $pw = PasswordGenerator::generate(8);
            $this->assertMatchesRegularExpression('/[a-z]/', $pw, 'missing lowercase');
            $this->assertMatchesRegularExpression('/[A-Z]/', $pw, 'missing uppercase');
            $this->assertMatchesRegularExpression('/[0-9]/', $pw, 'missing digit');
            $this->assertMatchesRegularExpression('/[^a-zA-Z0-9]/', $pw, 'missing symbol');
        }
    }

    public function testRejectsTooShort()
    {
        $this->expectException(\InvalidArgumentException::class);
        PasswordGenerator::generate(3);
    }

    public function testProducesDistinctPasswords()
    {
        $a = PasswordGenerator::generate(20);
        $b = PasswordGenerator::generate(20);
        $this->assertNotSame($a, $b);
    }
}
