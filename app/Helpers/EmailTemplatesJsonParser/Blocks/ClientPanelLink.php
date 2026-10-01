<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class ClientPanelLink extends BaseBlock
{

    private string $view = 'email-templates-json-parser.blocks.client-panel-link';

    public string $type = BlockTypes::CLIENT_PANEL_LINK;

    public array $wrapperStyles = ['backgroundColor', 'textAlign', 'padding'];


    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {


        return view($this->view, [
            'props' => $this->data['props'],
            'wrapperStyles' => $this->parseStyles($this->getWrapperStyles()),
            'buttonStyles' => $this->parseStyles($this->getButtonStyles()),
        ]);
    }

    public function getButtonShapeStyle($shape)
    {
        switch ($shape) {
            case 'rectangle':
                return ['border-radius' => '0'];
            case 'rounded':
                return ['border-radius' => '8px'];
            case 'pill':
                return ['border-radius' => '64px'];
            default:
                return ['border-radius' => '4px'];
        }
    }


    public function getFullWidthStyles()
    {

        return isset($this->data['props']['fullWidth']) ? ['display' => 'block'] : ['display' => 'inline-block'];
    }

    public function getSize($size)
    {

        switch ($size) {
            case 'x-small':
                return ['padding' => '4px 8px'];
            case 'small':
                return ['padding' => '8px 12px'];
            case 'medium':
            default:
                return ['padding' => '12px 20px'];
            case 'large':
                return ['padding' => '16px 32px'];
        }
    }

    public function getWrapperStyles()
    {
        $result = [
            'fontWeight' => 'bold'
        ];
        if (isset($this->data['style']['fontWeight'])) {
            $result['fontWeight'] = $this->data['style']['fontWeight'];
        }
        foreach ($this->wrapperStyles as $style) {
            if (isset($this->data['style'][$style])) {
                $result[$style] = $this->data['style'][$style];
            }
        }


        return $result;
    }


    public function getButtonStyles()
    {

        $result = array_filter($this->data['style'], function ($key) {
            return !in_array($key, $this->wrapperStyles);
        }, ARRAY_FILTER_USE_KEY);

        if (isset($this->data['props']['buttonBackgroundColor'])) {
            $result['backgroundColor'] = $this->data['props']['buttonBackgroundColor'];
        } else {
            $result['backgroundColor'] = '#999999';
        }

        if (isset($this->data['props']['buttonTextColor'])) {
            $result['color'] = $this->data['props']['buttonTextColor'];
        } else {
            $result['color'] = '#ffffff'; // default color
        }

        if (isset($this->data['props']['buttonStyle'])) {
            $result = array_merge($result, $this->getButtonShapeStyle($this->data['props']['buttonStyle']));
        } else {
            $result = array_merge($result, ['border-radius' => '4px']);
        }

        if (isset($this->data['props']['size'])) {
            $result = array_merge($result, $this->getSize($this->data['props']['size']));
        } else {
            $result = array_merge($result, ['padding' => '12px 20px']);
        }



        $result = array_merge($result, $this->getFullWidthStyles());

        return  $result;
    }
}
