<?php

namespace Melete\Database;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IlluminateDatabaseBootstrap
 *
 * @author Dan
 */
class IlluminateDatabaseBootstrap
{
    public function bootstrap($settings) {
        $connFactory = new \Illuminate\Database\Connectors\ConnectionFactory();
        $conn = $connFactory->make($settings);
        $resolver = new \Illuminate\Database\ConnectionResolver();
        $resolver->addConnection('default', $conn);
        $resolver->setDefaultConnection('default');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
    }
}

?>
