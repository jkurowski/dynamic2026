<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class Html extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.html';
    public string $type = BlockTypes::HTML;



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
