<?php

if (!function_exists('toSlug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string $title
     * @param  string $separator
     *
     * @return string
     */
    function toSlug($title, $separator = '-')
    {
        $title = mb_strtolower($title, 'UTF-8');
        $title = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $title);
        $title = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $title);
        $title = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $title);
        $title = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $title);
        $title = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $title);
        $title = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $title);
        $title = preg_replace('/(đ)/', 'd', $title);
        $title = preg_replace('/([^a-z0-9\-]+)/', $separator, $title);
        $title = preg_replace('/([' . $separator . ']+)/', $separator, $title);
        $title = preg_replace('/(^[^a-z0-9]|[^a-z0-9]$)/', '', $title);
        return $title;
    }
}

if ( !function_exists('show_selected_option')) {
    function show_selected_option($categories, $selected_id = 0, $class = '', $dataAtrribute = null) {
        $html = '';

        if (count($categories)) return $html;
        $html = $class != '' ? '<select class="'.$class.'">' : '<select>';
        foreach ($categories as $category) {
            $selected = $category->id == $selected_id ? 'selected' : '';
            $html .= '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
        }

        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('toSlug')) {
    /**
     * Replace url img for render PDF
     * @param  string $string content html
     * @return string         
     */
    function replace_url_img($string) {
        preg_match_all('@src="([^"]+)"+@', $string, $match );
        $srcs = array_pop($match);
        
        foreach ($srcs as $src) {
            $tmp = explode('uploads', $src);
            $replace = '/uploads'.array_pop($tmp);
            $string = str_replace($src, $replace, $string);
        }

        return $string;
    }
}