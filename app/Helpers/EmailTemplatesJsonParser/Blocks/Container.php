<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class Container extends BaseBlock
{
    public string $type = BlockTypes::CONTAINER;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
    }
}
