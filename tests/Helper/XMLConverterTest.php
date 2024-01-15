<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for XMLConverter
 */

namespace zeroline\MiniLoom\Tests\Helper;

use PHPUnit\Framework\TestCase;
use zeroline\MiniLoom\Helper\XMLConverter;

final class XMLConverterTest extends TestCase
{
    public function testToXMLWithArray()
    {
        $data = [
            'name' => 'John Doe',
            'age' => 30,
            'email' => 'johndoe@example.com'
        ];

        $expectedXml = '<?xml version="1.0"?>
                        <root>
                            <name>John Doe</name>
                            <age>30</age>
                            <email>johndoe@example.com</email>
                        </root>
                        ';

        $xml = XMLConverter::toXML($data);
        $this->assertXmlStringEqualsXmlString($expectedXml, $xml);
    }

    public function testToXMLWithObject()
    {
        $data = new \stdClass();
        $data->name = 'Jane Smith';
        $data->age = 25;
        $data->email = 'janesmith@example.com';

        $expectedXml = '<?xml version="1.0"?>
                            <root>
                                <name>Jane Smith</name>
                                <age>25</age>
                                <email>janesmith@example.com</email>
                            </root>
                            ';

        $xml = XMLConverter::toXML($data);
        $this->assertXmlStringEqualsXmlString($expectedXml, $xml);
    }

    public function testToXMLWithNestedData()
    {
        $data = [
                'name' => 'John Doe',
                'age' => 30,
                'email' => 'johndoe@example.com',
                'address' => [
                        'street' => '123 Main St',
                        'city' => 'New York',
                        'country' => 'USA'
                ]
        ];

        $expectedXml = '<?xml version="1.0"?>
                            <root>
                                <name>John Doe</name>
                                <age>30</age>
                                <email>johndoe@example.com</email>
                                <address>
                                    <street>123 Main St</street>
                                    <city>New York</city>
                                    <country>USA</country>
                                </address>
                            </root>
                            ';

        $xml = XMLConverter::toXML($data);
        $this->assertXmlStringEqualsXmlString($expectedXml, $xml);
    }
}
