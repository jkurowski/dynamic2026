<?php

namespace App\Helpers\EmailTemplatesJsonParser\Traits;

use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

trait ChildrenTrait
{
    protected $children = [];

    public function addChild(Block $block)
    {
        $this->children[] = $block;
    }

    public function removeChild(Block $block)
    {
        $this->children = array_filter($this->children, function ($child) use ($block) {
            return $child !== $block;
        });
    }

    public function renderChildren()
    {
        $output = '';
        foreach ($this->children as $child) {
            $output .= $child->parse();
        }
        return $output;
    }
}
