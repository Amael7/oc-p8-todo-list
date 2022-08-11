<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $trimmedPathinfo = rtrim($pathinfo, '/');
        $context = $this->context;
        $request = $this->request;
        $requestMethod = $canonicalMethod = $context->getMethod();
        $scheme = $context->getScheme();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }


        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_wdt')), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if ('/_profiler' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                    if (substr($pathinfo, -1) !== '/') {
                        return array_replace($ret, $this->redirect($pathinfo.'/', '_profiler_home'));
                    }

                    return $ret;
                }

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ('/_profiler/search' === $pathinfo) {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ('/_profiler/search_bar' === $pathinfo) {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_phpinfo
                if ('/_profiler/phpinfo' === $pathinfo) {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_search_results')), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler_open_file
                if ('/_profiler/open' === $pathinfo) {
                    return array (  '_controller' => 'web_profiler.controller.profiler:openAction',  '_route' => '_profiler_open_file',);
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler')), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_router')), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception')), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => '_profiler_exception_css')), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => '_twig_error_test')), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        // homepage
        if ('' === $trimmedPathinfo) {
            $ret = array (  '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  '_route' => 'homepage',);
            if (substr($pathinfo, -1) !== '/') {
                return array_replace($ret, $this->redirect($pathinfo.'/', 'homepage'));
            }

            return $ret;
        }

        if (0 === strpos($pathinfo, '/login')) {
            // login
            if ('/login' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\SecurityController::loginAction',  '_route' => 'login',);
            }

            // login_check
            if ('/login_check' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\SecurityController::loginCheck',  '_route' => 'login_check',);
            }

        }

        // logout
        if ('/logout' === $pathinfo) {
            return array (  '_controller' => 'AppBundle\\Controller\\SecurityController::logoutCheck',  '_route' => 'logout',);
        }

        if (0 === strpos($pathinfo, '/tasks')) {
            // task_list
            if ('/tasks' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\TaskController::listAction',  '_route' => 'task_list',);
            }

            // task_create
            if ('/tasks/create' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\TaskController::createAction',  '_route' => 'task_create',);
            }

            // task_edit
            if (preg_match('#^/tasks/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'task_edit')), array (  '_controller' => 'AppBundle\\Controller\\TaskController::editAction',));
            }

            // task_toggle
            if (preg_match('#^/tasks/(?P<id>[^/]++)/toggle$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'task_toggle')), array (  '_controller' => 'AppBundle\\Controller\\TaskController::toggleTaskAction',));
            }

            // task_delete
            if (preg_match('#^/tasks/(?P<id>[^/]++)/delete$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'task_delete')), array (  '_controller' => 'AppBundle\\Controller\\TaskController::deleteTaskAction',));
            }

        }

        elseif (0 === strpos($pathinfo, '/users')) {
            // user_list
            if ('/users' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\UserController::listAction',  '_route' => 'user_list',);
            }

            // user_create
            if ('/users/create' === $pathinfo) {
                return array (  '_controller' => 'AppBundle\\Controller\\UserController::createAction',  '_route' => 'user_create',);
            }

            // user_edit
            if (preg_match('#^/users/(?P<id>[^/]++)/edit$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'user_edit')), array (  '_controller' => 'AppBundle\\Controller\\UserController::editAction',));
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
