<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;
use Illuminate\Support\Facades\Log;

class Avatar extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.avatar';
    public string $type = BlockTypes::AVATAR;


    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
        return view($this->view, [
            'props' => $this->data['props'],
            'shapeStyles' => $this->getShapeStyles($this->data['props']['shape']),
            'style' => $this->parseStyles($this->data['style']),
        ]);
    }

    public function getShapeStyles($shape)
    {
        switch ($shape) {
            case 'circle':
                return 'border-radius: 100%;';
            case 'rounded':
                return 'border-radius: 8px;';
            case 'square':
                return 'border-radius: 0;';
            default:
                return 'border-radius: 100%';
        }
    }
}
