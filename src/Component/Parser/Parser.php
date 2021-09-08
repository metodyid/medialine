<?php


namespace App\Component\Parser;

/**
 * Class Parser
 * @package App\Component\Parser
 */

class Parser
{

    /**
     * @var \App\Component\Parser\ParserInterface
     */
    private $parser;
    /**
     * @var
     */
    private $resource;
    private $projectDir;

    /**
     * ParserRBK constructor.
     * @param String $url
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @param string $resource
     * @return $this
     */
    public function getResource(string $resource) {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        if (file_exists($this->projectDir . "/src/Component/Parser/Parser" . ucfirst($this->resource) . ".php")) {
            $className = 'App\Component\Parser\Parser' . ucfirst($this->resource);
            $this->parser = new $className();
            return $this->parser->parse();
        }else {
            return [];
        }
    }
}