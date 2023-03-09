<?php
interface RouterInterface
{
    public function getRoutes(): RouteCollectionInterface;
}
interface RouteCollectionInterface extends Countable, Iterator
{
}

/*
 * getRoutes return is tightly defined ensure expected behaviour
 */

class AdvancedRouter implements RouterInterface
{
    public function getRoutes() : RouteCollectionInterface
    {
        $routeCollection = new RouteCollection();
        // ...
        return $routeCollection;
    }
}
