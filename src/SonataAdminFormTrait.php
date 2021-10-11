<?php

namespace AveSystems\SonataTestUtils;

use DOMElement;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Needed to simplify validation of styled form fields
 * "SonataAdminBundle". Should be used in tests inherited from
 * "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 * @method void assertThat($value, Constraint $constraint, string $message = '')
 * @method void assertStringContainsString(string $string, string $haystack, string $message = '')
 * @method void assertEquals(mixed $expected, mixed $actual, string $message = '')
 * @method void assertTrue(mixed $condition, string $message = '')
 *
 * @see Assert::assertCount
 * @see Assert::assertThat
 * @see Assert::assertStringContainsString
 * @see Assert::assertEquals
 * @see Assert::assertTrue
 */
trait SonataAdminFormTrait
{
    /**
     * Checks that the content of a form input field found by title is
     * contains the passed value.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormTextFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'The value in the input field "%s" does not match what is expected',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormTextFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormTextFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Checks that the content of a numeric form input field found by
     * header contains the passed value.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormNumberFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'The value in the input field "%s" does not match what is expected',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormNumberFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormNumberFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Checks that the content of a form input field found by title is
     * contains the passed value.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormTextareaFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'The value in the input field "%s" does not match what is expected',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormTextareaFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormTextareaFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Checks that the content of an input field found by title is
     * is present on the form.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormTextFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormTextFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Checks that the content of a numeric input field found by title is
     * is present on the form.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormNumberFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
             'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormNumberFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Checks that the content of an input field found by title is
     * is present on the form.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormTextareaFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
             'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormTextareaFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Checks that the specified checkbox field exists on the form.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormCheckboxFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
             'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormCheckboxFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Checks that the given checkbox field exists on the form and
     * is on.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormCheckboxFieldExistsAndChecked(
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormCheckboxFieldXPath($label)}";

        $element = $form->filterXPath($inputXPath);

        $this->assertCount(
            1,
            $element,
            $message
        );

        $checkedMessage = sprintf(
            'Field with header "%s" is not set to enabled',
            $label
        );

        $this->assertTrue(
            (bool) $element->attr('checked'),
            $checkedMessage
        );
    }

    /**
     * Checks that the given checkbox field exists on the form and
     * is off.
     *
     * @param string  $label
     * @param Crawler $form
     */
    protected function assertFormCheckboxFieldExistsAndUnchecked(
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Title field "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFormCheckboxFieldXPath($label)}";

        $element = $form->filterXPath($inputXPath);

        $this->assertCount(
            1,
            $element,
            $message
        );

        $checkedMessage = sprintf(
            'Field with title "%s" not disabled',
            $label
        );

        $this->assertNull(
            $element->attr('checked'),
            $checkedMessage
        );
    }

