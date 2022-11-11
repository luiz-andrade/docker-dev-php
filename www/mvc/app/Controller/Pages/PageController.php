<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class PageController{

    /**
     * Metodo responsavel por renderizar o topo da pagina
     * @return string
     */
    public static function getHeader()
    {
        return View::render('pages/header');
    }
    /**
     * Metodo responsavel por renderizar o footer da pagina
     * @return string
     */
    public static function getFooter()
    {
        return View::render('pages/footer');
    }
    /**
     * Metodo responsavel por retornar o conteudo(view) da page padrao
     * @return string
     */
    public static function getPage($title, $content){
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
}