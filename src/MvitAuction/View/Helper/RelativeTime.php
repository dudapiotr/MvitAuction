<?php
namespace MvitAuction\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
 
/**
 * Returns string with relative time to/from a date.
 *
 */
class RelativeTime extends AbstractHelper {

    /**
     * __invoke
     *
     * @access public
     * @param  int $timestamp
     * @param  int $precision
     * @return String
     */
    public function __invoke($timestamp, $precision = 2) {
        $ranges = array(
            array('Name' => "year",   'Value' => 31104000),
            array('Name' => "month",  'Value' => 2592000),
            array('Name' => "week",   'Value' => 604800),
            array('Name' => "day",    'Value' => 86400),
            array('Name' => "hour",   'Value' => 3600),
            array('Name' => "minute", 'Value' => 60),
            array('Name' => "second", 'Value' => 1),
        );
        $diff = time() - $timestamp;
        if ($diff < 0) $diff = abs($diff);
        $i = 0;
        $return = "";
        foreach ($ranges as $range) {
            $intval = floor($diff / $range["Value"]);
            $diff %= $range["Value"];
            if ($intval == 1) {
                $return .= $intval." ".$range["Name"]." ";
                $i++;
            } elseif ($intval > 1) {
                $return .= $intval." ".$range["Name"]."s ";
                $i++;
            }
            if ($i == $precision) break;
        }
        
        return $return;
    }
}