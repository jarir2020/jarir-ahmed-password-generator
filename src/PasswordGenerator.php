<?php

namespace JarirAhmed\PasswordGenerator;

class PasswordGenerator
{
    const LOWER = 'abcdefghijklmnopqrstuvwxyz';
    const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const DIGITS = '0123456789';
    const SYMBOLS = '!@#$%^&*()_-+=<>?';

    /** Minimum length needed to guarantee one of each character class. */
    const MIN_LENGTH = 4;

    /**
     * Generate a cryptographically secure password containing at least one lowercase,
     * uppercase, digit and symbol.
     *
     * @param int|null $length Desired length. Default: random 12–16. Minimum 4.
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function generate($length = null)
    {
        if ($length === null) {
            $length = random_int(12, 16);
        }
        $length = (int) $length;
        if ($length < self::MIN_LENGTH) {
            throw new \InvalidArgumentException(
                'Password length must be at least ' . self::MIN_LENGTH . '.'
            );
        }

        $classes = [self::LOWER, self::UPPER, self::DIGITS, self::SYMBOLS];
        $all = implode('', $classes);

        // Guarantee one character from each class...
        $chars = [];
        foreach ($classes as $class) {
            $chars[] = $class[random_int(0, strlen($class) - 1)];
        }
        // ...then fill the remainder from the full set.
        for ($i = count($chars); $i < $length; $i++) {
            $chars[] = $all[random_int(0, strlen($all) - 1)];
        }

        return self::secureShuffle($chars);
    }

    /** Fisher–Yates shuffle using a CSPRNG, so the guaranteed chars aren't always first. */
    private static function secureShuffle(array $chars)
    {
        for ($i = count($chars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $tmp = $chars[$i];
            $chars[$i] = $chars[$j];
            $chars[$j] = $tmp;
        }
        return implode('', $chars);
    }
}
