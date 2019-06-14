<?php

namespace Mducharme\DateFormatter;

use DateTime;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;

/**
 * Parser service.
 * Attempts to parse a mixed value, either a DateTime object or a parsable string, into a DateTime object.
 *
 */
final class Parser
{

    /**
     * As a single-purpose service, Parser is invokable.
     *
     * @param DateTimeInterface|string|null $date      The date to parse.
     * @param boolean                       $allowNull If set to false, then passing null argument to this method will throw an exception instead of returning null.
     * @throws InvalidArgumentException If the argument is null when not allowed, the string is an invalid date or the parameter is not a DateTime object.
     * @return DateTimeInterface|null
     */
    public function __invoke($date, $allowNull = true)
    {
        return $this->parse($date, $allowNull);
    }

    /**
     * Ensure any given parameter is parsed into a DateTime format.
     * Also allows null (and therefore may return null) if the allowNull parameter is true (it is true by default).
     *
     * @param string|DateTimeInterface|null $date      The date to parse.
     * @param boolean                       $allowNull If set to false, then passing null argument to this method will throw an exception instead of returning null.
     * @throws InvalidArgumentException If the argument is null when not allowed, the string is an invalid date or the parameter is not a DateTime object.
     * @return DateTimeInterface|null
     */
    public function parse($date, $allowNull = true)
    {
        if ($date === null) {
            if ($allowNull !== true) {
                throw new InvalidArgumentException(
                    'Date is not allowed to be null.'
                );
            }
            return null;
        }

        if (is_string($date)) {
            try {
                $date = new DateTime($date);
            } catch (Exception $e) {
                throw new InvalidArgumentException(
                    'A date could not be parsed from given string.'
                );
            }
        }

        if (!($date instanceof DateTimeInterface)) {
            $null = $allowNull ? 'null,' : '';
            throw new InvalidArgumentException(
                sprintf('Invalid date. Must be %s a parsable string or a DateTime object.', $null)
            );
        }

        return $date;
    }
}
