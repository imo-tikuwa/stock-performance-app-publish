<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void
    {
        // プラグインのロード
        try {
            $this->addPlugin('SoftDelete');
            $this->addPlugin('Authentication');
            $this->addPlugin('Utilities');
            $this->addPlugin('CsvView');
        } catch (MissingPluginException $e) {
        }

        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            Configure::write('DebugKit', [
                'forceEnable' => true,
                'panels' => [
                    'DebugKit.Variables' => false,
                ],
            ]);
            $this->addPlugin('DebugKit', ['bootstrap' => true, 'routes' => true]);
        }
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue): \Cake\Http\MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this, '_cake_routes_'))

            // Add cakephp/authentication Middleware
            // https://github.com/cakephp/authentication
            ->add(new AuthenticationMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Add csrf middleware.
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true
            ]));

        return $middlewareQueue;
    }

    /**
     * Bootrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        $this->addPlugin('Migrations');

        // Load more plugins here
    }

    /**
     * Gets the successful authenticator instance if one was successful after calling authenticate
     *
     * @param ServerRequestInterface $request Representation of an incoming, server-side HTTP request.
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $loginUrl = Router::url([
            'prefix' => 'Admin',
            'plugin' => null,
            'controller' => 'Auth',
            'action' => ($request->getUri()->getPath() === '/admin/auth/secure-login') ? 'secureLogin' : 'login',
        ]);
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'mail',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];
        /** @var \Cake\Http\ServerRequest $request */
        $params = Router::parseRequest($request);
        if ($params['action'] === 'secureLogin') {
            $fields[GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME] = GOOGLE_AUTHENTICATOR_SECRET_INPUT_NAME;
        }

        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => $loginUrl,
            'queryParam' => 'redirect',
        ]);
        $authenticationService->loadIdentifier('SecurePassword', compact('fields'));
        $authenticationService->loadAuthenticator('Authentication.Session', [
            'sessionKey' => 'Auth.Admin'
        ]);
        $authenticationService->loadAuthenticator('Authentication.Form', compact('loginUrl', 'fields'));

        return $authenticationService;
    }
}
