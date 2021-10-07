<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * It is needed to simplify the check for the presence of items in the "Actions" menu,
 * designed in the "SonataAdminBundle" style.
 * Must be used in tests inherited from "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminActionsTrait
{
    /**
     * Checks that the list page contains a button corresponding to
     * passed to action.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertActionButtonExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $addActionXPath = $this->getActionButtonXPath($actionTitle);

        $this->assertCount(
            1,
            $crawler->filterXPath($addActionXPath),
            sprintf('На странице нет действия "%s"', $actionTitle)
        );
    }

    /**
     * Checks that the list page does not have a button corresponding to
     * passed to action.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $addActionXPath = $this->getActionButtonXPath($actionTitle);

        $this->assertCount(
            0,
            $crawler->filterXPath($addActionXPath),
            sprintf('На странице есть действие "%s"', $actionTitle)
        );
    }

    /**
     * Returns the XPath corresponding to the action on the standard list page
     * Sonata Admin.
     *
     * @param string $actionTitle
     *
     * @return string
     */
    private function getActionButtonXPath(string $actionTitle): string
    {
        return "//a[contains(@class, 'sonata-action-element') and normalize-space()='$actionTitle']";
    }
}
