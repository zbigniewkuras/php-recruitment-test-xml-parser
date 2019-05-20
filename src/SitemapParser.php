<?php
/**
 * Gate-Software
 *
 * @copyright Copyright © {2019} Gate-Software Sp. z o.o.
 ↳ (www.gate-software.com). All rights reserved.
 * @author    Gate-Software Dev Team
 * @author    zbigniew.kuras@gate-software.com
 *
 * @package   XmlParser_SitemapParser
 */
namespace XmlParser;

/**
 * Class SitemapParser
 */
class SitemapParser
{

    /**
     *
     * @var string
     */
    protected $rawData;

    /**
     *
     * @var array
     */
    private $websitesData = [];

    /**
     *
     * @param string $rawData
     * @return \XmlParser\Parser\SitemapParser
     */
    public function setRawData(string $rawData): self
    {
        $this->rawData = $rawData;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     *
     * @return array
     */
    public function getWebsitesData(): array
    {
        if (! $this->websitesData && ! $this->getRawData()) {
            $this->parseData();
        }
        return $this->websitesData;
    }

    public function parseData()
    {
        if ($this->getRawData()) {
            $xmlData = simplexml_load_string($this->getRawData());
            foreach ($xmlData as $data) {
                $url = (string) $data->loc;
                $hostname = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
                $path = parse_url($url, PHP_URL_PATH);
                $this->addUrl($hostname, $path);
            }
        }
        return $this->getWebsitesData();
    }

    /**
     *
     * @param string $hostname
     * @param string $path
     */
    private function addUrl($hostname, $path): void
    {
        if (! isset($this->websitesData[$hostname])) {
            $this->websitesData[$hostname] = [];
        }
        $this->websitesData[$hostname][] = $path;
    }
} 