<?php
/**
* Slug filter
* Filter used to generate slug
*/
class My_Filter_Slug implements Zend_Filter_Interface
{
    /**
     * Generate a slug.
     * @param string $value String to generate its slug
     * @return string Generated slug
     */
    public function filter($value)
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9-]/i', '-', $value);
        $value = preg_replace('/-[-]*/', '-', $value);
        $value = preg_replace('/-$/', '', $value);
        $value = preg_replace('/^-/', '', $value);
        return $value;
    }
}
