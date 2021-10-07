<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * PagesControllerTest class
 *
 * @uses \App\Controller\PagesController
 */
class PagesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * testMultipleGet method
     *
     * @return void
     */
    public function testMultipleGet()
    {
        if (Configure::read('debug')) {
            $this->get('/');
            $this->assertResponseOk();
            $this->get('/');
            $this->assertResponseOk();
        } else {
            $this->get('/');
            $this->assertResponseError();
            $this->get('/');
            $this->assertResponseError();
        }
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->get('/pages/home');
        if (Configure::read('debug')) {
            $this->assertResponseOk();
            $this->assertResponseContains('CakePHP');
            $this->assertResponseContains('<html>');
        } else {
            $this->assertResponseError();
            $this->assertResponseContains('Please replace templates/Pages/home.php with your own version or re-enable debug mode.');
            $this->assertResponseContains('<html>');
        }
    }

    /**
     * Test that missing template
     *
     * @return void
     */
    public function testMissingTemplate()
    {
        $this->get('/pages/not_existing');
        if (Configure::read('debug')) {
            $this->assertResponseContains('Missing Template');
            $this->assertResponseContains('Stacktrace');
            $this->assertResponseContains('not_existing.php');
        } else {
            $this->assertResponseError();
            $this->assertResponseContains('Error');
        }
    }

    /**
     * Test directory traversal protection
     *
     * @return void
     */
    public function testDirectoryTraversalProtection()
    {
        $this->get('/pages/../Layout/ajax');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Forbidden');
    }

    /**
     * Test that CSRF protection is applied to page rendering.
     *
     * @return void
     */
    public function testCsrfAppliedError()
    {
        $this->post('/pages/home', ['hello' => 'world']);

        $this->assertResponseCode(403);
        $this->assertResponseContains('CSRF');
    }

    /**
     * Test that CSRF protection is applied to page rendering.
     *
     * @return void
     */
    public function testCsrfAppliedOk()
    {
        $this->enableCsrfToken();
        $this->post('/pages/home', ['hello' => 'world']);

        if (Configure::read('debug')) {
            $this->assertResponseCode(200);
            $this->assertResponseContains('CakePHP');
        } else {
            $this->assertResponseCode(404);
            $this->assertResponseContains('Please replace templates/Pages/home.php with your own version or re-enable debug mode.');
        }
    }
}
