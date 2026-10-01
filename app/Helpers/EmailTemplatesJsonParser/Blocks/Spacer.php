<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class Spacer extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.spacer';
    public string $type = BlockTypes::SPACER;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {   
     
        return view($this->view, [
            'props' => $this->data['props'] ?? null,
        ]);
    }
}
