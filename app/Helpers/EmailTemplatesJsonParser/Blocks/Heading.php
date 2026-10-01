<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class Heading extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.heading';
    public string $type = BlockTypes::HEADING;

    

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
   
        return view($this->view, [
            'props' => $this->data['props'],
            'style' => $this->parseStyles($this->data['style']),
        ]);
    }
}
