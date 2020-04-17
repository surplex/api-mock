<?php
namespace SrplxBoiler\Common\Component\Parser;

use DOMDocument;
use DOMNode;
use InvalidArgumentException;
use SrplxBoiler\Common\Component\ContentNegotiator;
use yii\web\BadRequestHttpException;
use yii\web\RequestParserInterface;
use yii\web\Response;

class XmlParser implements RequestParserInterface
{
    /** @var bool $asArray */
    public $asArray = true;

    /** @var bool $throwException */
    public $throwException = true;

    /** @var array */
    const ALLOWED_ROUTES = [
        'accounting/project/opportunity',
    ];

    /**
     * Parses a HTTP request body.
     *
     * @param string $rawBody the raw HTTP request body.
     * @param string $contentType the content type specified for the request body.
     *
     * @return array parameters parsed from the request body
     * @throws BadRequestHttpException if the body contains invalid xml and [[throwException]] is `true`.
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */

    public function parse($rawBody, $contentType)
    {
        /** @var ContentNegotiator $contentNegotiator */
        $contentNegotiator = \Yii::$container->get(ContentNegotiator::class);
        if ((isset($contentNegotiator->formats[$contentType]))
            && ($contentNegotiator->formats[$contentType] !== Response::FORMAT_XML)
        ) {
            throw new BadRequestHttpException('Invalid content type in request header: ' . $contentType);
        }

        if (!in_array(\Yii::$app->requestedRoute, self::ALLOWED_ROUTES)) {
            throw new BadRequestHttpException('Invalid content type for this route');
        }

        try {
            $xml = new DOMDocument('1.0', 'utf-8');
            $xml->loadXML($rawBody);

            return $this->xmlToArray($xml);

        } catch (InvalidArgumentException $e) {
            if ($this->throwException) {
                throw new BadRequestHttpException('Invalid XML data in request body: ' . $e->getMessage());
            }
            return [];
        }
    }

    /**
     * @param DOMNode $root
     * @return array|string
     */
    private function xmlToArray(DOMNode $root)
    {
        $result = array();
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }
        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1 ? $result['_value'] : $result;
                }
            }
            $groups = array();
            foreach ($children as $child) {
                if ($child->nodeType !== XML_TEXT_NODE) {
                    if (!isset($result[$child->nodeName])) {
                        $result[$child->nodeName] = $this->xmlToArray($child);
                    } else {
                        if (!isset($groups[$child->nodeName])) {
                            $result[$child->nodeName] = array($result[$child->nodeName]);
                            $groups[$child->nodeName] = 1;
                        }
                        $result[$child->nodeName][] = $this->xmlToArray($child);
                    }
                }
            }
        }

        if (empty($result)) {
            $result = null;
        }

        return $result;
    }
}
