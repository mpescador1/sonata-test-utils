<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Trait to make it easier to check Sonata flash messages on a page.
 *
 * @method void assertGreaterThan(int $expected, $actual, string $message = '')
 * @method void assertTrue($condition, string $message = '')
 * @method void assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 *
 * @see Assert::assertGreaterThan
 * @see Assert::assertTrue
 * @see Assert::assertEquals
 */
trait SonataAdminFlashMessagesTrait
{
    /**
     * Allows you to check the text of a "successful" flash message in responsive.
     *
     * @param string  $message
     * @param Crawler $crawler
     */
    protected function assertFlashSuccessMessageExists(
        string $message,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashSuccessMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'Успешные сообщения отсутствуют на странице!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$message}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Успешные флэш-сообщения не содержат текст "'.$message.'".'
        );
    }

    /**
     * Allows you to check the text of the "unsuccessful" flash message in the responsive.
     *
     * @param string  $error
     * @param Crawler $crawler
     */
    protected function assertFlashErrorMessageExists(
        string $error,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashErrorMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'Сообщения c ошибками отсутствуют на странице!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$error}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Неуспешные флэш-сообщения не содержат текст "'.$error.'".'
        );
    }

    /**
     * Checks the number of unsuccessful flash messages in the responsive.
     *
     * @param int     $count
     * @param Crawler $crawler
     */
    protected function assertFlashErrorMessagesCount(int $count, Crawler $crawler)
    {
        $this->assertEquals(
            $count,
            $this->findFlashErrorMessages($crawler)->count()
        );
    }

    /**
     * Allows you to check the text of the "warning" flash message in the responsive.
     *
     * @param string  $expectedMessage
     * @param Crawler $crawler
     */
    protected function assertFlashWarningMessageExists(
        string $expectedMessage,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashWarningMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'No warning messages on page!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$expectedMessage}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Flash alert messages do not contain text "'
            .$expectedMessage.'".'
        );
    }

    /**
     * Search for flash messages with errors.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashErrorMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-error fade in"]');
    }

    /**
     * Search for successful flash messages.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashSuccessMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-success fade in"]');
    }

    /**
     * Search flash messages with warnings.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashWarningMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-warning fade in"]');
    }
}
