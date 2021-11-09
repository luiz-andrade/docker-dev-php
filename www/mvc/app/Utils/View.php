<?php

namespace App\Utils;

class View{

    /**
     * metodo responsavel por retornar o conteudo de uma view
     *
     * @param string $view Description
     * @return type
     * @throws conditon
     **/
    private static function getContentView($view)
    {
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }
    /**
     * Metodo responsavel por retornar o conteudo renderizado de uma view
     * @param string $view
     * @param array $vars(string/numeric)
     * @return string
     * 
     */
    public static function render($view, $vars = [])
    {
        //conteudo da view
        $contentView = self::getContentView($view);

        // chaves dos arrays
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);
        
        //retorna o conteudo renderizado
        return str_replace($keys, array_values($vars), $contentView);

    }


}