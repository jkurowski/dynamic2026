<?php

namespace App\Helpers\EmailTemplatesJsonParser;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlocksFactory;
use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;
use App\Helpers\EmailTemplatesJsonParser\Blocks\Columns;

class WebTemplateParser
{

    private array $json;
    private array $blocks = [];
    public function __construct(string $json)
    {
        $this->json = json_decode($json, true);
    }

    public function render()
    {
        $html = '';
        foreach ($this->blocks as $block_id => $block) {
            // Check if the block is not part of a column
            if (!$this->isBlockInColumn($block_id)) {
                $html .= $block->parse()->render();
            }
        }
        return $html;
    }

    private function isBlockInColumn($block_id)
    {
        foreach ($this->blocks as $block) {
            if ($block instanceof Columns) {
                if ($block->containsBlock($block_id)) {
                    return true;
                }
            }
        }
        return false;
    }





    public function prepareBlocks()
    {
        $blocktypes = [BlockTypes::CONTAINER, BlockTypes::EMAIL_LAYOUT, BlockTypes::ATTACHMENTS_LIST, BlockTypes::OFFER_LINK, BlockTypes::CLIENT_PANEL_LINK];
        foreach ($this->json as $block_id => $block) {
            if (in_array($block['type'], $blocktypes)) {
                continue;
            }

            if ($block['type'] === BlockTypes::COLUMNS) {
                $this->blocks[$block_id] = BlocksFactory::createBlock($block['type'], $block['data'], $this->json);
            } else {
                $this->blocks[$block_id] = BlocksFactory::createBlock($block['type'], $block['data']);
            }
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

    public function prepeareOfferList(string $html)
    {
        foreach ($this->blocks as $block) {
            if ($block->type === BlockTypes::PROPERTIES_LIST) {
                $block->data['props']['placeholder'] = $html;
            }
        }
    }
}
