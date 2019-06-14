<?php

namespace Mducharme\DateFormatter;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * Formatter service.
 */
final class Formatter
{
    const ALL = '_ALL_FORMATS';

    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var array The array of possible formats.
     */
    private $customFormats;

    /**
     * @var string
     */
    private $defaultFormat;

    /**
     * DateFormatter constructor.
     * @param Parser $parser        The parser service dependency.
     * @param array  $customFormats Optional, additional possible formats of this date formatter.
     * @param string $defaultFormat The default format,  when not specifying it to the `format()` method.
     */
    public function __construct(Parser $parser, array $customFormats = [], $defaultFormat = 'atom')
    {
        $this->parser = $parser;
        $this->customFormats = $customFormats;
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * As a single-purpose service, the formatter is invokable.
     *
     * @param DateTimeInterface|string $date   The date to format. Will be parsed by the parser.
     * @param string[]|string|null     $format The format (or array of formats) to return.
     * @throws InvalidArgumentException If the format (or one of the format) is not a string or not in the available formats.
     * @return array|string|null
     */
    public function __invoke($date, $format = null)
    {
        return $this->format($date, $format);
    }

    /**
     * @param DateTimeInterface|string $date   The date to format. Will be parsed by the parser.
     * @param string[]|string|null     $format The format (or array of formats) to return.
     * @throws InvalidArgumentException If the format (or one of the format) is not a string or not in the available formats.
     * @return array|string|null
     */
    public function format($date, $format = null)
    {
        $date = $this->parser->parse($date);
        if ($date === null) {
            return null;
        }

        if ($format === null) {
            $format = $this->defaultFormat;
        }

        $allFormats = array_merge($this->defaultFormats(), $this->customFormats);

        if ($format === self::ALL) {
            $format = array_keys($allFormats);
        }

        if (is_string($format)) {
            // Transform a single format
            if (!isset($allFormats[$format])) {
                throw new InvalidArgumentException(
                    sprintf('Not a valid format. %s not found in available formats.', $format)
                );
            }
            $c = $allFormats[$format];
            return $this->transform($date, $c);
        } elseif (is_array($format)) {
            // Transform an array of formats.
            $ret = [];
            foreach ($format as $f) {
                if (!is_string($f)) {
                    throw new InvalidArgumentException(
                        'Not a valid format. Each formats in the array must be a string.'
                    );
                }
                if (!isset($allFormats[$f])) {
                    throw new InvalidArgumentException(
                        sprintf('Not a valid format. %s not found in available formats.', $f)
                    );
                }
                $c = $allFormats[$f];
                $ret[$f] = $this->transform($date, $c);
            }
            return $ret;
        } else {
            throw new InvalidArgumentException(
                'Format must be a string or an array of strings.'
            );
        }
    }

    /**
     * @param DateTimeInterface $date The date to format (with Datestring::format() or with a callback method).
     * @param string|callable   $c    Either a DateTime format string or a callback.
     * @return string|null
     */
    private function transform(DateTimeInterface $date, $c)
    {
        if (is_string($c)) {
            return $date->format($c);
        } elseif (is_callable($c)) {
            return $c($date);
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    private function defaultFormats()
    {
        return [
            'atom'      => DateTime::ATOM,
            'rfc822'    => DateTime::RFC822,
            'iso8601'   => DateTime::ISO8601,
            'cookie'    => DateTime::COOKIE,
            'rss'       => DateTime::RSS,
            'w3c'       => DateTime::W3C,

            'day'       => 'd',
            'month'     => 'm',
            'year'      => 'Y',

            'hour'      => 'H',
            'minute'    => 'i',
            'second'    => 's',

            'timezone'  => 'e',

            'timestamp' => 'U'
        ];
    }
}
