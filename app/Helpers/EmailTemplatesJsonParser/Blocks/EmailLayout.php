<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Traits\ChildrenTrait;



class EmailLayout extends BaseBlock
{

    private string $view = 'email-templates-json-parser.blocks.email-layout';
    public string $type = BlockTypes::EMAIL_LAYOUT;


    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {   $styleCopy = $this->data['data'];
        $styleCopy['backgroundColor'] = $styleCopy['backdropColor'];

        // dd($this->data['data']);
        return view($this->view );
    }

    private function parseBlocks()
    {
        $output = '';

        return $output;
    }
}
