<?php

/**
 * TechDivision\Import\Serializers\ValueCsvSerializer
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Serializers;

/**
 * Serializer to serialize/unserialize simple column values.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import
 * @link      http://www.techdivision.com
 */
class ValueCsvSerializer extends AbstractCsvSerializer
{

    /**
     * The delimiter to override the one from the configuration with.
     *
     * @var string
     */
    private $delimiter;

    /**
     * The delimiter to use instead the one from the configuration.
     *
     * @param string $delimiter The delimiter
     *
     * @return void
     */
    protected function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Returns the delimiter to use instead the one from the configuration.
     *
     * @return string The delimiter
     */
    protected function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Serializes the elements of the passed array by using the
     * instances CSV configuration options.
     *
     * @param array|null $unserialized The serialized data
     *
     * @return string|null The serialized array
     */
    public function serialize(array $unserialized = null)
    {

        // do nothing, if the passed value is empty or NULL
        if ($unserialized === null || $unserialized === '') {
            return;
        }

        // load the global configuration
        $configuration = $this->getConfiguration();

        // initializet delimiter, enclosure and escape char
        $delimiter = $this->getDelimiter() ? $this->getDelimiter() : $configuration->getDelimiter();
        $enclosure = $configuration->getEnclosure();
        $escape = $configuration->getEscape();

        // create hte callback method to enclose/escape the values
        $callback = function ($value) use ($enclosure, $escape) {
            return $enclosure . str_replace($escape, $escape . $escape, $value) . $enclosure;
        };

        // implode and return the enclosed/escaped values
        return implode($delimiter, array_map($callback, $unserialized));
    }

    /**
     * Unserializes the elements of the passed value by using the
     * instances CSV configuration options.
     *
     * @param string|null $serialized The value to unserialize
     *
     * @return array|null The exploded values
     */
    public function unserialize($serialized = null)
    {

        // do nothing, if the passed value is empty or NULL
        if ($serialized === null || $serialized === '') {
            return;
        }

        // load the global configuration
        $configuration = $this->getConfiguration();

        // initializet delimiter, enclosure and escape char
        $delimiter = $this->getDelimiter() ? $this->getDelimiter() : $configuration->getDelimiter();
        $enclosure = $configuration->getEnclosure();
        $escape = $configuration->getEscape();

        // parse and return the found data as array
        return str_getcsv($serialized, $delimiter, $enclosure, $escape);
    }

    /**
     * Extracts the elements of the passed value by exploding them
     * with the also passed delimiter.
     *
     * @param string|null $value     The value to extract
     * @param string|null $delimiter The delimiter used to extrace the elements
     *
     * @return array|null The exploded values
     * @see \TechDivision\Import\Serializers\ValueCsvSerializer::unserialize()
     */
    public function explode($value = null, $delimiter = null)
    {

        // set the delimiter
        $this->setDelimiter($delimiter);

        // unserialize the value and return it
        return $this->unserialize($value);
    }

    /**
     * Compacts the elements of the passed value by imploding them
     * with the also passed delimiter.
     *
     * @param array|null  $value     The values to compact
     * @param string|null $delimiter The delimiter use to implode the values
     *
     * @return string|null The compatected value
     * @see \TechDivision\Import\Serializers\ValueCsvSerializer::serialize()
     */
    public function implode(array $value = null, $delimiter = null)
    {

        // set the delimiter
        $this->setDelimiter($delimiter);

        // serialize the value and return it
        return $this->serialize($value);
    }
}
