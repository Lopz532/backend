<?php
declare(strict_types=1);

class XmlHelper
{
    public static function arrayToXml(array $data, string $rootNode = 'root'): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><' . $rootNode . '/>');
        self::appendArray($xml, $data);

        return $xml->asXML() ?: '';
    }

    public static function xmlToArray(string $xmlContent): array
    {
        if (trim($xmlContent) === '') {
            return [];
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml === false) {
            return [];
        }

        return json_decode(json_encode($xml, JSON_UNESCAPED_UNICODE), true) ?: [];
    }

    private static function appendArray(SimpleXMLElement $xml, array $data): void
    {
        foreach ($data as $key => $value) {
            $nodeName = is_string($key) ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key) : 'item';
            $nodeName = $nodeName !== '' ? $nodeName : 'item';

            if (is_array($value)) {
                $child = $xml->addChild($nodeName);
                self::appendArray($child, $value);
                continue;
            }

            $xml->addChild($nodeName, htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        }
    }
}
