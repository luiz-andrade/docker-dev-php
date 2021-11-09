<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class HomeController extends PageController
{
    /**
     * Metodo responsavel por retornar o conteudo da home
     * @return string 
     */
    public static function getHome(){
        //organizacao
        $obOrganization = new Organization;

        //view da home
        $content = View::render('pages/home',[
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);
    
        return parent::getPage('PHP-MVC - Home', $content);
    }
    
}
