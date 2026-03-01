<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class CaseConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;
        return view('tools.case-converter', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $wordLimit = $tool->wc_tool ?? 10000;
        $validated = $request->validate([
            'string' => "required|min:1|max_words:{$wordLimit}",
            'type' => 'required',
        ]);

        $string = $request->input('string');
        $type = $request->input('type');
        $converted_text = "";

        switch ($type) {
            case ('1'):
                $converted_text  = $this->toggle_case($string);
                break;
            case ('2'):
                $converted_text  = $this->sentence_case($string);
                break;
            case ('3'):
                $converted_text  = strtolower($string);
                break;
            case ('4'):
                $converted_text  = strtoupper($string);
                break;
            case ('5'):
                $c_text = strtolower($string);
                $converted_text  = ucwords($c_text);
                break;
            case ('6'): // camelCase
                $converted_text = $this->to_camel_case($string);
                break;
            case ('7'): // snake_case
                $converted_text = $this->to_snake_case($string);
                break;
            case ('8'): // kebab-case
                $converted_text = $this->to_kebab_case($string);
                break;
            case ('9'): // PascalCase
                $converted_text = $this->to_pascal_case($string);
                break;
            case ('10'): // CONSTANT_CASE
                $converted_text = strtoupper($this->to_snake_case($string));
                break;
            case ('11'): // aLtErNaTiNg cAsE
                $converted_text = $this->to_alternating_case($string);
                break;
            default:
                $converted_text = $string;
        }

        $results = [
            'original_text' => $string,
            'converted_text' => $converted_text,
            'type' => $type
        ];

        return view('tools.case-converter', compact('results', 'tool', 'type'));
    }

    public function toggle_case($string)
    {
        $toggle_string = '';
        $length = mb_strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($string, $i, 1);
            if (ctype_upper($char)) {
                $toggle_string .= mb_strtolower($char);
            } else if (ctype_lower($char)) {
                $toggle_string .= mb_strtoupper($char);
            } else {
                $toggle_string .= $char;
            }
        }
        return $toggle_string;
    }

    public function sentence_case($string)
    {
        $string = strtolower($string);
        return preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return $matches[1] . ' ' . strtoupper($matches[2]);
        }, ucfirst($string));
    }

    protected function to_camel_case($string)
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string);
        $string = strtolower($string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return lcfirst($string);
    }

    protected function to_snake_case($string)
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        $string = strtolower($string);
        return str_replace(' ', '_', $string);
    }

    protected function to_kebab_case($string)
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        $string = strtolower($string);
        return str_replace(' ', '-', $string);
    }

    protected function to_pascal_case($string)
    {
        $string = preg_replace('/[^a-zA-Z0-9]/', ' ', $string);
        $string = strtolower($string);
        $string = ucwords($string);
        return str_replace(' ', '', $string);
    }

    protected function to_alternating_case($string)
    {
        $result = '';
        $length = mb_strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($string, $i, 1);
            $result .= ($i % 2 == 0) ? mb_strtolower($char) : mb_strtoupper($char);
        }
        return $result;
    }
}
