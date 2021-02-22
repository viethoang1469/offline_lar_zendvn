<?php
namespace App\Helpers;
use Config;

class Template {
    public static function showButtonFilter ($controllerName, $itemsStatusCount, $currentFilterStatus, $paramsSearch) { // $currentFilterStatus active inactive all
        $xhtml = null;
        $tmplStatus = Config::get('zvn.template.status');

        if (count($itemsStatusCount) > 0) {
            array_unshift($itemsStatusCount , [
                'count'   => array_sum(array_column($itemsStatusCount, 'count')),
                'status'  => 'all'
            ]);

            foreach ($itemsStatusCount as $item) {  // $item = [count,status]
                $statusValue = $item['status'];  // active inactive block
                $statusValue = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';

                $currentTemplateStatus = $tmplStatus[$statusValue]; // $value['status'] inactive block active
                $link = route($controllerName) . "?filter_status=" .  $statusValue;

                if($paramsSearch['value'] !== ''){
                    $link .= "&search_field=" . $paramsSearch['field'] . "&search_value=" .  $paramsSearch['value'];
                }

                $class  = ($currentFilterStatus == $statusValue) ? 'btn-danger' : 'btn-info';
                $xhtml  .= sprintf('<a href="%s" type="button" class="btn %s">
                                    %s <span class="badge bg-white">%s</span>
                                </a>', $link, $class, $currentTemplateStatus['name'], $item['count']);
            }
        }

        return $xhtml;
    }

    public static function showAreaSearch ($controllerName, $paramsSearch) {
        $xhtml = null;
        $tmplField         = Config::get('zvn.template.search');
        $fieldInController = Config::get('zvn.config.search');

        $controllerName = (array_key_exists($controllerName, $fieldInController)) ? $controllerName : 'default';
        $xhtmlField = null;

        foreach($fieldInController[$controllerName] as $field)  {// all id
            $xhtmlField .= sprintf('<li><a href="#" class="select-field" data-field="%s">%s</a></li>', $field, $tmplField[$field]['name']);
        }

        $searchField = (in_array($paramsSearch['field'],  $fieldInController[$controllerName] )) ? $paramsSearch['field'] : "all";

        $xhtml = sprintf('
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle btn-active-field" data-toggle="dropdown" aria-expanded="false">
                        %s <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        %s
                    </ul>
                </div>
                <input type="text" name="search_value" value="%s" class="form-control" >
                <input type="hidden" name="search_field" value="%s">
                <span class="input-group-btn">
                    <button id="btn-clear-search" type="button" class="btn btn-success" style="margin-right: 0px">Xóa tìm kiếm</button>
                    <button id="btn-search" type="button" class="btn btn-primary">Tìm kiếm</button>
                </span>
            </div>', $tmplField[$searchField]['name'], $xhtmlField, $paramsSearch['value'], $searchField);
        return $xhtml;
    }

    public static function showItemHistory ($by, $time) {
        $xhtml = sprintf(
            '<p><i class="fa fa-user"></i> %s</p>
            <p><i class="fa fa-clock-o"></i> %s</p>', $by, date(Config::get('zvn.format.short_time'), strtotime($time)) );
        return $xhtml;
    }

    public static function showItemStatus ($controllerName, $id, $statusValue) {
        $tmplStatus = Config::get('zvn.template.status');
        $statusValue        = array_key_exists($statusValue, $tmplStatus ) ? $statusValue : 'default';
        $currentTemplateStatus = $tmplStatus[$statusValue];
        $link          = route($controllerName . '/status', ['status' => $statusValue, 'id' => $id]);

        $xhtml = sprintf(
            '<a href="%s" type="button" class="btn-status btn btn-round %s" data-class="%s">%s</a>', $link , $currentTemplateStatus['class'], $currentTemplateStatus['class'], $currentTemplateStatus['name']  );
        return $xhtml;
    }

    public static function showItemIsHome ($controllerName, $id, $isHomeValue) {
        $tmplIsHome = Config::get('zvn.template.is_home');
        $isHomeValue        = array_key_exists($isHomeValue, $tmplIsHome ) ? $isHomeValue : 'yes';
        $currentTemplateIsHome = $tmplIsHome[$isHomeValue];
        $link          = route($controllerName . '/isHome', ['is_home' => $isHomeValue, 'id' => $id]);

        $xhtml = sprintf(
            '<a href="%s" type="button" class="btn btn-round %s">%s</a>', $link , $currentTemplateIsHome['class'], $currentTemplateIsHome['name']  );
        return $xhtml;
    }

    public static function showItemSelect($controllerName, $id, $displayValue, $fieldName)
    {
       $link          = route($controllerName . '/' . $fieldName, [$fieldName => 'value_new', 'id' => $id]);

       $tmplDisplay = Config::get('zvn.template.' . $fieldName);
       $xhtml = sprintf('<select name="select_change_attr" data-url="%s" class="form-control select-ajax">', $link  );

        foreach ($tmplDisplay as $key => $value) {
           $xhtmlSelected = '';
           if ($key == $displayValue) $xhtmlSelected = 'selected="selected"';
            $xhtml .= sprintf('<option value="%s" %s>%s</option>', $key, $xhtmlSelected, $value['name']);
        }
        $xhtml .= '</select>';

        return $xhtml;
    }

    public static function showItemThumb ($controllerName, $thumbName, $thumbAlt) {
        $xhtml = sprintf(
            '<img src="%s" alt="%s" class="zvn-thumb">', asset("images/$controllerName/$thumbName")  , $thumbAlt );
        return $xhtml;
    }

    public static function showButtonAction ($controllerName, $id) {
        $tmplButton   = Config::get('zvn.template.button');
        $buttonInArea = Config::get('zvn.config.button');

        $controllerName = (array_key_exists($controllerName, $buttonInArea)) ? $controllerName : "default";
        $listButtons    = $buttonInArea[$controllerName]; // ['edit', 'delete']

        $xhtml = '<div class="zvn-box-btn-filter">';

        foreach ($listButtons as $btn) {
            $currentButton = $tmplButton[$btn];

            $link = route($controllerName . $currentButton['route-name'], ['id' => $id] );
            $xhtml .= sprintf(
                '<a href="%s" type="button" class="btn btn-icon %s" data-toggle="tooltip" data-placement="top"
                    data-original-title="%s">
                    <i class="fa %s"></i>
                </a>', $link, $currentButton['class'], $currentButton['title'], $currentButton['icon']);
        }

        $xhtml .= '</div>';

        return $xhtml;
    }

    public static function showDatetimeFrontend($dateTime)
    {
        return date_format(date_create($dateTime), Config::get('zvn.format.short_time'));
    }

    public static function showContent($content, $length, $prefix = '...')
    {
        $prefix = ($length == 0) ? '' : $prefix;
        $content = str_replace(['<p>', '</p>'], '', $content);
        return preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $length)) . $prefix;
    }

    public static function showBoxDashboard($box)
    {
        $result = sprintf('<div class="tile-stats">
                                <div class="icon"><i class="fa fa-comments-o"></i></div>
                                <div class="count">%s</div>
                                <h3>%s</h3>
                                <p><a href="%s">Xem chi tiết</a></p>
                            </div>', $box['name'],  $box['total'],  $box['link']);

        return $result;
    }

    public static function showItemOrdering($controllerName, $orderingValue, $id)
    {
        $link = route("$controllerName/ordering", ['ordering' => 'value_new', 'id' => $id]);
        $xhtml = sprintf('<input type="number" class="form-control ordering" id="ordering-%s" data-url="%s" value="%s" style="width: 60px">', $id, $link, $orderingValue);
        return $xhtml;
    }
    public static function callRecursion($list, &$xhtml)
    {

        foreach($list as $val)
        {
            $linkCategory = URL::linkCategory($val['id'], $val['name']);
            $xhtml .= '<li><a class="dropdown-item" href="'.$linkCategory.'">'.$val['name'].'</a>';
            if(isset($val['children'][0]))
            {
                $xhtml .= '<ul class="submenu dropdown-menu">';
                self::callRecursion($val['children'], $xhtml);
                $xhtml .= '</ul>';
            }
            $xhtml .= '</li>';
        }

    }
    public static function showDropdownItem($name, $arrItem)
    {
        $xhtml = '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">'.$name.'</a>
                    <ul class="dropdown-menu">';
        self::callRecursion($arrItem, $xhtml);
        $xhtml .= '</ul>';
        return $xhtml;
    }
}
