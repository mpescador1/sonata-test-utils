<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * It is needed to simplify the check for the presence of items in the menu for bulk actions
 * with lines on the list page, styled in the "SonataAdminBundle" style.
 * Must be used in tests inherited from "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminListBatchActionsTrait
{
    /**
     * Checks that the list page contains a button corresponding to
     * transferred to the group action.
     *
     * @param string       $actionTitle
     * @param Crawler|null $crawler
     */
    protected function assertListBatchActionButtonNotExists(
        string $actionTitle,
        ?Crawler $crawler
    ) {
        $deleteBatchActionXPath = $this->getListBatchActionButtonXPath(
            $actionTitle
        );

        $this->assertCount(
            0,
            $crawler->filterXPath($deleteBatchActionXPath),
            sprintf('There is an action on the page "%s"', $actionTitle)
        );
    }

    /**
     * Returns an XPath corresponding to a group action on a standard
     * Sonata Admin list page.
     *
     * @param string $actionTitle
     *
     * @return string
     */
    private function getListBatchActionButtonXPath(
        string $actionTitle
    ): string {
        return "//select[@name='action']/option[.='$actionTitle']";
    }
}
