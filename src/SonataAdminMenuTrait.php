<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * It is needed to make it easier to check for the presence of menu items,
 * designed in the "SonataAdminBundle" style.
 * Must be used in tests inherited from "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminMenuTrait
{
    /**
     * Проверяет, что список меню присутствует на странице.
     *
     * @param Crawler $crawler
     */
    protected function assertMenuExists(Crawler $crawler)
    {
        $menuXPath = $this->getMenuXPath();

        $this->assertCount(
            1,
            $crawler->filterXPath('//'.$menuXPath),
            'Не найдено меню на странице'
        );
    }

    /**
     * Checks that the menu list is not present on the page.
     *
     * @param Crawler $crawler
     */
    protected function assertMenuNotExists(Crawler $crawler)
    {
        $menuXPath = $this->getMenuXPath();

        $this->assertCount(
            0,
            $crawler->filterXPath('//'.$menuXPath),
            'Меню присутствует на странице'
        );
    }

    /**
     * Checks that the item with the given name is in the menu list.
     *
     * @param Crawler $crawler
     * @param string  $menuItem название пункта меню
     */
    protected function assertMenuItemExists(Crawler $crawler, string $menuItem)
    {
        $menuXPath = $this->getMenuXPath();
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$itemXPath";

        $this->assertCount(
            1,
            $crawler->filterXPath($xpath),
            sprintf('There is no "%s" item in the menu', $menuItem)
        );
    }

    /**
     * Checks that the item with the given name is in the menu list.
     *
     * @param Crawler $crawler
     * @param string  $menuItem название пункта меню
     */
    protected function assertMenuItemNotExists(
        Crawler $crawler,
        string $menuItem
    ) {
        $menuXPath = $this->getMenuXPath();
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$itemXPath";

        $this->assertCount(
            0,
            $crawler->filterXPath($xpath),
            sprintf('В меню есть пункт "%s"', $menuItem)
        );
    }

    /**
     * Checks that the item with the given name is in the specified group
     * menu.
     *
     * @param Crawler $crawler
     * @param string  $menuItem  название пункта меню
     * @param string  $menuGroup название группы меню
     */
    protected function assertMenuItemInGroupExists(
        Crawler $crawler,
        string $menuItem,
        string $menuGroup
    ) {
        $xpath = $this->getMenuItemInGroupXPath($menuGroup, $menuItem);

        $this->assertCount(
            1,
            $crawler->filterXPath($xpath),
            sprintf('В группе меню "%s" нет пункта "%s"', $menuGroup, $menuItem)
        );
    }

    /**
     * Checks that the item with the given name is not in the specified group
     * menu.
     *
     * @param Crawler $crawler
     * @param string  $menuItem  название пункта меню
     * @param string  $menuGroup название группы меню
     */
    protected function assertMenuItemInGroupNotExists(
        Crawler $crawler,
        string $menuItem,
        string $menuGroup
    ) {
        $xpath = $this->getMenuItemInGroupXPath($menuGroup, $menuItem);

        $this->assertCount(
            0,
            $crawler->filterXPath($xpath),
            sprintf('В группе меню "%s" есть пункт "%s"', $menuGroup, $menuItem)
        );
    }

    /**
     * Checks that menu item names and hierarchy match
     * passed value.
     *
     * @param Crawler $crawler
     * @param array   $expectedMenuHierarchyLabels
     *
     * @example [
     *   'Management Board' => [
     *     'Meeting information',
     *     'Founder's representatives',
     *   ],
     *   'Director certificates',
     * ]
     */
    protected function assertMenuItemsEqual(
        Crawler $crawler,
        array $expectedMenuHierarchyLabels
    ) {
        $menuXPath = "//{$this->getMenuXPath()}";
        $menu = $crawler->filterXPath($menuXPath);

        $actualMenuHierarchyLabels = $this->retrieveMenuLabels($menu);

        $this->assertEquals(
            $expectedMenuHierarchyLabels,
            $actualMenuHierarchyLabels
        );

        // При сравнении ассоциативных массивов, не учитывается порядок ключей,
        // поэтому нужна дополнительная проверка
        $expectedOrderedKeys = [];
        $actualOrderedKeys = [];

        array_walk_recursive(
            $expectedMenuHierarchyLabels,
            function ($value, $key) use (&$expectedOrderedKeys) {
                $expectedOrderedKeys[] = $key;
            }
        );

        array_walk_recursive(
            $actualMenuHierarchyLabels,
            function ($value, $key) use (&$actualOrderedKeys) {
                $actualOrderedKeys[] = $key;
            }
        );

        $this->assertEquals(
            $expectedOrderedKeys,
            $actualOrderedKeys,
            'Menu item order does not match'
        );
    }

    /**
     * @param string $menuGroup
     * @param string $menuItem
     *
     * @return string
     */
    private function getMenuItemInGroupXPath(
        string $menuGroup,
        string $menuItem
    ): string {
        $menuXPath = $this->getMenuXPath();
        $groupXMenuPath = $this->getMenuGroupMenuXPath($menuGroup);
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$groupXMenuPath//$itemXPath";

        return $xpath;
    }

    /**
     * Returns the XPath path to the SonataAdminBundle menu.
     *
     * @return string
     */
    private function getMenuXPath()
    {
        return 'ul[@class="sidebar-menu"]';
    }

    /**
     * Returns the XPath path to the SonataAdminBundle menu group by name.

     *
     * @param string $menuGroup
     *
     * @return string
     */
    private function getMenuGroupXPath(string $menuGroup)
    {
        return "li[contains(@class, 'treeview')]//a[normalize-space()='{$menuGroup}']";
    }

    /**
     * Returns the XPath path to the menu of the group by name in the menu
     * SonataAdminBundle.
     *
     * @param string $menuGroup
     *
     * @return string
     */
    private function getMenuGroupMenuXPath(string $menuGroup)
    {
        $menuGroupXPath = $this->getMenuGroupXPath($menuGroup);

        return "$menuGroupXPath/following-sibling::ul[contains(@class, 'treeview-menu')]";
    }

    /**
     * Returns the XPath path to the SonataAdminBundle menu item by name.
     *
     * @param string $menuItem название пункта меню
     *
     * @return string
     */
    private function getMenuItemXPath(string $menuItem)
    {
        return "li//a[normalize-space()='{$menuItem}']";
    }

    /**
     * Traverses the menu and returns the item names according to the hierarchy.
     *
     * @param Crawler $menu
     *
     * @return string[]
     *
     * @example [
     *   'Management Board' => [
     *     'Meeting information',
     *     'Founder's representatives',
     *   ],
     *   'Director certificates',
     * ]
     */
    private function retrieveMenuLabels(Crawler $menu): array
    {
        $menuLabels = [];

        $menu->filterXPath('ul/li')->each(
            function (Crawler $item) use (&$menuLabels) {
                $itemLabel = trim($item->filterXPath('li/a')->text());
                $subMenu = $item->filterXPath('li/ul');

                if ($subMenu->count() === 0) {
                    $menuLabels[] = $itemLabel;
                } else {
                    $menuLabels[$itemLabel] = $this->retrieveMenuLabels(
                        $subMenu
                    );
                }
            }
        );

        return $menuLabels;
    }
}
