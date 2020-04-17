<?php
namespace SrplxBoiler\Common\Component;

use Yii;
use yii\i18n\MessageSource;

/**
 * JsonMessageSource
 *
 * Translations for json-Files
 *
 * TODO Funktioniert derzeit nur für eine Verschachtelungstiefe
 *
 * @author  Markus Fröhling <markus.froehling@surplex.com>
 * @package Unity\Common\Component
 */
class JsonMessageSource extends MessageSource
{
    /**
     * @var string the base path for all translated messages. Defaults to '@app/messages'.
     */
    public $basePath = '@app/messages';

    /**
     * Loads the message translation for the specified $language.
     *
     * @param string $category the message category
     * @param string $language the target language
     * @return array the loaded messages. The keys are original messages, and the values are the translated messages.
     * @see loadFallbackMessages
     * @see sourceLanguage
     */
    protected function loadMessages($category, $language)
    {
        $language = 'en';

        $messageFile = $this->getMessageFilePath('/' . $language);
        $messages = $this->loadMessagesFromFile($messageFile);

        if ($messages === null) {
            Yii::error("The message file does not exist: $messageFile", __METHOD__);
        }

        return (array) $messages;
    }

    /**
     * Returns message file path for the specified language.
     *
     * @param string $language the target language
     * @return string path to message file
     */
    protected function getMessageFilePath($language)
    {
        return Yii::getAlias($this->basePath) . $language . '.json';
    }

    /**
     * Loads the message translation for the specified language
     * or returns null if file doesn't exist.
     *
     * @param string $messageFile path to message file
     * @return array|null array of messages or null if file not found
     */
    protected function loadMessagesFromFile($messageFile)
    {
        if (is_file($messageFile)) {
            $messages = json_decode(
                file_get_contents($messageFile),
                true
            );

            if (!is_array($messages)) {
                $jsonMessages = [];
            } else {
                $jsonMessages = $this->flattenArray($messages);
            }

            return $jsonMessages;
        } else {
            return null;
        }
    }

    /**
     * Flattens the array structure, so that we have a key for translation.
     *
     * @param array $array
     * @return array
     */
    private function flattenArray(array $array)
    {
        $return = [];
        foreach ($array as $groupName => $items) {
            foreach ($items as $key => $value) {
                if (is_array($value)) {
                    continue;
                }

                $return[$groupName . '~~' . $key] = $value;
            }
        }

        return $return;
    }
}
