<?php

class SortableMenuTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function setUp()
    {
        Config::inst()->update('SortableMenu', 'menus', array(
            'ShowInFooter' => array(
                'Title' => 'Footer',
            ),
            'ShowInSidebar' => array(
                'Title' => 'Sidebar',
            ),
        ));
        parent::setUp();
    }

    public function testDBFieldsApplyToDataObject()
    {
        $record = new SortablePage();
        $record->Title = 'Footer Menu Item #1';
        $record->ShowInFooter = true;
        $record->write();

        // NOTE(Jake): 2018-08-09
        //
        // We re-retrieve the record. If it saved correctly
        // into the database, ShowInFooter will be `true`
        //
        $record = SortablePage::get()->byID($record->ID);
        $this->assertEquals(
            true,
            $record->ShowInFooter,
            'ShowInFooter is not "true". This probably means that the \'menus\' Config/YML in the setUp() method isn\'t working as expected.'
        );

        $record = new SortablePage();
        $record->Title = 'Sidebar Menu Item #1';
        $record->ShowInSidebar = true;
        $record->write();

        // NOTE(Jake): 2018-08-09
        //
        // We re-retrieve the record. If it saved correctly
        // into the database, ShowInSidebar will be `true`
        //
        $record = SortablePage::get()->byID($record->ID);
        $this->assertEquals(
            true,
            $record->ShowInSidebar,
            'ShowInSidebar is not "true". This probably means that the \'menus\' Config/YML in the setUp() method isn\'t working as expected.'
        );
    }

    public function testRetrievingPagesByMenu()
    {
        // Check we have no items in the menu
        $count = SortablePage::get()->filter(array('ShowInFooter' => false))->count();
        $this->assertEquals(0, $count);
        $count = SortablePage::get()->filter(array('ShowInSidebar' => false))->count();
        $this->assertEquals(0, $count);

        // Check we have 1 footer menu item
        $record = new SortablePage();
        $record->Title = 'Footer Menu Item #1';
        $record->ShowInFooter = true;
        $record->write();
        $count = SortablePage::get()->filter(array('ShowInFooter' => true))->count();
        $this->assertEquals(1, $count);

        // Check we have 1 sidemenu menu item
        $record = new SortablePage();
        $record->Title = 'Footer Menu Item #1';
        $record->ShowInSidebar = true;
        $record->write();
        $count = SortablePage::get()->filter(array('ShowInSidebar' => true))->count();
        $this->assertEquals(1, $count);
    }
}