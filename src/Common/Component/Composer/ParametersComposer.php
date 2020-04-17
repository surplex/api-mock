<?php
namespace SrplxBoiler\Common\Component\Composer;

use Composer\Script\Event;

class ParametersComposer
{

    const CONFIG_PROPERTY = 'parameters';

    public static function action(Event $event)
    {
        $params = $event->getComposer()->getPackage()->getExtra();

        if (!isset($params[self::class]) || !isset($params[self::class][self::CONFIG_PROPERTY])) {
            static::outputNoConfig();
            exit;
        }

        $params = $params[self::class];

        if (isset($params[self::CONFIG_PROPERTY]) && is_array($params[self::CONFIG_PROPERTY])) {
            $baseDir = dirname($event->getComposer()->getConfig()->get('vendor-dir'));


            foreach ($params[self::CONFIG_PROPERTY] as $action) {
                if (isset($action['target-file'])
                    && isset($action['dist-file'])
                    && is_file($baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $action['target-file'])
                    && is_file($baseDir . DIRECTORY_SEPARATOR . $action['dist-path'] . DIRECTORY_SEPARATOR . $action['dist-file'])
                    && static::contentEquals(
                        $baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $action['target-file'],
                        $baseDir . DIRECTORY_SEPARATOR . $action['dist-path'] . DIRECTORY_SEPARATOR . $action['dist-file']
                    )
                ) {
                    echo 'Content equals. Nothing to do...done' . PHP_EOL;
                    continue;
                }

                //job rename
                if (isset($action['target-file'])
                    && is_file($baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $action['target-file'])
                ) {
                    $exploded = explode('.', $action['target-file']);
                    $newFile = 'cp_' . $exploded[0] . '_' . time() . '.' . $exploded[1];

                    rename(
                        $baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $action['target-file'],
                        $baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $newFile
                    );

                    echo 'Renamed existing (' . $action['target-file'] . ') into ' . $newFile . ' ...done.' . PHP_EOL;
                }

                //job copy
                if (isset($action['target-file'])
                    && isset($action['dist-file'])
                    && is_file($baseDir . DIRECTORY_SEPARATOR . $action['dist-path'] . DIRECTORY_SEPARATOR . $action['dist-file'])
                ) {
                    copy(
                        $baseDir . DIRECTORY_SEPARATOR . $action['dist-path'] . DIRECTORY_SEPARATOR . $action['dist-file'],
                        $baseDir . DIRECTORY_SEPARATOR . $action['target-path'] . DIRECTORY_SEPARATOR . $action['target-file']
                    );

                    echo 'Copy (' . $action['dist-file'] . ') to ' . $action['target-file'] . ' ...done.' . PHP_EOL;
                }
            }
        }
    }

    /**
     * Checks if content of existing equals the dist file.
     *
     * @param string $existingFilePath
     * @param string $distFilePath
     * @return bool
     */
    private static function contentEquals($existingFilePath, $distFilePath)
    {
        $existing = file_get_contents($existingFilePath);
        $dist = file_get_contents($distFilePath);

        return $existing === $dist;
    }

    /**
     * Simple echo for showing that task is aborted
     */
    private static function outputNoConfig()
    {
        echo 'No config found for parameters...aborted.' . PHP_EOL;
    }
}
