<?php

class SortableMenuSiteConfigTest extends FunctionalTest
{
    protected static $use_draft_site = true;

    protected $usesDatabase = true;

    public function setUp()
    {
        // NOTE(Jake): 2018-08-10
        //
        // If we can't find "SiteConfig" class, skip all tests.
        //
        if (!class_exists('SiteConfig')) {
            $this->skipTest = true;
        }

        Config::inst()->update('SortableMenu', 'menus', array(
            'ShowInFooter' => array(
                'Title' => 'Footer',
            ),
            'ShowInSidebar' => array(
                'Title' => 'Sidebar',
            ),
        ));
        // NOTE(Jake): 2018-08-09
        //
        // The core `$requiredExtensions` functionality isn't working here in SS 3.X.
        // I suspect its not flushing the YML or something?
        //
        Page::add_extension('SortableMenu');
        parent::setUp();
    }

    public function testLoadEditingSiteConfigWithNoData()
    {
        $this->logInWithPermission('ADMIN');

        // Taken from SiteConfig::requireDefaultRecords()
        $config = DataObject::get_one('SiteConfig');
        if (!$config) {
            $config = SiteConfig::make_site_config();
        }

        $response = $this->get($config->CMSEditLink());

        // Check that the GridFields have been created in the CMS
        $this->assertContains('<input type="hidden" name="ShowInFooter[GridState]" ', $response->getBody());
        $this->assertContains('<input type="hidden" name="ShowInSidebar[GridState]" ', $response->getBody());
    }

    /**
     * Taken from "framework\tests\view\SSViewerTest.php"
     */
    private function assertEqualIgnoringWhitespace($a, $b, $message = '')
    {
        $this->assertEquals(preg_replace('/\s/', '', $a), preg_replace('/\s/', '', $b), $message);
    }
}