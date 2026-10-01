<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;
use Illuminate\Support\Facades\Log;

class BaseBlock implements Block
{
    public array $data = [];


    public function __construct(array $data = [])
    {
        $this->data = $data;
    }
    public function parse()
    {
        return 'Implement parse method';
    }

    function parseStyles($obj)
    {
        if($obj === null) {
            return '';
        }
        $css = '';
        foreach ($obj as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_array($value)) {
                $subCss = '';
                foreach ($value as $subKey => $subValue) {
                    if ($subValue === null) {
                        continue;
                    }



                    if ($subKey === 'fontFamily') {
                        $subCss .= 'font-family:' . $this->getFontsNames($subValue) . ';';
                    } else {
                        $subCss .= $this->camelCaseToCSS($key) . '-' . $subKey . ':' . $subValue;
                        if (is_numeric($subValue)) {
                            $subCss .= 'px;';
                        } else {
                            $subCss .= ';';
                        }
                    }
                }

                $css .= $subCss;
            } else {



                if ($key === 'fontFamily') {
                    $css .= 'font-family:' . $this->getFontsNames($value) . ';';
                } else {
                    $css .= $this->camelCaseToCSS($key) . ':' . $value;
                    if (is_numeric($value)) {
                        $css .= 'px;';
                    } else {
                        $css .= ';';
                    }
                }
            }
        }

        return trim($css);
    }

    function camelCaseToCSS($input)
    {
        $words = preg_split('/(?=[A-Z])/', $input);
        $cssName = strtolower(implode('-', $words));
        return $cssName;
    }

    function getFontsNames($family)
    {
        $families = [
            'MODERN_SANS' => '"Helvetica Neue", "Arial Nova", "Nimbus Sans", Arial, sans-serif',
            'BOOK_SANS' => 'Optima, Candara, "Noto Sans", source-sans-pro, sans-serif',
            'ORGANIC_SANS' => 'Seravek, "Gill Sans Nova", Ubuntu, Calibri, "DejaVu Sans", source-sans-pro, sans-serif',
            'GEOMETRIC_SANS' => 'Avenir, "Avenir Next LT Pro", Montserrat, Corbel, "URW Gothic", source-sans-pro, sans-serif',
            'HEAVY_SANS' => 'Bahnschrift, "DIN Alternate", "Franklin Gothic Medium", "Nimbus Sans Narrow", sans-serif-condensed, sans-serif',
            'ROUNDED_SANS' => 'ui-rounded, "Hiragino Maru Gothic ProN", Quicksand, Comfortaa, Manjari, "Arial Rounded MT Bold", Calibri, source-sans-pro, sans-serif',
            'MODERN_SERIF' => 'Charter, "Bitstream Charter", "Sitka Text", Cambria, serif',
            'BOOK_SERIF' => '"Iowan Old Style", "Palatino Linotype", "URW Palladio L", P052, serif',
            'MONOSPACE' => '"Nimbus Mono PS", "Courier New", "Cutive Mono", monospace',
        ];
        if (array_key_exists($family, $families)) {
            return $families[$family];
        }
        return $families['MODERN_SANS'];
    }
}