    /**
     * Checks that a file field with the given header exists on the form.
     *
     * @param string  $label field name
     * @param Crawler $form  link to crawler by form
     */
    protected function assertFileFormFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'File field with title "%s" not found ',
            $label
        );

        $inputXPath = "//{$this->getFileFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Checks that the list box has the expected value.
     *
     * @param string  $expectedValue expected value
     * @param string  $label         field name
     * @param Crawler $form          link to crawler by form
     */
    protected function assertSelectFormFieldValueEquals(
        string $expectedValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'The field with title "%s" and value "%s" not found',
            $label,
            $expectedValue
        );

        $selectXPath = "//{$this->getSelectFieldXPath($label)}";
        $selectElement = $form->filterXPath($selectXPath)->first();

        if ($expectedValue === '') {
            $selectedValues = $this->getSelectedValuesFromSelect($selectElement);
            if (count($selectedValues) === 1) {
                $value = $selectedValues[0];
                $this->assertEquals($expectedValue, $value, $message);
            } else {
                $this->assertCount(0, $selectedValues);
            }
        } else {
            $value = $this->getSelectedValueFromSelect($selectElement);
            $this->assertEquals($expectedValue, $value, $message);
        }
    }

    /**
     * Checks that a list box with the given title exists on the form.
     *
     * @param string  $label field name
     * @param Crawler $form  link to crawler by form
     */
    protected function assertSelectFormFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Title field "%s" not found ',
            $label
        );

        $selectXPath = "//{$this->getSelectFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($selectXPath),
            $message
        );
    }

    /**
     * Checks that the value in the list box with the given title exists
     * in the shape of.
     *
     * @param string  $selectLabel наименование поля
     * @param string  $optionTitle заголовок значения
     * @param Crawler $form        ссылка на краулер по форме
     */
    protected function assertSelectOptionExists(
        string $selectLabel,
        string $optionTitle,
        Crawler $form
    ) {
        $message = sprintf(
            'The value "%s" in the field with title "%s" not found',
            $optionTitle,
            $selectLabel
        );

        $selectOptionXPath = $this->getSelectOptionXPath(
            $selectLabel,
            $optionTitle
        );

        $this->assertCount(
            1,
            $form->filterXPath($selectOptionXPath),
            $message
        );
    }

    /**
     * Checks that an autocomplete multiple choice field with the given
     * heading exists in the form.
     *
     * @param string  $label field name
     * @param Crawler $form  link to crawler by form
     */
    protected function assertMultipleSelectFormFieldWithAutocompleteExists(
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Title field "%s" not found ',
            $label
        );

        $selectXPath = "//{$this->getSelectFormFieldWithAutocompleteXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($selectXPath),
            $message
        );
    }

    /**
     * Checks that an autocomplete multiple choice field has
     * expected values and not superfluous.
     *
     * @param string[] $expectedValues expected values
     * @param string   $label          field name
     * @param Crawler  $form           link to crawler by form
     */
    protected function assertMultipleSelectFormFieldWithAutocompleteValueEquals(
        array $expectedValues,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'In the title box "%s" ',
            $label
        );

        $selectXPath = "//{$this->getSelectFormFieldWithAutocompleteXPath($label)}";

        $selectElement = $form->filterXPath($selectXPath)->first();
        $values = $this->getSelectedValuesFromSelect($selectElement);

        $notFound = array_diff($expectedValues, $values);
        $extraFound = array_diff($values, $expectedValues);

        $additionalMessages = [];
        if (!empty($notFound)) {
            $additionalMessages[] = sprintf(
                'no values %s found ',
                $this->formatArrayValues($notFound)
            );
        }

        if (!empty($extraFound)) {
            $additionalMessages[] = sprintf(
                'extra values %s found',
                $this->formatArrayValues($extraFound)
            );
        }

        $message .= implode(' and ', $additionalMessages);

        $this->assertTrue(
            empty($notFound) && empty($extraFound),
            $message
        );
    }

    /**
     * Checks that on the create / edit page
     * there is no button corresponding to the passed action.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertFormActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actionButtonXPath = $this->getFormActionButtonXPath($actionTitle);

        $this->assertCount(
            0,
            $crawler->filterXPath($actionButtonXPath),
            "There is a button '$actionTitle' on the form "
        );
    }

    /**
     * Checks that on the create / edit page
     * there is a button corresponding to the passed action.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertFormActionButtonExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actionButtonXPath = $this->getFormActionButtonXPath($actionTitle);

        $this->assertCount(
            1,
            $crawler->filterXPath($actionButtonXPath),
            "There is no button '$actionTitle' on the form "
        );
    }

    /**
     * Checks if there is a given error for a form element with a specific label.
     *
     * @param string  $label   лэйбл для поиска элемента формы
     * @param string  $error   ожидаемая строка ошибки
     * @param Crawler $crawler
     */
    protected function assertFormFieldContainsError(
        string $label,
        string $error,
        Crawler $crawler
    ) {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $containerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $errorListXPath = "div[contains(@class, 'sonata-ba-field-error-messages')]";

        $errorsPath = "//$labelXPath/following-sibling::$containerXPath//$errorListXPath";
        $errorsContainer = $crawler->filterXPath($errorsPath);

        $this->assertCount(
            1,
            $errorsContainer,
            'Could not uniquely find such a field with errors'
        );

        $this->assertStringContainsString(
            $error,
            $errorsContainer->text(),
            'The error is not equal to the expected'
        );
    }

    /**
     * Returns XPath path to the checkbox with the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormCheckboxFieldXPath(string $label): string
    {
        $fieldXPath = "form//div[contains(@class, 'sonata-ba-field')]";
        $labelXPath = "label/span[contains(@class, 'control-label__text') and normalize-space()='$label']";
        $checkboxXPath = "input[@type='checkbox']";

        return "$fieldXPath//$labelXPath/preceding-sibling::$checkboxXPath";
    }

    /**
     * Returns the path to get the file field by its name.
     *
     * @param string $label name (label)
     *
     * @return string
     */
    private function getFileFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $fileFieldContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $fileFieldXPath = "input[@type='file']";

        return "$labelXPath/following-sibling::$fileFieldContainerXPath//$fileFieldXPath";
    }

    /**
     * Returns the XPath path to the option in the list box with the given titles
     * fields and values.
     *
     * @param string $selectLabel
     * @param string $optionTitle
     *
     * @return string
     */
    private function getSelectOptionXPath(
        string $selectLabel,
        string $optionTitle
    ): string {
        return "//{$this->getSelectFieldXPath($selectLabel)}".
            "/option[text() = '$optionTitle']";
    }

    /**
     * Returns the XPath path to the list box of the form with the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getSelectFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "select[contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::$inputContainerXPath//$inputXPath";
    }

    /**
     * Returns the XPath path to an autocompleted multiple choice field with
     * by the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getSelectFormFieldWithAutocompleteXPath(
        string $label
    ): string {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = 'select';

        // Look for this way, because the sonata does not explicitly mark
        // select with autocomplete.
        return "$labelXPath/following-sibling::$inputContainerXPath//$inputXPath";
    }

    /**
     * Returns the XPath path to the specified title of the form field.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormFieldLabelXPath(string $label): string
    {
        $formGroupXPath = 'form//div[contains(@class, "form-group")]';
        $labelXPath = "label[contains(@class, 'control-label') and normalize-space()='$label']";

        return "$formGroupXPath/$labelXPath";
    }

    /**
     * Returns the XPath path to the form field with the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormTextFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "input[@type='text' and contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::$inputContainerXPath//$inputXPath";
    }

    /**
     * Returns the XPath path to the numeric form field with the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormNumberFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "input[@type='number' and contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::$inputContainerXPath//$inputXPath";
    }

    /**
     * Returns the XPath path to the form field with the given title.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormTextareaFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $textareaContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $textareaXPath = "textarea[contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::$textareaContainerXPath//$textareaXPath";
    }

    /**
     * Returns the XPath path to the form action button with the specified text.
     *
     * @param string $buttonText
     *
     * @return string
     */
    private function getFormActionButtonXPath(string $buttonText): string
    {
        $containerXPath = "div[contains(@class, 'sonata-ba-form-actions')]";
        $buttonXpath = "button[@type='submit' and normalize-space()='$buttonText']";
        $linkTypeButtonXpath = "a[normalize-space()='$buttonText']";

        return "(//$containerXPath/$buttonXpath | //$containerXPath/$linkTypeButtonXpath)";
    }

    /**
     * Returns the contents of a form field with a given title without leading,
     * trailing and repeating spaces.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormTextFieldValue(
        string $label,
        Crawler $form
    ): string {
        $xPathToNormalize = "//{$this->getFormTextFieldXPath($label)}/@value";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Returns the content of a numeric form field with a given title without
     * leading, trailing and repeating spaces.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormNumberFieldValue(
        string $label,
        Crawler $form
    ): string {
        $xPathToNormalize = "//{$this->getFormNumberFieldXPath($label)}/@value";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Returns the contents of a form field with a given title without leading,
     * trailing and repeating spaces.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormTextareaFieldValue(
        string $label,
        Crawler $form
    ): string {
        $xPathToNormalize = "//{$this->getFormTextareaFieldXPath($label)}";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Gets a crawler for a table by its name
     * (relevant for the edit-create page in the admin panel).
     *
     * @param Crawler $form  родительская форма
     * @param string  $title заголовок таблицы
     *
     * @return Crawler
     */
    private function getSubAdminTableByItsTitle(string $title, Crawler $form): Crawler
    {
        $labelXPath = $this->getFormFieldLabelXPath($title);
        $tableXPath = "//$labelXPath/following-sibling::div//table[contains(@class,'table')]";

        return $form->filterXPath($tableXPath);
    }

    /**
     * Gets the value of the selected item in the dropdown list.
     *
     * @param Crawler $selectElement корневым элементом должен быть select
     *
     * @return string
     */
    private function getSelectedValueFromSelect(Crawler $selectElement): string
    {
        $selectedOption = $selectElement->filter('option[selected]');

        return trim($selectedOption->attr('value'));
    }

    /**
     * Gets a list of the values of the selected items in the dropdown list.
     *
     * @param Crawler $selectElement корневым элементом должен быть select
     *
     * @return string[]
     */
    private function getSelectedValuesFromSelect(Crawler $selectElement): array
    {
        $result = [];

        /**
         * @var DOMElement
         */
        foreach ($selectElement->children('option[selected]') as $selectedOption) {
            $result[] = trim($selectedOption->getAttribute('value'));
        }

        return $result;
    }

    /**
     * Formats array values for easy display in error text.
     *
     * @param array $data
     *
     * @return string
     */
    private function formatArrayValues(array $data): string
    {
        return implode(
            ', ',
            array_map(
                function ($value) {
                    return '"'.$value.'"';
                },
                $data
            )
        );
    }
}
