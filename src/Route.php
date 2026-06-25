<?php
namespace MVC;

// Représentation d'une route : /path => Controller@method
class Route {

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];
    private $protected = false;

    public function __construct($path, $callable){
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    // Marque la route comme protégée (authentification requise)
    public function auth(){
        $this->protected = true;
        return $this;
    }

    public function isProtected(){
        return $this->protected;
    }

    // Teste si l'URL correspond à cette route (gère les paramètres comme :id)
    public function match($url){
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    // Exécute le contrôleur/méthode associés à cette route
    public function call() {
        $rep = explode("@", $this->callable);
        $controller = "MVC\\Controllers\\".$rep[0];
        $controller = new $controller();

        return call_user_func_array([$controller, $rep[1]], $this->matches);
    }

}
