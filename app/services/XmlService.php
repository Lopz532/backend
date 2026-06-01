<?php
declare(strict_types=1);

class XmlService
{
    public static function arrayToXml(array $data, string $rootNode = 'root'): string
    {
        return XmlHelper::arrayToXml($data, $rootNode);
    }

    public static function xmlToArray(string $xmlContent): array
    {
        return XmlHelper::xmlToArray($xmlContent);
    }
}
