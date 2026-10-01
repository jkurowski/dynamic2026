<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\EmailTemplatesJsonParser\Blocks\BaseBlock;

class BaseBlockTest extends TestCase
{
    public function testParse()
    {
        $block = new BaseBlock();
        $decodedJson = []; 
        $result = $block->parse();
        $this->assertEquals('Implement parse method', $result);
    }

    public function testParseStyles()
    {
        $block = new BaseBlock();
        $obj = [
            'padding' => [
                'top' => 10,
                'right' => 20,
                'bottom' => 30,
                'left' => 40
            ],
            "fontSize" => 16,
            "textAlign" => null
        ]; 
        $result = $block->parseStyles($obj);
        
        $this->assertEquals('padding-top:10px;padding-right:20px;padding-bottom:30px;padding-left:40px;font-size:16px;', $result);

    }

    public function testCamelCaseToCSS()
    {
        $block = new BaseBlock();
        $input = 'fontSize'; 
        $result = $block->camelCaseToCSS($input);
        
        $this->assertEquals('font-size', $result);
    }

    public function testGetFontsNames()
    {
        $block = new BaseBlock();
        $family = 'GEOMETRIC_SANS'; 
        $result = $block->getFontsNames($family);
        $expected = 'Avenir, "Avenir Next LT Pro", Montserrat, Corbel, "URW Gothic", source-sans-pro, sans-serif';
        
        $this->assertEquals($expected, $result);
    }
}