<?php
declare(strict_types=1);

namespace Nfq\Adacemy\Names\Tests;

use PHPUnit\Framework\TestCase;


class NamesTest extends TestCase
{
    private function getLocation() : string
    {
        return realpath(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "SUGGESTED.txt"]));
    }

    /**
     * @dataProvider namesProvider
     */
    public function testNonEmpty($name)
    {
        $this->assertGreaterThan(0, mb_strlen($name));
    }

    /**
     * @dataProvider namesProvider
     */
    public function testNamePattern($name)
    {
        $uppercaseLetters = "AĄBCČDEĘĖFGHIĮYJKLMNOPRSŠTUŲŪVZŽ";
        $lowercaseLetters = "aąbcčdeęėfghiįyjklmnoprsštuųūvzž";

        $match = preg_match("/^[$uppercaseLetters][$lowercaseLetters]*$/u", $name);

        $this->assertSame(1, $match, "Contains non lithuanian letters");
    }

    public function testSorted()
    {
        $collator = new Collator("lt_LT");

        $unsorted = file($this->getLocation());
        $sorted = file($this->getLocation());

        $collator->sort($sorted);

        $this->assertSame($sorted, $unsorted);
    }

    public function namesProvider()
    {
        return array_map(
            function ($name) {
                return [trim($name, "\n")];
            },
            file($this->getLocation())
        );
    }
}
