<?php

namespace App\Helpers\EmailTemplatesJsonParser;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlocksFactory;
use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class EmailTemplateParser
{
    private array $json;
    private array $blocks = [];
    public function __construct(string $json)
    {
        $this->json = json_decode($json, true);
    }



    public function render()
    {

        $html = $this->renderAsTableLayout();
        return $html;
    }

    public function renderAsTableLayout()
    {
        $html = '';
        $html .= '
       <html>
       <head>
       <meta title=" ' .  env('APP_NAME') . '">
       </head>
        <table style="width: 600px; margin: 0 auto;">
            <tbody>';
        foreach ($this->blocks as $block_id => $block) {
            $html .= '<tr><td>';
            $html .= $block->parse()->render();
            $html .= '</td></tr>';
        }
        $html .= '</tbody></table></body></html> ';

        return $html;
    }

    public function addTableWrapper() {}

    public function prepareBlocks()
    {
        $blocktypes = [BlockTypes::CONTAINER, BlockTypes::COLUMNS, BlockTypes::EMAIL_LAYOUT, BlockTypes::PROPERTIES_LIST];
        foreach ($this->json as $block_id => $block) {
            if (in_array($block['type'], $blocktypes)) {
                continue;
            }
            $this->blocks[$block_id] = BlocksFactory::createBlock($block['type'], $block['data']);
        }
    }

    public function getJson(): array
    {
        return $this->json;
    }
    public function setBlock(string $id, Block $block)
    {
        $this->blocks[$id] = $block;
    }
    public function getBlock(string $id): Block
    {
        return $this->blocks[$id];
    }

    public function setButtonOfferLink(string $url)
    {

        foreach ($this->blocks as $block) {

            if ($block->type === BlockTypes::OFFER_LINK) {

                $block->data['props']['url'] = $url;
            }
        }
    }
}
