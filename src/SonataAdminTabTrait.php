<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Needed to make it easier to check content in tabs in HTML,
 * designed in the "SonataAdminBundle" style.
 * Must be used in tests inherited from "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertEquals
 */
trait SonataAdminTabTrait
{
    /**
     * Checks that a tab with the given name exists in the container.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer the root node must be a container,
     *                               containing container with labels
     *                               tabs (ul-list) and container with panels
     *                               tabs
     */
    protected function assertTabExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertTabLabelExists($tabLabel, $tabsContainer);
        $this->assertTabPaneExists($tabLabel, $tabsContainer);
    }

    /**
     * Checks that the tab with the given name does not exist in the container.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabNotExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertTabLabelNotExists($tabLabel, $tabsContainer);
    }

    /**
     * Checks that a tab shortcut with the given name exists in the container.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabLabelExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$this->getTabLabelXPath($tabLabel)}"),
            sprintf('Вкладка с заголовком "%s" не найдена', $tabLabel)
        );
    }

    /**
     * Checks that a tab shortcut with the given name does not exist in the container.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabLabelNotExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertCount(
            0,
            $tabsContainer->filterXPath("//{$this->getTabLabelXPath($tabLabel)}"),
            sprintf('Вкладка с заголовком "%s" найдена', $tabLabel)
        );
    }

    /**
     * Checks that the tab bar with the given shortcut content exists
     * in a container.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabPaneExists(string $tabLabel, Crawler $tabsContainer)
    {
        $tabPaneXPath = $this->getTabPaneXPathByTabLabel($tabLabel, $tabsContainer);

        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$tabPaneXPath}"),
            sprintf('Панель для вкладки с заголовком "%s" не найдена', $tabLabel)
        );
    }

    /**
     * Returns the XPath path to the container with tab labels.
     *
     * @return string
     */
    private function tabLabelsContainerXPath(): string
    {
        return "ul[contains(@class, 'nav-tabs')]";
    }

    /**
     * Returns the XPath path to the tab shortcut for the given content.
     *
     * @param string $tabLabel
     *
     * @return string
     */
    private function getTabLabelXPath(string $tabLabel): string
    {
        return "{$this->tabLabelsContainerXPath()}/li//a[normalize-space()='$tabLabel']";
    }

    /**
     * Returns the XPath path to the tab bar with the given identifier.
     *
     * @param string $tabPaneId identifier without leading "#"
     *
     * @return string
     */
    private function getTabPaneXPath(string $tabPaneId): string
    {
        return "{$this->tabLabelsContainerXPath()}/following-sibling::div"
            ."/descendant-or-self::div[contains(@class, 'tab-pane') and @id='$tabPaneId']";
    }

    /**
     * Returns the XPath path to the tab bar based on the contents of the shortcut.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     *
     * @return string
     */
    private function getTabPaneXPathByTabLabel(
        string $tabLabel,
        Crawler $tabsContainer
    ): string {
        $tabLabelHref = $tabsContainer
            ->filterXPath("//{$this->getTabLabelXPath($tabLabel)}")
            ->attr('href');

        $tabPaneId = ltrim($tabLabelHref, '#');

        return $this->getTabPaneXPath($tabPaneId);
    }
}
