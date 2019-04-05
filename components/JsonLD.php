<?php
namespace diazoxide\blog\components;
use Yii;
use yii\base\Component;
use yii\web\View;

use yii\base\BaseObject;
use yii\helpers\Html;
use yii\helpers\Url;
use ML\JsonLD\JsonLD;
/**
 * Helper class for registering structured data markup in JSON-LD format
 *
 * @author Alexander Stepanov <student_vmk@mail.ru>
 */
class JsonLDHelper extends Component
{
    /**
     * Adds BreadcrumbList schema.org markup based on the application view `breadcrumbs` parameter
     */
    public static function addBreadcrumbList()
    {
        $view = Yii::$app->getView();
        $breadcrumbList = [];
        if (isset($view->params['breadcrumbs'])) {
            $position = 1;
            foreach ($view->params['breadcrumbs'] as $breadcrumb) {
                if (is_array($breadcrumb)) {
                    $breadcrumbList[] = (object)[
                        "@type" => "http://schema.org/ListItem",
                        "http://schema.org/position" => $position,
                        "http://schema.org/item" => (object)[
                            "@id" => Url::to($breadcrumb['url'], true),
                            "http://schema.org/name" => $breadcrumb['label'],
                        ]
                    ];
                } else {
                    // Is it ok to omit URL here or not? Google is not clear on that:
                    // http://stackoverflow.com/questions/33688608/how-to-markup-the-last-non-linking-item-in-breadcrumbs-list-using-json-ld
                    $breadcrumbList[] = (object)[
                        "@type" => "http://schema.org/ListItem",
                        "http://schema.org/position" => $position,
                        "http://schema.org/item" => (object)[
                            "http://schema.org/name" => $breadcrumb,
                        ]
                    ];
                }
                $position++;
            }
        }
        $doc = (object)[
            "@type" => "http://schema.org/BreadcrumbList",
            "http://schema.org/itemListElement" => $breadcrumbList
        ];
        JsonLDHelper::add($doc);
    }
    /**
     * Compacts JSON-LD document, encodes and adds to the application view `jsonld` parameter,
     * so it can later be registered using JsonLDHelper::registerScripts().
     * @param array|object $doc The JSON-LD document
     * @param array|null|object|string $context optional context. If not specified, schema.org vocabulary will be used.
     */
    public static function add($doc, $context = null)
    {
        if (is_null($context)) {
            // Using a simple context from the following comment would end up replacing `@type` keyword with `type` alias,
            // which is not recognized by Google's SDTT. So using a workaround instead
            // http://stackoverflow.com/questions/35879351/google-structured-data-testing-tool-fails-to-validate-type-as-an-alias-of-type
            //$context = (object)["@context" => "http://schema.org"];
            $context = (object)[
                "@context" => (object)["@vocab" => "http://schema.org/"]
            ];
        }
        $compacted = JsonLD::compact((object)$doc, $context);
        // We need to register it with "application/ld+json" script type, which is not possible with registerJs(),
        // so passing to layout where it can be registered via JsonLDHelper::registerScripts() using Html::script
        $view = Yii::$app->getView();
        $view->params['jsonld'][] = json_encode($compacted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    /**
     * Registers JSON-LD scripts stored in the application view `jsonld` parameter.
     * This should be invoked in the <head> section of your layout.
     */
    public static function registerScripts()
    {
        $view = Yii::$app->getView();
        if (isset($view->params['jsonld'])) {
            foreach ($view->params['jsonld'] as $jsonld) {
                echo Html::script($jsonld, ['type' => 'application/ld+json']);
            }
        }
    }
}