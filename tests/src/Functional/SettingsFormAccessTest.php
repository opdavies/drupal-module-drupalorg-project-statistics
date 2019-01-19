<?php

namespace Drupal\Tests\drupalorg_projects\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

class SettingsFormAccessTest extends BrowserTestBase {

    /**
     * {@inheritdoc}
     */
    protected static $modules = ['drupalorg_projects'];

    /**
      * Test that anonymousadmin users cannot access the settings form.
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function testAnonymousUsersCannotAccessTheSettingsForm() {
      $this->drupalGet(Url::fromRoute('drupalorg_projects.settings')->getInternalPath());
      $this->assertSession()->statusCodeEquals(403);
    }

    /**
     * Test that authenticated non-admin users cannot access the settings form.
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function testNonAdminUsersCannotAccessTheSettingsForm() {
      $this->drupalLogin($this->createUser());

      $this->drupalGet(Url::fromRoute('drupalorg_projects.settings')->getInternalPath());
      $this->assertSession()->statusCodeEquals(403);
    }

    /**
     * Test that authenticated admin users can access the settings form.
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function testAdminUsersCanAccessTheSettingsForm() {
      $this->drupalLogin($this->createUser(['administer site configuration']));

      $this->drupalGet(Url::fromRoute('drupalorg_projects.settings')->getInternalPath());
      $this->assertSession()->statusCodeEquals(200);
    }

}
