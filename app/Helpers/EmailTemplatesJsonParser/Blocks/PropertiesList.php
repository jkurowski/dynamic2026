<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class PropertiesList extends BaseBlock
{

    private string $view = 'email-templates-json-parser.blocks.properties-list';

    public array $wrapperStyles = ['backgroundColor', 'textAlign', 'padding'];

    public string $type = BlockTypes::PROPERTIES_LIST;




    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
        return view($this->view, [
            'props' => $this->data['props'],
        ]);
    }
}
