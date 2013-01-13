<?php
namespace ZendMax\View\Helper;
/**
 * @copyright  2008, IgorN
 * @author     IgorN (progi2007@gmail.com)
 */
class Truncate
{
    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string  $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     * @return string Trimmed string.
     */
    public function truncate($text, $length = 150, $ending = '...', $exact = false, $considerHtml = false)
    {
        if ($considerHtml) {
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            
            $totalLength = strlen($ending);
            $openTags = array();
            $truncate = '';
            
            foreach ($lines as $lineMatchings) {
                if (!empty($lineMatchings[1])) {
                    $match = '/^<(s*.+?/s*|s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)'
                        . '(s.+?)?)>$/is';
                    if (preg_match($match, $lineMatchings [1])) {
                    } else if (preg_match('/^<s*/([^s]+?)s*>$/s', $lineMatchings [1], $tagMatchings)) {
                        $pos = array_search($tagMatchings [1], $openTags);
                        if ($pos !== false) {
                            unset($openTags [$pos]);
                        }
                    } else if (preg_match('/^<s*([^s>!]+).*?>$/s', $lineMatchings[1], $tagMatchings)) {
                        array_unshift($openTags, strtolower($tagMatchings[1]));
                    }
                    $truncate .= $lineMatchings[1];
                }
                $match = '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i';
                $contentLength = strlen(preg_replace($match, ' ', $lineMatchings[2]));
                if ($totalLength + $contentLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    $match = '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i';
                    if (preg_match_all($match, $lineMatchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }
                    $truncate .= substr($lineMatchings[2], 0, $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $lineMatchings [2];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        
        if (!$exact) {
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;
        
        if ($considerHtml) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        
        return $truncate;
    }
}