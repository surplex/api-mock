<?php
namespace SrplxBoiler\Common\Component;

use yii\web\Response;

/**
 * Replaces the default Negotiator for rest controller as we do not support xml
 */
class ContentNegotiator extends \yii\filters\ContentNegotiator
{

    /**
     * This is necessary as the configuration of the formats property is set by the yii\rest\Controller
     */
    public function init()
    {
        parent::init();
        $this->formats = [
            'text/html'        => Response::FORMAT_JSON,
            'application/json' => Response::FORMAT_JSON,
            'application/xml'  => Response::FORMAT_XML,
            'text/xml'         => Response::FORMAT_XML,
            'application/pdf'  => Response::FORMAT_RAW
        ];
    }
}
