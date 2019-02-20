<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 14/02/2019
 * Time: 22:33
 */

namespace SettingBundle\Twig\Extension;


class CheckColExtension extends  \Twig_Extension {


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('check_col', array($this, 'checkCol')),
        );
    }

    public function checkCol($class)
    {
        if (preg_match("/(col-)[a-z]{2}-[0-9]{1,2}/i", $class,$matches)) {

            $tmp=explode('-',$matches[0]);
            return intval($tmp[2]);
        }
    }

    public function getName()
    {
        return 'application_check_col';
    }
}