<?php
/**
 * Created by PhpStorm.
 * User: Skoroid
 * Date: 20.11.2018
 * Time: 16:08
 */

namespace Twig;


use Entity\TranslatableListInterface;
use Exception;
use MvcSkeleton\ServiceLocator;
use Service\DateTime;
use Tracy\Debugger;
use Yii\I18n\MessageFormatter;

class Extension extends \Twig_Extension {

    /**
     * @var array
     */
    private $_usedUrls = [];
    private $_usedData = [];

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'TwigExtension';
    }

    public function getFunctions() {
        return [
            't'                => new \Twig_Function_Method($this, 't', ['is_safe' => ['html']]),
            'translateField'   => new \Twig_Function_Method($this, 'translateField', ['is_safe' => ['html']]),
            'nt'               => new \Twig_Function_Method($this, 'nt', ['is_safe' => ['html']]),
            'widget'           => new \Twig_Function_Method($this, 'widget', ['is_safe' => ['html']]),
            'unserialize'      => new \Twig_Function_Method($this, 'unserialize'),
            'secondsBetween'   => new \Twig_Function_Method($this, 'secondsBetween'),
            'timePassed'       => new \Twig_Function_Method($this, 'timePassed', ['is_safe' => ['html']]),
            'timePassedRus'    => new \Twig_Function_Method($this, 'timePassedRus', ['is_safe' => ['html']]),
            'calendarDaysDiff' => new \Twig_SimpleFunction('calendarDaysDiff', [$this, 'calendarDaysDiff']),
            'formatVisits'     => new \Twig_Function_Method($this, 'formatVisits'),
            'formatMoney'      => new \Twig_Function_Method($this, 'formatMoney'),
            'formatDate'       => new \Twig_Function_Method($this, 'formatDate'),
            'favicon'          => new \Twig_Function_Method($this, 'favicon'),
            'image'            => new \Twig_Function_Method($this, 'image', ['is_safe' => ['html']]),
            'tooltip'          => new \Twig_Function_Method($this, 'tooltip', ['pre_escape' => 'html', 'is_safe' => ['html']]),
            'dateRange'        => new \Twig_Function_Method($this, 'dateRange', ['pre_escape' => 'html', 'is_safe' => ['html']]),
            'buildPageVars'    => new \Twig_Function_Method($this, 'buildPageVars', ['is_safe' => ['html']]),
            'setUrlParam'      => new \Twig_SimpleFunction('setUrlParam', [$this, 'setUrlParam'], ['is_safe' => ['html_attr']]),
            'useUrl'           => new \Twig_Function_Method($this, 'useUrl'),
            'useData'          => new \Twig_Function_Method($this, 'useData'),
            'js'               => new \Twig_SimpleFunction('js', [$this, 'js'], ['is_safe' => ['html']]),
            'css'              => new \Twig_SimpleFunction('css', [$this, 'css'], ['is_safe' => ['html']]),
            'debug'            => new \Twig_SimpleFunction('debug', [$this, 'debug'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters() {
        return [
            'strToDate'                  => new \Twig_Filter_Method($this, 'strToDate'),
            'cut'                        => new \Twig_Filter_Method($this, 'cut'),
            'formatMoneyNumber'          => new \Twig_Filter_Method($this, 'formatMoneyNumber'),
            'formatMoneyWithSign'        => new \Twig_Filter_Method($this, 'formatMoneyWithSign', ['is_safe' => ['html']]),
            'formatUserMoneyWithSign'    => new \Twig_Filter_Method($this, 'formatUserMoneyWithSign', ['is_safe' => ['html']]),
            'formatProjectMoneyWithSign' => new \Twig_Filter_Method($this, 'formatProjectMoneyWithSign', ['is_safe' => ['html']]),
            'formatNumeric'              => new \Twig_Filter_Method($this, 'formatNumeric'),
            'url_decode'                 => new \Twig_Filter_Method($this, 'urlDecode'),
            'prettyNumber'               => new \Twig_Filter_Method($this, 'prettyNumber', ['pre_escape' => 'html', 'is_safe' => ['html']]),
            'formatTimezone'             => new \Twig_Filter_Method($this, 'formatTimezone'),
            'inUserTimeZone'             => new \Twig_Filter_Method($this, 'formatDateToApplicationTimeZone'),
            'formatDateWithoutTime'      => new \Twig_Filter_Method($this, 'formatDateWithoutTime'),
            'formatDateShort'            => new \Twig_Filter_Method($this, 'formatDateShort'),
            'formatDateWithMonth'        => new \Twig_Filter_Method($this, 'formatDateWithMonth'),
            'json_decode'                => new \Twig_Filter_Method($this, 'jsonDecode'),
        ];
    }

    public function getTests() {
        return [
            'hasTags' => new \Twig_SimpleTest('hasTags', [$this, 'hasTagsTest'])
        ];
    }

    /**
     * @param string $name
     * @param string $url
     */
    public function useUrl($name, $url) {
        $this->_usedUrls[$name] = $url;
    }

    /**
     * @param string $name
     * @param string $data
     */
    public function useData($name, $data) {
        $this->_usedData[$name] = $data;
    }

    public function buildPageVars() {
        $urlList = 'window.urlList = ' . json_encode($this->_usedUrls) . ';';
        $dataList = 'window.pageData = ' . json_encode($this->_usedData) . ';';
        return "<script type=\"text/javascript\">\n{$urlList}\n{$dataList}</script>";
    }

    /**
     * @param string $class
     * @param array $data
     * @return string
     * @throws Exception\BadUsage
     */
    public function widget($class, array $data = []) {
        $widgetClass = "\\Widget\\$class\\Widget";
        if (!class_exists($widgetClass)) {
            throw new  Exception\BadUsage('Widget class does not exist');
        }
        if (!is_a($widgetClass, '\Widget\Basic', true)) {
            throw new Exception\BadUsage('Widget class is not a widget');
        }
        /** @var \Widget\Basic $widget */
        $widget = new $widgetClass($data);
        $widget->run();
        $result = $widget->render();
        return $result;
    }

    /**
     * @param $text
     * @param array|int $params
     * @return string
     */
    public function tOld($text, $params = []) {

        if (!is_array($params)) {
            $params = (array)$params;
        }
        if (count($params) === 0) {
            return $text;
        }
        $language = 'ru';
        if (preg_match('~{\s*[\d\w]+\s*,~u', $text)) {
            $formatter = new MessageFormatter();
            $result = $formatter->format($text, $params, $language);
            if ($result === false) {
                return $text;
            } else {
                return $result;
            }
        }

        $replaces = [];
        foreach ($params as $name => $value) {
            $replaces['{' . $name . '}'] = $value;
        }

        return strtr($text, $replaces);
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public function t($message, array $params = []) {
        $replaces = [];
        foreach ($params as $name => $value) {
            $replaces['{' . $name . '}'] = $value;
        }

        return strtr(ServiceLocator::localizationService()->translate($message), $replaces);
    }

    /**
     * @param array|object $entity
     * @param string $field
     * @return string
     * @throws Exception\BadUsage
     * @throws Exception\Core\Object\NotExists
     */
    public function translateField($entity, $field) {
        if (is_array($entity)) {
            if (!array_key_exists($entity, $field)) {
                throw new Exception\Core\Object\NotExists("Field {$field} not exists");
            }
            return $this->t($entity[$field]);
        }

        if ($entity instanceof TranslatableListInterface) {
            return ServiceLocator::localizationService()->translateListField($entity, $field);
        }

        throw new Exception\BadUsage('Invalid type');
    }

    /**
     * @param string $message
     * @param string $message2
     * @param float|array $n
     * @return string
     * @throws Exception\BadUsage
     */
    public function nt($message, $message2, $n) {
        return nt($message, $message2, $n);
    }

    public function unserialize($text) {
        return unserialize($text);
    }

    /**
     * @param \DateTime|string $dateFrom
     * @param \DateTime|string $dateTo
     * @return int
     */
    public function secondsBetween($dateFrom, $dateTo) {
        if ($dateFrom === null || (strtotime($dateFrom) === false)) {
            return null;
        }
        if (is_string($dateFrom)) {
            $dateFrom = new \DateTime($dateFrom);
        }
        if (is_string($dateTo)) {
            $dateTo = new \DateTime($dateTo);
        }
        return ServiceLocator::dateTimeService()->secondsBetween($dateFrom, $dateTo);
    }

    /**
     * @param \DateTime|string $dateBefore
     * @param \DateTime|string $dateAfter
     * @return string
     */
    public function timePassed($dateBefore, $dateAfter = null) {
        if ((is_null($dateBefore)) || (strtotime($dateBefore) === false)) {
            return '';
        }
        if (is_string($dateBefore)) {
            $dateBefore = new \DateTime($dateBefore);
        }
        if (is_string($dateAfter)) {
            $dateAfter = new \DateTime($dateAfter);
        }
        return ServiceLocator::dateTimeService()->timePassedAfterHtml($dateBefore, $dateAfter);
    }

    /**
     * @param \DateTime|string $dateBefore
     * @param \DateTime|string $dateAfter
     * @return string
     */
    public function timePassedRus($dateBefore, $dateAfter = null) {
        // todo Refactor
        if ((is_null($dateBefore)) || (strtotime($dateBefore) === false)) {
            return '';
        }
        if (is_string($dateBefore)) {
            $dateBefore = new \DateTime($dateBefore);
        }
        if (is_string($dateAfter)) {
            $dateAfter = new \DateTime($dateAfter);
        }
        $result = ServiceLocator::dateTimeService()->timePassedAfter($dateBefore, $dateAfter);
        return $result;
    }

    /**
     * @param \DateTime|string $dateFrom
     * @param \DateTime|string $dateTo
     * @return int
     */
    public function calendarDaysDiff($dateFrom, $dateTo = null) {
        if (!($dateFrom instanceof \DateTime)) {
            if (is_string($dateFrom) && strtotime($dateFrom) > 0) {
                $dateFrom = new \DateTime($dateFrom);
            } else {
                return false;
            }
        }
        if (!($dateTo instanceof \DateTime)) {
            if (is_string($dateTo) && strtotime($dateTo) > 0) {
                $dateTo = new \DateTime($dateTo);
            } else {
                $dateTo = null;
            }
        }
        return ServiceLocator::dateTimeService()->getCalendarDaysDiff($dateFrom, $dateTo);
    }

    /**
     * @param int $visits
     * @return string
     */
    public function formatVisits($visits) {
        if (is_infinite($visits)) {
            return '&infin;';
        }
        if ($visits > 1000) {
            return round($visits / 1000, 0) . 'k';
        } else {
            return $visits;
        }
    }

    /**
     * @param int $value
     * @return string
     */
    public function formatMoney($value) {
        return number_format($value / 100, 2, ',', ' ');
    }

    /**
     * @param float $value
     * @return float
     * @throws \Exception
     */
    public function formatMoneyNumber($value) {
        if ($value === null) {
            return null;
        }
        if (!is_numeric($value)) {
            ServiceLocator::loggerService()->notifyException(new \Exception("Not numeric value: " . var_export($value, true)));
        }
        if ($value > 10 || $value == 0) {
            return number_format($value, 0, '.', '&nbsp;');
        } else {
            return number_format($value, 2, '.', '&nbsp;');
        }
    }

    /**
     * @param float $value
     * @return string
     */
    public function formatMoneyWithSign($value) {
        $formattedValue = $this->prettyNumber($value);
        $currencySign = 'р.';
        $project = ServiceLocator::contextService()->getProject();
        if ($project !== null) {
            $currency = ServiceLocator::currency()->getCurrencyByCode($project->currency);
            $currencySign = $currency !== null ? $currency->sign() : $currencySign;
            if ($currency !== null && $currency->code() === 'USD') {
                return  $currencySign . $formattedValue;
            }
            return $formattedValue.'&nbsp;'.$currencySign;
        }
        $userCurrency = ServiceLocator::userEnvironment()->currency();
        if ($userCurrency !== null) {
            $currencySign = $userCurrency->sign;
            if ($userCurrency->code === 'USD') {
                return  $currencySign . $formattedValue;
            }
        }
        return $formattedValue.'&nbsp;'.$currencySign;
    }

    /**
     * @param float $value
     * @return string
     */
    public function formatUserMoneyWithSign($value) {
        $formattedValue = $this->prettyNumber($value);
        $currencySign = 'р.';
        $userCurrency = ServiceLocator::userEnvironment()->currency();
        if ($userCurrency !== null) {
            $currencySign = $userCurrency->sign;
            if ($userCurrency->code === 'USD') {
                return  $currencySign . $formattedValue;
            }
        }
        return $formattedValue.'&nbsp;'.$currencySign;
    }

    /**
     * @param float $value
     * @return string
     */
    public function formatProjectMoneyWithSign($value) {
        $formattedValue = $this->prettyNumber($value);
        $currencySign = 'р.';
        $project = ServiceLocator::contextService()->getProject();
        if ($project !== null) {
            $currency = ServiceLocator::currency()->getCurrencyByCode($project->currency);
            $currencySign = $currency !== null ? $currency->sign() : $currencySign;
            if ($currency !== null && $currency->code() === 'USD') {
                return  $currencySign . $formattedValue;
            }
        }
        return $formattedValue.'&nbsp;'.$currencySign;
    }

    /**
     * @param float $value
     * @return float
     */
    public function formatNumeric($value) {
        return preg_replace("#[^-0-9.]*#", "", $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function favicon($value) {
        return sprintf(FAVICON_URL, $value);
    }

    /**
     * @param string $message
     * @param string $class
     * @return string
     */
    public function tooltip($message, $class = null) {
        $classAttr = is_null($class) ? "" : "class=\"$class\" ";
        $tooltip = <<<TOOLTIP
<sup {$classAttr}data-toggle="tooltip" rel="tooltip" data-placement="top" data-original-title="{$message}" style="cursor: help;">[?]</sup>
TOOLTIP;
        return $tooltip;
    }

    /**
     * @param string $url
     * @return string
     */
    public function urlDecode($url) {
        return urldecode($url);
    }

    /**
     * @param string $json
     * @return string
     */
    public function jsonDecode($json) {
        return json_decode($json, true);
    }

    /**
     * @param string $dateFromText
     * @param string $dateToText
     * @return string
     */
    public function dateRange($dateFromText, $dateToText) {
        return ServiceLocator::dateTimeService()->dateRange($dateFromText, $dateToText);
    }

    /**
     * @param string $script
     * @return string
     */
    public function js($script) {
        $version = $GLOBALS['config']['version'];
        return "<script src=\"{$script}?v={$version}\"></script>";
    }

    /**
     * @param string $style
     * @return string
     */
    public function css($style) {
        $version = $GLOBALS['config']['version'];
        return "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$style}?v={$version}\">";
    }

    /**
     * @param float $value
     * @param int $decimals
     * @return string
     */
    public function prettyNumber($value, $decimals = null) {
        $value = floatval($value);
        if (!is_null($decimals)) {
            $result = number_format($value, $decimals, '.', '&nbsp;');
        } elseif (number_format($value, 2, '.', '') === "0.00") {
            $result = "0";
        } elseif ($value < 5) {
            $result = number_format($value, 2, '.', '&nbsp;');
        } elseif ($value < 10) {
            $result = number_format($value, 1, '.', '&nbsp;');
        } else {
            $result = number_format($value, 0, '.', '&nbsp;');
        }

        /*$spanOpen = "<span class=\"pretty-number\">";
        if (intval($value) === 0) {
            $result = "0";  // . $spanOpen . ".00</span>";
        } elseif ($value < 5) {
            $result = number_format($value, 2, '.', '&nbsp;');
        } elseif ($value < 10) {
            $result = number_format($value, 1, '.', '&nbsp;');
            $result .= $spanOpen . "0</span>";
        } else {
            $result = number_format($value, 0, '.', '&nbsp;');
            $result .= $spanOpen . ".00</span>";
        }
        $valueLength = strlen((string)intval($value));
        if ($valueLength <= 8) {
            $result = $spanOpen . str_repeat("0", 8 - $valueLength) . "</span>" . $result;
        }*/
        return $result;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function debug($value) {
        return Debugger::dump($value, true);
    }

    /**
     * @param string $value
     * @param int $maxLength
     * @param string $postfix
     * @return string
     */
    public function cut($value, $maxLength = 50, $postfix = "...") {
        if (mb_strlen($value) <= $maxLength) {
            return $value;
        }
        $postfixLength = mb_strlen($postfix);
        if ($maxLength <= $postfixLength) {
            return $value;
        }
        return mb_substr($value, 0, ($maxLength - $postfixLength)) . $postfix;
    }

    /**
     * @param \DateTimeZone $dateTimeZone
     * @return string
     */
    public function formatTimezone(\DateTimeZone $dateTimeZone) {
        return ServiceLocator::dateTimeService()->getTimeZonePrettyPrint($dateTimeZone);
    }

    /**
     * @param string|\DateTime $date
     * @return string
     * @throws Exception\Base
     */
    public function formatDateToApplicationTimeZone($date) {
        $dateTime = $this->_instanceDate($date);
        if ($dateTime === null) {
            return null;
        }
        $outputTimeZone = ServiceLocator::dateTimeService()->getApplicationTimeZone();
        $dateTime->setTimezone($outputTimeZone);
        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @param string|\DateTime $date
     * @return string
     * @throws Exception\Base
     */
    public function formatDateWithoutTime($date) {
        $dateTime = $this->_instanceDate($date);
        return $dateTime !== null ? $dateTime->format('Y-m-d') : null;
    }

    /**
     * @param string|\DateTime $date
     * @return string
     * @throws Exception\Base
     */
    public function formatDateShort($date) {
        $dateTime = $this->_instanceDate($date);
        return $dateTime !== null ? $dateTime->format('H:i, d.m') : null;
    }

    /**
     * @param string $date
     * @return string
     * @throws Exception\Base
     */
    public function formatDateWithMonth($date) {
        $dateTime = $this->_instanceDate($date);
        if ($dateTime === null) {
            return "";
        }
        return ServiceLocator::dateTimeService()->getDateWithMonthName($dateTime);
    }

    /**
     * @param string|\DateTime $date
     * @return \DateTime|null
     * @throws Exception\Base
     */
    private function _instanceDate($date) {
        if ($date === null) {
            return null;
        }
        if (is_string($date)) {
            $dateTime = new \DateTime($date);
        } elseif ($date instanceof \DateTime) {
            $dateTime = clone $date;
        } else {
            throw new Exception\Base('This formatter supports only DateTime objects and date-like strings. ' . var_export($date, true));
        }
        $dateTime->setTimezone(currentUserTimeZone());
        return $dateTime;
    }

    /**
     * @param string $value
     * @return boolean
     */
    public function hasTagsTest($value) {
        // A very simple test for now. You are welcome to patch it in case of any improvements required.
        return strpos($value, '<') !== false;
    }

    /**
     * @param string $url
     * @param string $paramName
     * @param string $paramValue
     * @return string
     */
    public function setUrlParam($url, $paramName, $paramValue) {
        return ServiceLocator::urlService()->forcedSetUrlGetParameter($url, $paramName, $paramValue);
    }

    /**
     * @param string $path
     * @return string
     */
    public function image($path) {
        $language = ServiceLocator::localizationService()->getCurrentLanguage();
        $imagePath = "{$language}/{$path}";
        $staticPath = $GLOBALS['config']['paths']['static'];
        $imagesPath = $GLOBALS['config']['static_paths']['images'];
        if (!file_exists("{$staticPath}{$imagesPath}{$imagePath}")) {
            $imagePath = "default/{$path}";
        }
        return "/{$imagesPath}{$imagePath}";
    }
}
